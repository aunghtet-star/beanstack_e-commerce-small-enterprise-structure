<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();

const isAuthenticated = computed(() => page.props.auth?.user != null);
const cartCount = computed(() => page.props.cartCount || 0);
const wishlistCount = computed(() => page.props.wishlistCount || 0);

const showSearchModal = ref(false);
const searchQuery = ref('');

const toggleSearch = () => {
    showSearchModal.value = !showSearchModal.value;
    if (showSearchModal.value) {
        setTimeout(() => {
            document.getElementById('navbar-search-input')?.focus();
        }, 100);
    }
};

const performSearch = () => {
    if (searchQuery.value.trim()) {
        const searchUrl = `/products?search=${encodeURIComponent(searchQuery.value.trim())}`;
        router.visit(searchUrl);
        showSearchModal.value = false;
        searchQuery.value = '';
    }
};

const handleKeyPress = (event) => {
    if (event.key === 'Enter') {
        performSearch();
    } else if (event.key === 'Escape') {
        showSearchModal.value = false;
        searchQuery.value = '';
    }
};
</script>

<template>
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
                    <!-- Search -->
                    <button @click="toggleSearch" class="text-gray-600 hover:text-gray-900 p-2 rounded-md hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- User Account -->
                    <Link v-if="!isAuthenticated" :href="route('login')" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </Link>

                    <!-- Wishlist -->
                    <Link v-if="isAuthenticated" :href="route('wishlist.index')" class="text-gray-600 hover:text-gray-900 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ wishlistCount }}</span>
                    </Link>

                    <!-- Shopping Cart -->
                    <Link :href="route('cart.index')" class="text-gray-600 hover:text-gray-900 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ cartCount }}</span>
                    </Link>

                    <Link v-if="$page.props.auth?.user" :href="route('profile.show')" :active="route().current('profile.show')">
                        Profile
                    </Link>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Modal -->
    <div v-if="showSearchModal" class="fixed inset-0 bg-black bg-opacity-50 z-[60] flex items-start justify-center pt-20" @click="showSearchModal = false">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4" @click.stop>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        id="navbar-search-input"
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search products..."
                        class="flex-1 text-lg border-none focus:ring-0 p-0"
                        @keydown="handleKeyPress"
                    />
                    <button @click="performSearch" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Search
                    </button>
                    <button @click="showSearchModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    Press Enter to search or click Search button
                </div>
            </div>
        </div>
    </div>
</template>