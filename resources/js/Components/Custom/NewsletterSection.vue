<script setup>
import { ref } from 'vue';

const email = ref('');

const subscribe = () => {
    // Handle newsletter subscription
    console.log('Subscribing:', email.value);
    email.value = '';
};
</script>

<template>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Join Our Newsletter</h2>
                <p class="text-gray-600 mb-6">Get exclusive access to new arrivals, special offers, and more.</p>

                <form @submit.prevent="subscribe" class="flex gap-3">
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Enter your email"
                        required
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent"
                    />
                    <button
                        type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-semibold px-6 py-3 rounded-md transition-colors duration-200"
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>