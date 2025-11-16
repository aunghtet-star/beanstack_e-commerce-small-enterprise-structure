<script setup>
defineProps({
    testimonial: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Star Rating -->
        <div class="flex justify-center mb-4">
            <div class="flex space-x-1">
                <svg v-for="i in 5" :key="i" class="w-5 h-5 text-purple-500 fill-current" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                </svg>
            </div>
        </div>

        <!-- Testimonial Text -->
        <p class="text-gray-600 text-center mb-6 italic">
            "{{ testimonial.text }}"
        </p>

        <!-- Customer Info -->
        <div class="flex items-center justify-center">
            <img
                v-if="testimonial.avatar"
                :src="testimonial.avatar"
                :alt="testimonial.name"
                class="w-10 h-10 rounded-full mr-3"
            />
            <div v-else class="w-10 h-10 rounded-full bg-gray-300 mr-3 flex items-center justify-center text-gray-600 font-semibold">
                {{ testimonial.name.charAt(0) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ testimonial.name }}</p>
            </div>
        </div>
    </div>
</template>
