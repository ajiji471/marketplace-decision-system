<script setup>
import { ref, computed } from 'vue'
import { LayoutDashboard, Package, Calculator, ChevronDown, Menu, X, PanelLeftClose, PanelLeftOpen } from 'lucide-vue-next'
import { cn } from '@lib/utils'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const isOpen = ref(true);
const isMobileOpen = ref(false);

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), icon: LayoutDashboard, active: route().current('dashboard') },
    { name: 'Produk', href: route('products'), icon: Package, active: route().current('products') },
    { name: 'SPK', href: route('spk'), icon: Calculator, active: route().current('spk') },
]

const pageTitle = computed(() => {
    const currentRoute = route().current()
    const titles = {
        'dashboard': 'Dashboard',
        'products': 'Daftar Produk',
        'product-detail': 'Detail Produk',
        'spk': 'Perhitungan SPK',
    }
    return titles[currentRoute] || 'SPK Marketplace'
})

// Toggle mobile sidebar
function toggleMobileSidebar() {
    isMobileOpen.value = !isMobileOpen.value
}

// Close mobile sidebar saat klik link
function closeMobileSidebar() {
    isMobileOpen.value = false
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Mobile Sidebar Overlay -->
        <div 
            v-if="isMobileOpen" 
            @click="closeMobileSidebar"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        <!-- Mobile Header (hanya muncul di mobile) -->
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 lg:hidden">
            <button 
                @click="toggleMobileSidebar"
                class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100"
            >
                <Menu v-if="!isMobileOpen" class="h-5 w-5" />
                <X v-else class="h-5 w-5" />
            </button>

            <h2 class="text-lg font-semibold text-gray-900">{{ pageTitle }}</h2>

            <div class="ml-auto flex items-center gap-4">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                            <span class="hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                            <ChevronDown class="h-4 w-4" />
                        </button>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </header>

        <!-- Sidebar -->
        <aside 
            :class="cn(
                'fixed left-0 top-0 z-50 h-screen border-r border-gray-200 bg-white transition-all duration-300 ease-in-out',
                // Mobile: full width slide dari kiri
                'lg:translate-x-0',
                isMobileOpen ? 'w-64 translate-x-0' : '-translate-x-full w-64 lg:w-16',
                // Desktop: collapse/expand
                isOpen ? 'lg:w-64' : 'lg:w-16'
            )"
        >
            <!-- Sidebar Header -->
            <div 
                :class="cn(
                    'flex h-16 items-center border-b border-gray-200',
                    isOpen || isMobileOpen ? 'px-6' : 'px-0 lg:px-2'
                )"
            >
                <Link :href="route('dashboard')" class="flex items-center gap-2 overflow-hidden whitespace-nowrap">
                    <ApplicationLogo class="block h-8 w-auto shrink-0 fill-current text-gray-800" />
                    <span 
                        :class="cn(
                            'text-lg font-bold text-gray-900 transition-all duration-300',
                            isOpen || isMobileOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 lg:hidden'
                        )"
                    >
                        SPK Marketplace
                    </span>
                </Link>
                
                <!-- Close button di mobile sidebar -->
                <button 
                    @click="closeMobileSidebar"
                    class="ml-auto lg:hidden p-1 text-gray-500 hover:bg-gray-100 rounded-lg"
                >
                    <X class="h-5 w-5" />
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="space-y-1 p-2">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    @click="closeMobileSidebar"
                    :class="cn(
                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-300',
                        item.active
                            ? 'bg-gray-900 text-white'
                            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                        !isOpen && !isMobileOpen && 'lg:justify-center'
                    )"
                >
                    <component :is="item.icon" class="h-5 w-5 shrink-0" />
                    <span 
                        :class="cn(
                            'transition-all duration-300 overflow-hidden whitespace-nowrap',
                            isOpen || isMobileOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 lg:hidden'
                        )"
                    >
                        {{ item.name }}
                    </span>
                </Link>
            </nav>

            <!-- Sidebar Footer -->
            <div 
                :class="cn(
                    'absolute bottom-0 w-full border-t border-gray-200',
                    isOpen || isMobileOpen ? 'p-4' : 'p-2'
                )"
            >
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="h-8 w-8 shrink-0 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div 
                        :class="cn(
                            'flex-1 min-w-0 transition-all duration-300',
                            isOpen || isMobileOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0 lg:hidden'
                        )"
                    >
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="cn('transition-all duration-300', isOpen ? 'lg:ml-64' : 'lg:ml-16')">
            <!-- Desktop Top Header (hidden di mobile) -->
            <header class="sticky top-0 z-30 hidden lg:flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 lg:px-8">
                <button 
                    @click="isOpen = !isOpen"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                >
                    <PanelLeftClose v-if="isOpen" class="h-5 w-5" />
                    <PanelLeftOpen v-else class="h-5 w-5" />
                </button>

                <h2 class="text-lg font-semibold text-gray-900">{{ pageTitle }}</h2>

                <div class="ml-auto flex items-center gap-4">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
                                <span class="hidden sm:inline">{{ $page.props.auth.user.name }}</span>
                                <ChevronDown class="h-4 w-4" />
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>