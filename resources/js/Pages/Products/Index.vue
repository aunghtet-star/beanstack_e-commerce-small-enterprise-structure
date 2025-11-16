<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    products: Object,
    title: String,
    category: String,
    filters: Object,
});

const page = usePage();
const isAuthenticated = computed(() => page.props.auth?.user != null);
const cartCount = computed(() => page.props.cartCount || 0);
const wishlistCount = computed(() => page.props.wishlistCount || 0);

const search = ref(props.filters?.search || '');
const showSearchModal = ref(false);

watch(search, (value) => {
    router.get(route('products.index'), { search: value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
});

const toggleSearch = () => {
    showSearchModal.value = !showSearchModal.value;
    if (showSearchModal.value) {
        setTimeout(() => {
            document.getElementById('search-input')?.focus();
        }, 100);
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />

        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">BeanStack</span>
                        </Link>
                    </div>

                    <!-- Center Navigation -->
                    <div class="hidden md:flex space-x-8">
                        <Link href="/products" class="text-gray-700 hover:text-gray-900 font-medium">Shop</Link>
                        <Link href="/category/men" class="text-gray-700 hover:text-gray-900 font-medium">Men</Link>
                        <Link href="/category/women" class="text-gray-700 hover:text-gray-900 font-medium">Women</Link>
                        <Link href="/category/new-arrivals" class="text-gray-700 hover:text-gray-900 font-medium">New Arrivals</Link>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-6">
                        <button @click="toggleSearch" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>

                        <Link v-if="!isAuthenticated" :href="route('login')" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </Link>

                        <Link v-if="isAuthenticated" :href="route('wishlist.index')" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ wishlistCount }}</span>
                        </Link>

                        <Link :href="route('cart.index')" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ cartCount }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Search Modal -->
        <div v-if="showSearchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-20" @click="showSearchModal = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4" @click.stop>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            id="search-input"
                            v-model="search"
                            type="text"
                            placeholder="Search products..."
                            class="flex-1 text-lg border-none focus:ring-0 p-0"
                        />
                        <button @click="showSearchModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div v-if="search" class="mt-4 text-sm text-gray-500">
                        Press Enter to search or type to filter results
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ title }}</h1>
                        <p class="mt-2 text-gray-600">
                            Showing {{ products.data.length }} of {{ products.total }} products
                        </p>
                    </div>
                    <div v-if="search" class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Searching for:</span>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">{{ search }}</span>
                        <button @click="search = ''" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="products.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <ProductCard
                    v-for="product in products.data"
                    :key="product.id"
                    :product="product"
                    :show-add-to-cart="true"
                />
            </div>

            <div v-else class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try browsing other categories</p>
                <div class="mt-6">
                    <Link href="/" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Back to Home
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="products.data.length > 0" class="mt-8 flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <template v-for="link in products.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                link.active
                                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                'cursor-pointer'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            :class="[
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium bg-white border-gray-300 text-gray-400',
                                'opacity-50 cursor-not-allowed'
                            ]"
                            v-html="link.label"
                        />
                    </template>
                </nav>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center text-gray-600 text-sm">
                    <p>© 2024 BeanStack. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
