<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="relative bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="relative z-10 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Elevate Your Everyday Style
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Discover our new collection of timeless pieces, crafted for the modern individual.
                </p>
                <Link
                    href="/products"
                    class="inline-block bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-semibold px-8 py-3 rounded-md transition-colors duration-200"
                >
                    Shop The Collection
                </Link>
            </div>
        </div>
        <!-- Background Image Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/50"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1600')"></div>
    </section>
</template>