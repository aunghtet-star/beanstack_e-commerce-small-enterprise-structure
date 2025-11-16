<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const page = usePage();
const showToast = ref(!!page.props.flash?.success);
const toastMessage = page.props.flash?.success || 'Payment successful';

onMounted(() => {
  if (showToast.value) {
    setTimeout(() => (showToast.value = false), 3000);
  }
});
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <Head title="Order Successful" />
    <div v-if="showToast" class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
      <div class="bg-green-600 text-white px-4 py-2 rounded shadow">
        {{ toastMessage }}
      </div>
    </div>
    <div class="bg-white rounded-lg shadow p-8 max-w-md w-full text-center">
      <svg class="mx-auto w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h1 class="mt-4 text-2xl font-bold text-gray-900">Thank you for your purchase!</h1>
      <p class="mt-2 text-gray-600">Your payment was successful. A receipt has been sent to your email.</p>
      <div class="mt-6 flex gap-3 justify-center">
        <Link href="/" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Continue Shopping</Link>
        <Link :href="route('cart.index')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">View Cart</Link>
      </div>
    </div>
  </div>
</template>
