<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true,
    },
    showAddToCart: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="group relative bg-white rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <Link :href="`/products/${product.id}`" class="block">
            <!-- Product Image -->
            <div class="aspect-square overflow-hidden bg-gray-100">
                <img
                    v-if="product.meta?.image_url"
                    :src="product.meta.image_url"
                    :alt="product.name"
                    class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                />
                <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-1 truncate">
                    {{ product.name }}
                </h3>
                <p class="text-lg font-semibold text-gray-900">
                    ${{ Number(product.price).toFixed(2) }}
                </p>
                
                <!-- Stock Badge -->
                <div v-if="product.stock <= 0" class="mt-2">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded">
                        Out of Stock
                    </span>
                </div>
                <div v-else-if="product.stock <= 10" class="mt-2">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded">
                        Low Stock
                    </span>
                </div>
            </div>
        </Link>

        <!-- Add to Cart Button (optional) -->
        <div v-if="showAddToCart" class="p-4 pt-0">
            <button
                :disabled="product.stock <= 0"
                class="w-full bg-gray-900 text-white py-2 px-4 rounded-md hover:bg-gray-800 transition-colors duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
                {{ product.stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
        </div>
    </div>
</template>
