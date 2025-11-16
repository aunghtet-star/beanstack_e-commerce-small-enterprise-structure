<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const quantity = ref(1);

const incrementQuantity = () => {
    if (quantity.value < props.product.stock) {
        quantity.value++;
    }
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const addToCart = () => {
    // TODO: Implement add to cart functionality
    console.log('Adding to cart:', { product: props.product, quantity: quantity.value });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="product.name" />

        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">Moderno</span>
                        </Link>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <Link href="/products" class="text-gray-700 hover:text-gray-900 font-medium">Shop</Link>
                        <Link href="/category/men" class="text-gray-700 hover:text-gray-900 font-medium">Men</Link>
                        <Link href="/category/women" class="text-gray-700 hover:text-gray-900 font-medium">Women</Link>
                    </div>

                    <div class="flex items-center space-x-6">
                        <Link :href="route('login')" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </Link>
                        <button class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li>
                            <Link href="/" class="text-gray-500 hover:text-gray-700">Home</Link>
                        </li>
                        <li><span class="text-gray-400">/</span></li>
                        <li>
                            <Link href="/products" class="text-gray-500 hover:text-gray-700">Products</Link>
                        </li>
                        <li><span class="text-gray-400">/</span></li>
                        <li class="text-gray-900">{{ product.name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Product Detail -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Product Image -->
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <img
                        v-if="product.meta?.image_url"
                        :src="product.meta.image_url"
                        :alt="product.name"
                        class="w-full h-full object-cover object-center"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                    
                    <div class="mb-6">
                        <p class="text-3xl font-bold text-gray-900">${{ Number(product.price).toFixed(2) }}</p>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        <span v-if="product.stock > 10" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            In Stock ({{ product.stock }} available)
                        </span>
                        <span v-else-if="product.stock > 0" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Low Stock ({{ product.stock }} left)
                        </span>
                        <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Out of Stock
                        </span>
                    </div>

                    <!-- Product Meta -->
                    <div v-if="product.meta" class="mb-6 space-y-2">
                        <p v-if="product.meta.color" class="text-gray-600">
                            <span class="font-medium">Color:</span> {{ product.meta.color }}
                        </p>
                        <p v-if="product.meta.weight" class="text-gray-600">
                            <span class="font-medium">Weight:</span> {{ product.meta.weight }}g
                        </p>
                    </div>

                    <!-- Quantity Selector -->
                    <div v-if="product.stock > 0" class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center space-x-3">
                            <button
                                @click="decrementQuantity"
                                class="p-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                :disabled="quantity <= 1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </button>
                            <span class="text-lg font-medium w-12 text-center">{{ quantity }}</span>
                            <button
                                @click="incrementQuantity"
                                class="p-2 border border-gray-300 rounded-md hover:bg-gray-50"
                                :disabled="quantity >= product.stock"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="flex space-x-4 mb-6">
                        <button
                            @click="addToCart"
                            :disabled="product.stock <= 0"
                            class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ product.stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </button>
                        <button class="p-3 border border-gray-300 rounded-md hover:bg-gray-50">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Description -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Product Details</h3>
                        <p class="text-gray-600">
                            This is a premium quality product from our collection. 
                            Made with attention to detail and designed for modern individuals.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div v-if="relatedProducts.length > 0" class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">You May Also Like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="relatedProduct in relatedProducts"
                        :key="relatedProduct.id"
                        :product="relatedProduct"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
