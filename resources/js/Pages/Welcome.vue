<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { 
    BarChart3, 
    Brain, 
    TrendingUp, 
    Shield, 
    Zap, 
    ChevronRight,
    Package,
    Calculator,
    ArrowRight
} from 'lucide-vue-next';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const isVisible = ref(false);

onMounted(() => {
    isVisible.value = true;
});

const features = [
    {
        icon: Package,
        title: 'Manajemen Produk',
        description: 'Kelola produk dari berbagai marketplace dalam satu dashboard terintegrasi.'
    },
    {
        icon: Calculator,
        title: 'Perhitungan SPK',
        description: 'Gunakan metode SAW dan Weighted Product untuk menentukan produk terbaik.'
    },
    {
        icon: TrendingUp,
        title: 'Analisis Margin',
        description: 'Pantau margin keuntungan real-time dengan perhitungan otomatis.'
    },
    {
        icon: Brain,
        title: 'Keputusan Cerdas',
        description: 'AI-assisted decision making untuk strategi bisnis yang lebih baik.'
    },
    {
        icon: Shield,
        title: 'Data Aman',
        description: 'Keamanan data terjamin dengan autentikasi dan otorisasi berlapis.'
    },
    {
        icon: Zap,
        title: 'Performa Tinggi',
        description: 'Sistem responsif dengan teknologi Laravel + Vue 3 terkini.'
    }
];
</script>

<template>
    <Head title="SPK Marketplace - Sistem Pendukung Keputusan" />

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white overflow-hidden">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-50 border-b border-white/10 backdrop-blur-xl bg-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25">
                            <BarChart3 class="w-6 h-6 text-white" />
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                            SPK Marketplace
                        </span>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4">
                        <template v-if="canLogin">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40"
                            >
                                Dashboard
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="px-5 py-2.5 text-gray-300 hover:text-white font-medium transition-colors"
                                >
                                    Log in
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium transition-all duration-200 border border-white/20 hover:border-white/30"
                                >
                                    Register
                                </Link>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative z-10 pt-20 pb-32 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center">
                <div 
                    :class="[
                        'transition-all duration-1000 transform',
                        isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                    ]"
                >
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium mb-8">
                        <Zap class="w-4 h-4" />
                        Sistem Pendukung Keputusan Terbaik
                    </div>

                    <!-- Heading -->
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight mb-6">
                        Optimalkan Bisnis<br>
                        <span class="bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 bg-clip-text text-transparent">
                            Marketplace Anda
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="max-w-2xl mx-auto text-lg sm:text-xl text-gray-400 mb-10 leading-relaxed">
                        Gunakan metode SAW dan Weighted Product untuk menganalisis produk, 
                        membandingkan margin, dan mengambil keputusan bisnis yang lebih cerdas.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('register')"
                            class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl font-semibold text-lg transition-all duration-200 shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-105"
                        >
                            Mulai Gratis
                            <ChevronRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="route('dashboard')"
                            class="group inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl font-semibold text-lg transition-all duration-200 shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-105"
                        >
                            Buka Dashboard
                            <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </Link>
                        <a
                            href="#features"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white rounded-2xl font-semibold text-lg transition-all duration-200 border border-white/10 hover:border-white/20"
                        >
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div 
                    :class="[
                        'mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-3xl mx-auto transition-all duration-1000 delay-300 transform',
                        isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                    ]"
                >
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-emerald-400">2</div>
                        <div class="text-sm text-gray-400 mt-1">Metode SPK</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-teal-400">7</div>
                        <div class="text-sm text-gray-400 mt-1">Kriteria Analisis</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-cyan-400">100%</div>
                        <div class="text-sm text-gray-400 mt-1">Real-time</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-blue-400">∞</div>
                        <div class="text-sm text-gray-400 mt-1">Produk</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative z-10 py-24 px-4 sm:px-6 lg:px-8 border-t border-white/10">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                        Fitur <span class="text-emerald-400">Unggulan</span>
                    </h2>
                    <p class="text-gray-400 max-w-xl mx-auto">
                        Semua yang Anda butuhkan untuk menganalisis dan mengoptimalkan produk marketplace.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.title"
                        :class="[
                            'group p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-emerald-500/30 transition-all duration-300 hover:bg-white/10 hover:-translate-y-1',
                            'transition-all duration-700 transform',
                            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
                        ]"
                        :style="{ transitionDelay: `${400 + index * 100}ms` }"
                    >
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center mb-4 group-hover:from-emerald-500/30 group-hover:to-teal-500/30 transition-colors">
                            <component :is="feature.icon" class="w-6 h-6 text-emerald-400" />
                        </div>
                        <h3 class="text-lg font-semibold mb-2 text-white group-hover:text-emerald-400 transition-colors">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative z-10 py-24 px-4 sm:px-6 lg:px-8 border-t border-white/10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-br from-emerald-500/10 via-teal-500/10 to-cyan-500/10 border border-emerald-500/20">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                        Siap Mengoptimalkan Bisnis Anda?
                    </h2>
                    <p class="text-gray-400 mb-8 max-w-lg mx-auto">
                        Bergabung sekarang dan rasakan kemudahan pengambilan keputusan dengan data yang akurat.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('register')"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl font-semibold transition-all duration-200 shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-105"
                        >
                            Daftar Sekarang
                            <ArrowRight class="w-5 h-5" />
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-2xl font-semibold transition-all duration-200 shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-105"
                        >
                            Ke Dashboard
                            <ArrowRight class="w-5 h-5" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 py-8 px-4 sm:px-6 lg:px-8 border-t border-white/10">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <BarChart3 class="w-5 h-5 text-emerald-400" />
                    <span class="font-semibold">SPK Marketplace</span>
                </div>
                <p class="text-sm text-gray-500">
                    Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}
                </p>
            </div>
        </footer>
    </div>
</template>