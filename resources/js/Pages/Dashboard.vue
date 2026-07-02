<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue'
import Card from '@components/ui/card/Card.vue'
import CardHeader from '@components/ui/card/CardHeader.vue'
import CardTitle from '@components/ui/card/CardTitle.vue'
import CardContent from '@components/ui/card/CardContent.vue'
import Badge from '@components/ui/badge/Badge.vue'
import Table from '@components/ui/table/Table.vue'

const props = defineProps({
    products: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({
            totalProducts: 0,
            avgMargin: '0%',
            highMarginProducts: 0,
            totalCategories: 0
        })
    }
})

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num)

const topProducts = computed(() => {
    return [...props.products]
        .sort((a, b) => b.margin_percent - a.margin_percent)
        .slice(0, 5)
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Total Produk
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ props.stats.totalProducts }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Rata-rata Margin
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ props.stats.avgMargin }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Produk >30% Margin
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ props.stats.highMarginProducts }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            Kategori
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ props.stats.totalCategories }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Top Margin -->
            <Card>
                <CardHeader>
                    <CardTitle>Top 5 Margin Tertinggi</CardTitle>
                </CardHeader>
                <CardContent>
                    <Table>
                        <thead class="border-b bg-muted/50">
                            <tr>
                                <th class="h-12 px-4 text-left font-medium">Produk</th>
                                <th class="h-12 px-4 text-left font-medium">Harga China</th>
                                <th class="h-12 px-4 text-left font-medium">Harga Indo</th>
                                <th class="h-12 px-4 text-left font-medium">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in topProducts" :key="p.id" class="border-b hover:bg-muted/50">
                                <td class="p-4 font-medium">{{ p.name }}</td>
                                <td class="p-4">¥{{ p.price_china_cny }}</td>
                                <td class="p-4">Rp{{ formatNumber(p.price_indonesia_idr) }}</td>
                                <td class="p-4">
                                    <Badge :variant="p.margin_percent > 30 ? 'success' : 'default'">
                                       {{ p.margin_percent.toFixed(2) }}%
                                    </Badge>
                                </td>
                            </tr>
                        </tbody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>