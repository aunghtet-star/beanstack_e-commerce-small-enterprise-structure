<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Shop by Category</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Jackets Category -->
                <Link href="/category/jackets" class="group relative overflow-hidden rounded-lg aspect-[4/3] bg-gray-900">
                    <img
                        src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800"
                        alt="Jackets"
                        class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white">Jackets</h3>
                </Link>

                <!-- Footwear Category -->
                <Link href="/category/footwear" class="group relative overflow-hidden rounded-lg aspect-[4/3] bg-gray-900">
                    <img
                        src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800"
                        alt="Footwear"
                        class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white">Footwear</h3>
                </Link>

                <!-- Accessories Category -->
                <Link href="/category/accessories" class="group relative overflow-hidden rounded-lg aspect-[4/3] bg-gray-900">
                    <img
                        src="https://images.unsplash.com/photo-1622434641406-a158123450f9?w=800"
                        alt="Accessories"
                        class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-300"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <h3 class="absolute bottom-6 left-6 text-2xl font-bold text-white">Accessories</h3>
                </Link>
            </div>
        </div>
    </section>
</template>