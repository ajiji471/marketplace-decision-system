<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import Card from '@components/ui/card/Card.vue'
import CardContent from '@components/ui/card/CardContent.vue'
import Button from '@components/ui/button/Button.vue'
import Input from '@components/ui/input/Input.vue'
import Badge from '@components/ui/badge/Badge.vue'
import DataTable from '@components/DataTable.vue'
import Pagination from '@components/ui/pagination/Pagination.vue'

// shadcn-vue Dialog imports
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@components/ui/dialog'

const props = defineProps({
    products: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
            from: 0,
            to: 0
        })
    },
    filters: {
        type: Object,
        default: () => ({
            category: '',
            min_margin: ''
        })
    }
})

const showAddModal = ref(false)
const showPriceModal = ref(false)
const editingProduct = ref(null)

const filterForm = reactive({
    category: props.filters.category || '',
    min_margin: props.filters.min_margin || ''
})

const newProduct = reactive({
    name: '',
    category: '',
    subcategory: '',
    price_china_cny: 0,
    price_indonesia_idr: 0,
    shipping_cost_idr: 0,
    tax_estimate_idr: 0,
    weight_kg: 0
})

const priceUpdate = reactive({
    price_china_cny: null,
    price_indonesia_idr: null,
    note: ''
})

const columns = [
    { key: 'name', label: 'Nama', width: 'w-48' },
    { key: 'category', label: 'Kategori' },
    {
        key: 'price_china_cny',
        label: 'Harga China',
        formatter: (val) => `¥${val}`
    },
    {
        key: 'price_indonesia_idr',
        label: 'Harga Indo',
        formatter: (val) => `Rp${new Intl.NumberFormat('id-ID').format(val)}`
    },
    {
        key: 'total_cost_from_china',
        label: 'Total Cost',
        formatter: (val) => `Rp${new Intl.NumberFormat('id-ID').format(val)}`
    },
    {
        key: 'margin_percent',
        label: 'Margin',
        formatter: (val) => `${Number(val).toFixed(2)}%`
    },
    {
        key: 'marketplace_summary',
        label: 'Terjual',
        formatter: (val) => val?.total_sold || 0
    },
    {
        key: 'actions',
        label: 'Aksi',
        align: 'center'
    }
]

const formatNumber = (num) => {
    if (!num && num !== 0) return '0'
    return new Intl.NumberFormat('id-ID').format(num)
}

const getMarginVariant = (margin) => {
    if (margin > 40) return 'success'
    if (margin > 20) return 'warning'
    return 'destructive'
}

