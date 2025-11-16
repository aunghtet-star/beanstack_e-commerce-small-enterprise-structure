<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Custom/Navbar.vue';
import HeroSection from '@/Components/Custom/HeroSection.vue';
import TrendingSection from '@/Components/Custom/TrendingSection.vue';
import CategorySection from '@/Components/Custom/CategorySection.vue';
import TestimonialsSection from '@/Components/Custom/TestimonialsSection.vue';
import NewsletterSection from '@/Components/Custom/NewsletterSection.vue';
import FooterSection from '@/Components/Custom/FooterSection.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    trendingProducts: Array,
    categories: Array,
    testimonials: Array,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="BeanStack - Elevate Your Everyday Style" />

        <!-- Navigation -->
        <Navbar />

        <!-- Hero Section -->
        <HeroSection />

        <!-- Trending Now Section -->
        <TrendingSection :trending-products="trendingProducts" />

        <!-- Shop by Category Section -->
        <CategorySection />

        <!-- Testimonials Section -->
        <TestimonialsSection :testimonials="testimonials" />

        <!-- Newsletter Section -->
        <NewsletterSection />

        <!-- Footer -->
        <FooterSection />
    </div>
</template>
