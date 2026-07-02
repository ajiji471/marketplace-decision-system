<!-- resources/js/components/ui/Pagination.vue -->
<script setup>
import { computed } from 'vue'
import Button from '@components/ui/button/Button.vue'
import Select from '@components/ui/select/Select.vue'
import SelectTrigger from '@components/ui/select/SelectTrigger.vue'
import SelectValue from '@components/ui/select/SelectValue.vue'
import SelectContent from '@components/ui/select/SelectContent.vue'
import SelectItem from '@components/ui/select/SelectItem.vue'
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next'

const props = defineProps({
    meta: {
        type: Object,
        required: true,
        default: () => ({
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
            from: 0,
            to: 0
        })
    },
    maxVisible: {
        type: Number,
        default: 5
    }
})

const emit = defineEmits(['page-change', 'per-page-change'])

const visiblePages = computed(() => {
    const current = props.meta.current_page
    const last = props.meta.last_page
    const max = props.maxVisible

    if (last <= max) {
        return Array.from({ length: last }, (_, i) => i + 1)
    }

    let start = Math.max(1, current - Math.floor(max / 2))
    let end = Math.min(last, start + max - 1)

    if (end - start + 1 < max) {
        start = Math.max(1, end - max + 1)
    }

    return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})

function goToPage(page) {
    if (page < 1 || page > props.meta.last_page) return
    if (page === props.meta.current_page) return
    emit('page-change', page)
}

function handlePerPageChange(value) {
    emit('per-page-change', Number(value))
}
</script>

<template>
    <div v-if="meta.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t">
        <!-- Info -->
        <span class="text-sm text-muted-foreground">
            Menampilkan {{ meta.from || 0 }} - {{ meta.to || 0 }} dari {{ meta.total }} data
        </span>

        <!-- Navigation -->
        <div class="flex items-center gap-1">
            <Button
                variant="outline"
                size="sm"
                class="px-2"
                :disabled="meta.current_page === 1"
                @click="goToPage(1)"
            >
                <ChevronsLeft class="h-4 w-4" />
            </Button>

            <Button
                variant="outline"
                size="sm"
                class="px-2"
                :disabled="meta.current_page === 1"
                @click="goToPage(meta.current_page - 1)"
            >
                <ChevronLeft class="h-4 w-4" />
            </Button>

            <Button
                v-for="page in visiblePages"
                :key="page"
                :variant="meta.current_page === page ? 'default' : 'outline'"
                size="sm"
                class="min-w-[36px]"
                @click="goToPage(page)"
            >
                {{ page }}
            </Button>

            <Button
                variant="outline"
                size="sm"
                class="px-2"
                :disabled="meta.current_page === meta.last_page"
                @click="goToPage(meta.current_page + 1)"
            >
                <ChevronRight class="h-4 w-4" />
            </Button>

            <Button
                variant="outline"
                size="sm"
                class="px-2"
                :disabled="meta.current_page === meta.last_page"
                @click="goToPage(meta.last_page)"
            >
                <ChevronsRight class="h-4 w-4" />
            </Button>
        </div>

        <!-- Per Page Selector -->
<div class="flex items-center gap-2">
    <span class="text-sm text-muted-foreground">Per halaman:</span>
    <Select
        :model-value="String(meta.per_page || 10)"
        @update:model-value="handlePerPageChange"
    >
        <SelectTrigger class="w-16 h-8 text-xs">
            <SelectValue />
        </SelectTrigger>
        <SelectContent class="min-w-0">
            <SelectItem value="5" class="text-xs px-2 py-1">5</SelectItem>
            <SelectItem value="10" class="text-xs px-2 py-1">10</SelectItem>
            <SelectItem value="20" class="text-xs px-2 py-1">20</SelectItem>
            <SelectItem value="50" class="text-xs px-2 py-1">50</SelectItem>
        </SelectContent>
    </Select>
</div>
    </div>

    <div v-else-if="meta.total > 0" class="flex items-center justify-between px-6 py-4 border-t">
        <span class="text-sm text-muted-foreground">
            Menampilkan {{ meta.total }} data
        </span>
    </div>
</template>