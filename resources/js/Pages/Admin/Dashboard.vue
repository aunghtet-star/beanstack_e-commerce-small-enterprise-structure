<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Admin Dashboard - BeanStack" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="mt-8 text-2xl font-medium text-gray-900">
                        Admin Dashboard
                    </h1>

                    <p class="mt-6 text-gray-500 leading-relaxed">
                        Welcome to the admin panel. Here you can manage users, products, orders, and more.
                    </p>
                </div>

                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                    <div>
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            <h2 class="ml-3 text-xl font-semibold text-gray-900">
                                Users
                            </h2>
                        </div>
                        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                            Total Users: <strong>{{ stats.totalUsers }}</strong><br>
                            Admin Users: <strong>{{ stats.adminUsers }}</strong>
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <h2 class="ml-3 text-xl font-semibold text-gray-900">
                                Products
                            </h2>
                        </div>
                        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                            Total Products: <strong>{{ stats.totalProducts }}</strong><br>
                            In Stock: <strong>{{ stats.inStockProducts }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
