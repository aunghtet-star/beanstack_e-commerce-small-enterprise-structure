<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="Shopping Cart" />

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
                        <Link v-if="isAuthenticated" :href="route('wishlist.index')" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ wishlistCount }}</span>
                        </Link>

                        <!-- Shopping Cart -->
                        <Link :href="route('cart.index')" class="text-indigo-600 hover:text-indigo-700 relative">
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
                    <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
                    <p class="mt-2 text-sm text-gray-600">{{ cartItems.length }} items in your cart</p>
                </div>

                <div v-if="cartItems.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-sm text-gray-500">Start shopping to add items to your cart</p>
                    <div class="mt-6">
                        <Link :href="route('products.index')" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Continue Shopping
                        </Link>
                    </div>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <div v-for="item in cartItems" :key="item.id" class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <Link :href="route('products.show', item.product.id)" class="flex-shrink-0">
                                    <img :src="item.product.image_url" :alt="item.product.name" class="h-24 w-24 object-cover rounded-lg">
                                </Link>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <Link :href="route('products.show', item.product.id)" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                        {{ item.product.name }}
                                    </Link>
                                    <p class="mt-1 text-sm text-gray-500">{{ item.product.category }}</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">${{ item.product.price }}</p>
                                    <p v-if="item.product.stock_quantity < 10" class="mt-1 text-sm text-red-600">
                                        Only {{ item.product.stock_quantity }} left in stock
                                    </p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-3">
                                    <button
                                        @click="updateQuantity(item, item.quantity - 1)"
                                        :disabled="item.quantity <= 1 || updating === item.id"
                                        class="p-1 rounded-full hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <span class="text-gray-900 font-medium w-8 text-center">{{ item.quantity }}</span>
                                    <button
                                        @click="updateQuantity(item, item.quantity + 1)"
                                        :disabled="item.quantity >= item.product.stock_quantity || updating === item.id"
                                        class="p-1 rounded-full hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Item Total -->
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        ${{ (item.product.price * item.quantity).toFixed(2) }}
                                    </p>
                                </div>

                                <!-- Remove Button -->
                                <button
                                    @click="removeItem(item.id)"
                                    :disabled="removing === item.id"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-full disabled:opacity-50"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ subtotal.toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900">
                                        {{ subtotal >= 100 ? 'FREE' : '$10.00' }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax (10%)</span>
                                    <span class="font-medium text-gray-900">${{ tax.toFixed(2) }}</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-base font-medium text-gray-900">Total</span>
                                        <span class="text-xl font-bold text-gray-900">${{ total.toFixed(2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <Link
                                :href="route('checkout.index')"
                                class="w-full inline-flex items-center justify-center bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-colors"
                            >
                                Proceed to Checkout
                            </Link>

                            <Link :href="route('products.index')" class="block text-center text-sm text-indigo-600 hover:text-indigo-700 mt-4">
                                Continue Shopping
                            </Link>

                            <div v-if="subtotal < 100" class="mt-4 p-3 bg-green-50 rounded-lg">
                                <p class="text-sm text-green-800">
                                    Add ${{ (100 - subtotal).toFixed(2) }} more to get FREE shipping!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    cartItems: Array,
});

const page = usePage();
const isAuthenticated = computed(() => page.props.auth?.user != null);
const cartCount = computed(() => page.props.cartCount || 0);
const wishlistCount = computed(() => page.props.wishlistCount || 0);

const updating = ref(null);
const removing = ref(null);

const subtotal = computed(() => {
    return props.cartItems.reduce((total, item) => {
        return total + (item.product.price * item.quantity);
    }, 0);
});

const shipping = computed(() => {
    return subtotal.value >= 100 ? 0 : 10;
});

const tax = computed(() => {
    return subtotal.value * 0.1;
});

const total = computed(() => {
    return subtotal.value + shipping.value + tax.value;
});

const updateQuantity = (item, newQuantity) => {
    if (newQuantity < 1 || newQuantity > item.product.stock_quantity) return;
    
    updating.value = item.id;
    
    router.put(route('cart.update', item.id), {
        quantity: newQuantity,
    }, {
        preserveScroll: true,
        onFinish: () => {
            updating.value = null;
        },
    });
};

const removeItem = (itemId) => {
    if (!confirm('Remove this item from cart?')) return;
    
    removing.value = itemId;
    
    router.delete(route('cart.destroy', itemId), {
        preserveScroll: true,
        onFinish: () => {
            removing.value = null;
        },
    });
};
</script>
