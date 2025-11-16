<script setup>
import ProductCard from '@/Components/Custom/ProductCard.vue';

defineProps({
    trendingProducts: Array,
});
</script>

<template>
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Trending Now</h2>

            <div v-if="trendingProducts && trendingProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <ProductCard
                    v-for="product in trendingProducts"
                    :key="product.id"
                    :product="product"
                />
            </div>
            <div v-else class="text-center py-12">
                <p class="text-gray-500">No trending products available at the moment.</p>
            </div>
        </div>
    </section>
</template>