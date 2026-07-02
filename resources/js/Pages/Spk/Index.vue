<script setup>
import { computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { cn } from '@/lib/utils'
import Card from '@/Components/ui/card/Card.vue'
import CardHeader from '@/Components/ui/card/CardHeader.vue'
import CardTitle from '@/Components/ui/card/CardTitle.vue'
import CardContent from '@/Components/ui/card/CardContent.vue'
import Button from '@/Components/ui/button/Button.vue'
import Badge from '@/Components/ui/badge/Badge.vue'
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select'
import Slider from '@/Components/ui/slider/Slider.vue'
import DataTable from '@/Components/DataTable.vue'

const props = defineProps({
    criteria: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
    results: {
        type: Object,
        default: () => null
    },
    filters: {
        type: Object,
        default: () => null
    }
})

// Inertia form state
const form = useForm({
    method: props.filters?.method || 'saw',
    category: props.filters?.category || '',
    weights: {},
    limit: 20
})

// Initialize weights dari criteria 
props.criteria.forEach(c => {
    form.weights[c.key] = props.filters?.weights?.[c.key] ?? c.default_weight
})

const isLoading = computed(() => form.processing)

const totalWeight = computed(() => 
    Object.values(form.weights).reduce((a, b) => a + (b || 0), 0)
)

// Helper: shadcn-vue slider butuh array
function getSliderValue(key) {
    return [form.weights[key] || 0]
}

function setSliderValue(key, val) {
    form.weights[key] = val[0]
}

// Transform results
const sawResults = computed(() => {
    if (!props.results?.saw) return []
    return props.results.saw.map(item => ({
        ...item,
        product_name: item.product?.name || 'Produk #' + item.product_id,
        margin_percent: item.details?.margin_percent,
        sold_count: item.details?.sold_count,
        rating: item.details?.rating,
    }))
})

const wpResults = computed(() => {
    if (!props.results?.wp) return []
    return props.results.wp.map(item => ({
        ...item,
        product_name: item.product?.name || 'Produk #' + item.product_id,
        margin_percent: item.details?.margin_percent,
    }))
})

const sawColumns = [
    { 
        key: 'rank', 
        label: 'Rank', 
        width: 'w-16',
        align: 'center'
    },
    { key: 'product_name', label: 'Produk' },
    { 
        key: 'score', 
        label: 'Score',
        formatter: (val) => val.toFixed(4)
    },
    { 
        key: 'margin_percent', 
        label: 'Margin',
        formatter: (val) => `${val?.toFixed(1) || 0}%`
    },
    { 
        key: 'sold_count', 
        label: 'Terjual',
        formatter: (val) => Math.round(val || 0).toLocaleString('id-ID')
    },
    { 
        key: 'rating', 
        label: 'Rating',
        formatter: (val) => val?.toFixed(1) || 0
    },
]

const wpColumns = [
    { 
        key: 'rank', 
        label: 'Rank', 
        width: 'w-16',
        align: 'center'
    },
    { key: 'product_name', label: 'Produk' },
    { 
        key: 'score', 
        label: 'Score',
        formatter: (val) => val.toFixed(6)
    },
    { 
        key: 'margin_percent', 
        label: 'Margin',
        formatter: (val) => `${val?.toFixed(1) || 0}%`
    },
]

function calculate() {
    if (Math.abs(totalWeight.value - 1) > 0.01) {
        alert('Total bobot harus = 1.00 (saat ini: ' + totalWeight.value.toFixed(2) + ')')
        return
    }

    form.post(route('spk.calculate'), {
        preserveState: true,
        preserveScroll: true,
        onError: (errors) => {
            console.error('SPK Calculation error:', errors)
            alert('Gagal menghitung SPK: ' + Object.values(errors).join(', '))
        }
    })
}

function resetWeights() {
    props.criteria.forEach(c => {
        form.weights[c.key] = c.default_weight
    })
}

function getRankClass(rank) {
    return cn(
        'inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold',
        rank === 1 ? 'bg-yellow-100 text-yellow-700' :
        rank === 2 ? 'bg-gray-200 text-gray-700' :
        rank === 3 ? 'bg-orange-100 text-orange-700' :
        'bg-muted text-muted-foreground'
    )
}
</script>

<template>
    <Head title="SPK" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Perhitungan SPK</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- Configuration -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Konfigurasi SPK</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="flex gap-4 flex-wrap items-end">
                                <!-- Metode -->
                                <div class="flex flex-col gap-1">
                                    <label class="text-sm font-medium mb-1 block">Metode:</label>
                                    <Select v-model="form.method">
                                        <SelectTrigger class="w-40">
                                            <SelectValue placeholder="Pilih metode" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="saw">SAW</SelectItem>
                                            <SelectItem value="wp">Weighted Product</SelectItem>
                                            <SelectItem value="both">Keduanya</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Kategori -->
                                <div class="flex items-end gap-2">
                                    <div>
                                        <label class="text-sm font-medium mb-1 block">Kategori:</label>
                                        <Select v-model="form.category">
                                            <SelectTrigger class="w-40">
                                                <SelectValue placeholder="Semua Kategori" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="cat in categories"
                                                    :key="cat"
                                                    :value="cat"
                                                >
                                                    {{ cat }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <Button
                                        v-if="form.category"
                                        variant="ghost"
                                        size="sm"
                                        class="mb-0.5"
                                        @click="form.category = ''"
                                    >
                                        Reset
                                    </Button>
                                </div>

                                <Button @click="calculate" :disabled="isLoading">
                                    {{ isLoading ? 'Menghitung...' : 'Hitung' }}
                                </Button>
                                <Button variant="outline" @click="resetWeights">Reset Bobot</Button>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium">Bobot Kriteria</h4>
                                    <Badge :variant="Math.abs(totalWeight - 1) < 0.01 ? 'success' : 'destructive'">
                                        Total: {{ totalWeight.toFixed(2) }}
                                    </Badge>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div v-for="c in criteria" :key="c.key" class="space-y-2">
                                        <div class="flex justify-between">
                                            <label class="text-sm">{{ c.label }}</label>
                                            <span class="text-xs text-muted-foreground uppercase">{{ c.type }}</span>
                                        </div>
                                        <Slider
                                            :model-value="getSliderValue(c.key)"
                                            @update:model-value="(val) => setSliderValue(c.key, val)"
                                            :max="1"
                                            :step="0.01"
                                            :min="0"
                                        />
                                        <div class="text-xs text-right text-muted-foreground">
                                            {{ (form.weights[c.key] || 0).toFixed(2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Loading State -->
                    <Card v-if="isLoading">
                        <CardContent class="py-12 text-center">
                            <p class="text-muted-foreground">Menghitung peringkat produk...</p>
                        </CardContent>
                    </Card>

                    <!-- SAW Results -->
                    <Card v-if="sawResults.length > 0">
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Hasil SAW (Simple Additive Weighting)</CardTitle>
                            <Badge variant="secondary">{{ sawResults.length }} produk</Badge>
                        </CardHeader>
                        <CardContent>
                            <DataTable
                                :columns="sawColumns"
                                :data="sawResults"
                                :internal-pagination="true"
                                :internal-per-page="5"
                                empty-text="Tidak ada hasil SAW"
                            >
                                <template #cell-rank="{ value }">
                                    <span :class="getRankClass(value)">
                                        {{ value }}
                                    </span>
                                </template>
                                <template #cell-rating="{ value }">
                                    <div class="flex items-center gap-1">
                                        <span>{{ Number(value).toFixed(1) }}</span>
                                        <span class="text-yellow-500">★</span>
                                    </div>
                                </template>
                            </DataTable>
                        </CardContent>
                    </Card>

                    <!-- WP Results -->
                    <Card v-if="wpResults.length > 0">
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Hasil Weighted Product</CardTitle>
                            <Badge variant="secondary">{{ wpResults.length }} produk</Badge>
                        </CardHeader>
                        <CardContent>
                            <DataTable
                                :columns="wpColumns"
                                :data="wpResults"
                                :internal-pagination="true"
                                :internal-per-page="5"
                                empty-text="Tidak ada hasil WP"
                            >
                                <template #cell-rank="{ value }">
                                    <span :class="getRankClass(value)">
                                        {{ value }}
                                    </span>
                                </template>
                            </DataTable>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>