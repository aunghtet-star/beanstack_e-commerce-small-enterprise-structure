<script setup>
import TestimonialCard from '@/Components/Custom/TestimonialCard.vue';

defineProps({
    testimonials: Array,
});
</script>

<template>
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">What Our Customers Say</h2>

            <div v-if="testimonials && testimonials.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <TestimonialCard
                    v-for="(testimonial, index) in testimonials"
                    :key="index"
                    :testimonial="testimonial"
                />
            </div>
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <TestimonialCard :testimonial="{
                    name: 'Jessica L.',
                    text: 'The quality is absolutely amazing. My new favorite coat! It\'s stylish, warm, and fits perfectly. Will be shopping here again.',
                    avatar: null
                }" />
                <TestimonialCard :testimonial="{
                    name: 'Michael B.',
                    text: 'Fast shipping and beautiful packaging. The unboxing experience felt so luxurious. The products are even better in person.',
                    avatar: null
                }" />
                <TestimonialCard :testimonial="{
                    name: 'Sarah K.',
                    text: 'I\'m in love with the minimalist design. The pieces are so versatile and easy to style. A wardrobe staple for sure!',
                    avatar: null
                }" />
            </div>
        </div>
    </section>
</template>