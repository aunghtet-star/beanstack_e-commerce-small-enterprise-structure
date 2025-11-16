<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="My Wishlist" />

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
                        <!-- Search -->
                        <Link href="/products" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </Link>

                        <!-- User Account -->
                        <Link v-if="!isAuthenticated" :href="route('login')" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </Link>

                        <!-- Wishlist -->
                        <Link v-if="isAuthenticated" :href="route('wishlist.index')" class="text-red-600 hover:text-red-700 relative">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
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
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
                    <p class="mt-2 text-sm text-gray-600">{{ wishlistItems.length }} items saved</p>
                </div>

                <div v-if="wishlistItems.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Your wishlist is empty</h3>
                    <p class="mt-2 text-sm text-gray-500">Save your favorite items for later</p>
                    <div class="mt-6">
                        <Link :href="route('products.index')" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Browse Products
                        </Link>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="item in wishlistItems" :key="item.id" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group relative">
                        <!-- Remove Button -->
                        <button
                            @click="removeFromWishlist(item.id)"
                            :disabled="removing === item.id"
                            class="absolute top-2 right-2 z-10 p-2 bg-white rounded-full shadow-sm hover:bg-red-50 transition-colors disabled:opacity-50"
                        >
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <!-- Product Image -->
                        <Link :href="route('products.show', item.product.id)">
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200">
                                <img 
                                    :src="item.product.image_url" 
                                    :alt="item.product.name"
                                    class="h-64 w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                >
                            </div>
                        </Link>

                        <!-- Product Info -->
                        <div class="p-4">
                            <Link :href="route('products.show', item.product.id)">
                                <h3 class="text-sm font-medium text-gray-900 hover:text-indigo-600 line-clamp-2">
                                    {{ item.product.name }}
                                </h3>
                            </Link>
                            <p class="mt-1 text-xs text-gray-500 uppercase">{{ item.product.category }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-lg font-bold text-gray-900">${{ item.product.price }}</p>
                                <span v-if="item.product.stock_quantity > 0" class="text-xs text-green-600">In Stock</span>
                                <span v-else class="text-xs text-red-600">Out of Stock</span>
                            </div>

                            <!-- Add to Cart Button -->
                            <button
                                @click="addToCart(item.product)"
                                :disabled="item.product.stock_quantity === 0 || addingToCart === item.product.id"
                                class="mt-4 w-full bg-indigo-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed text-sm"
                            >
                                <span v-if="addingToCart === item.product.id">Adding...</span>
                                <span v-else-if="item.product.stock_quantity === 0">Out of Stock</span>
                                <span v-else>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    wishlistItems: Array,
});
const page = usePage();
const isAuthenticated = computed(() => page.props.auth?.user != null);
const cartCount = computed(() => page.props.cartCount || 0);
const wishlistCount = computed(() => page.props.wishlistCount || 0);


const removing = ref(null);
const addingToCart = ref(null);

const removeFromWishlist = (itemId) => {
    if (!confirm('Remove this item from your wishlist?')) return;
    
    removing.value = itemId;
    
    router.delete(route('wishlist.destroy', itemId), {
        preserveScroll: true,
        onFinish: () => {
            removing.value = null;
        },
    });
};

const addToCart = (product) => {
    if (product.stock_quantity === 0) return;
    
    addingToCart.value = product.id;
    
    router.post(route('cart.store'), {
        product_id: product.id,
        quantity: 1,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show success message
        },
        onFinish: () => {
            addingToCart.value = null;
        },
    });
};
</script>
