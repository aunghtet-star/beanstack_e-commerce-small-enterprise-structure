<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import ProductCard from '@/Components/Custom/ProductCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

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

watch(search, (value) => {
    router.get(route('products.index'), { search: value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
});

const toggleSearch = () => {
    // Search is now handled globally by the Navbar component
    // This function is kept for backward compatibility but does nothing
};
</script>

<template>
    <AppLayout :title="title">
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
    </AppLayout>
</template>