function applyFilters() {
    router.get(route('products'), {
        category: filterForm.category,
        min_margin: filterForm.min_margin
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

function handlePageChange(page) {
    router.get(route('products'), {
        page: page,
        category: filterForm.category,
        min_margin: filterForm.min_margin
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

function handlePerPageChange(perPage) {
    router.get(route('products'), {
        per_page: perPage,
        category: filterForm.category,
        min_margin: filterForm.min_margin
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

function handleRowClick(item) {
    router.visit(route('products.show', item.id))
}

function editPrice(product) {
    editingProduct.value = product
    priceUpdate.price_china_cny = product.price_china_cny
    priceUpdate.price_indonesia_idr = product.price_indonesia_idr
    priceUpdate.note = ''
    showPriceModal.value = true
}

function saveProduct() {
    router.post(route('products.store'), { ...newProduct }, {
        onSuccess: () => {
            showAddModal.value = false
            Object.keys(newProduct).forEach(k => {
                newProduct[k] = ['price_china_cny', 'price_indonesia_idr', 'shipping_cost_idr', 'tax_estimate_idr', 'weight_kg'].includes(k) ? 0 : ''
            })
        },
        onError: (errors) => {
            alert('Gagal menyimpan: ' + Object.values(errors).join(', '))
        }
    })
}

function savePriceUpdate() {
    router.patch(route('products.update-price', editingProduct.value.id), { ...priceUpdate }, {
        onSuccess: () => {
            showPriceModal.value = false
        },
        onError: (errors) => {
            alert('Gagal update harga: ' + Object.values(errors).join(', '))
        }
    })
}
</script>

<template>
    <Head title="Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Daftar Produk
            </h2>
        </template>

        <div class="mx-auto space-y-4">
            <!-- Filters -->
            <Card>
                <CardContent class="pt-6 flex gap-4 flex-wrap items-end">
                    <div>
                        <label class="text-sm font-medium mb-1 block">Kategori</label>
                        <Input
                            v-model="filterForm.category"
                            placeholder="Filter kategori..."
                            class="w-48"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-1 block">Min Margin (%)</label>
                        <Input
                            v-model.number="filterForm.min_margin"
                            type="number"
                            placeholder="Min margin %"
                            class="w-32"
                        />
                    </div>
                    <Button @click="applyFilters">Filter</Button>
                    <Button variant="outline" @click="showAddModal = true">+ Tambah Produk</Button>
                </CardContent>
            </Card>

            <!-- DataTable -->
            <Card>
                <DataTable
                    :columns="columns"
                    :data="products.data"
                    :loading="false"
                    empty-text="Tidak ada data produk"
                    @row-click="handleRowClick"
                >
                    <template #cell-category="{ value }">
                        <Badge variant="secondary">{{ value }}</Badge>
                    </template>

                    <template #cell-margin_percent="{ value }">
                        <Badge :variant="getMarginVariant(value)">
                            {{ Number(value).toFixed(2) }}%
                        </Badge>
                    </template>

                    <template #cell-actions="{ item }">
                        <Button
                            variant="ghost"
                            size="sm"
                            @click.stop="editPrice(item)"
                        >
                            Update Harga
                        </Button>
                    </template>
                </DataTable>

                <!-- Pagination -->
                <Pagination
                    :meta="products"
                    @page-change="handlePageChange"
                    @per-page-change="handlePerPageChange"
                />
            </Card>
        </div>
    </AuthenticatedLayout>

    <!-- Add Product Dialog -->
    <Dialog v-model:open="showAddModal">
        <DialogContent class="sm:max-w-[600px]">
            <DialogHeader>
                <DialogTitle>Tambah Produk Baru</DialogTitle>
                <DialogDescription>
                    Isi detail produk baru di bawah ini.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-4 py-4">
                <div>
                    <label class="text-sm font-medium">Nama Produk</label>
                    <Input v-model="newProduct.name" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Kategori</label>
                        <Input v-model="newProduct.category" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Subkategori</label>
                        <Input v-model="newProduct.subcategory" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Harga China (CNY)</label>
                        <Input v-model.number="newProduct.price_china_cny" type="number" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Harga Indonesia (IDR)</label>
                        <Input v-model.number="newProduct.price_indonesia_idr" type="number" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Ongkir (IDR)</label>
                        <Input v-model.number="newProduct.shipping_cost_idr" type="number" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Pajak Estimasi (IDR)</label>
                        <Input v-model.number="newProduct.tax_estimate_idr" type="number" />
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium">Berat (kg)</label>
                    <Input v-model.number="newProduct.weight_kg" type="number" step="0.1" />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="showAddModal = false">Batal</Button>
                <Button @click="saveProduct">Simpan</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Update Price Dialog -->
    <Dialog v-model:open="showPriceModal">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Update Harga</DialogTitle>
                <DialogDescription>
                    Update harga untuk {{ editingProduct?.name }}
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-4 py-4">
                <div>
                    <label class="text-sm font-medium">Harga China Baru (CNY)</label>
                    <Input v-model.number="priceUpdate.price_china_cny" type="number" />
                </div>
                <div>
                    <label class="text-sm font-medium">Harga Indonesia Baru (IDR)</label>
                    <Input v-model.number="priceUpdate.price_indonesia_idr" type="number" />
                </div>
                <div>
                    <label class="text-sm font-medium">Catatan</label>
                    <Input v-model="priceUpdate.note" placeholder="e.g. kenaikan harga komponen" />
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="showPriceModal = false">Batal</Button>
                <Button @click="savePriceUpdate">Update</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>