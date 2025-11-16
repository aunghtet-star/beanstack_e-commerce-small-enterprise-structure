<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

const props = defineProps({
  cartItems: Array,
  subtotal: Number,
  tax: Number,
  shipping: Number,
  total: Number,
  stripeKey: String,
  setupIntent: String,
});

const page = usePage();
const isSubmitting = ref(false);
const saveCard = ref(false);
let stripe = null;
let elements = null;
let card = null;

onMounted(async () => {
  stripe = await loadStripe(props.stripeKey);
  const elementsObj = stripe.elements();
  elements = elementsObj;
  card = elements.create('card');
  card.mount('#card-element');
});

const formatted = (n) => `$${Number(n).toFixed(2)}`;

const submit = async () => {
  if (!stripe || !elements) return;
  isSubmitting.value = true;
  try {
    let paymentMethodId = null;
    if (saveCard.value) {
      const { setupIntent, error } = await stripe.confirmCardSetup(props.setupIntent, {
        payment_method: { card },
      });
      if (error) throw error;
      paymentMethodId = setupIntent.payment_method;
    } else {
      const { paymentMethod, error } = await stripe.createPaymentMethod({ type: 'card', card });
      if (error) throw error;
      paymentMethodId = paymentMethod.id;
    }

    await router.post(route('checkout.pay'), { payment_method: paymentMethodId, save_card: saveCard.value }, { preserveScroll: true });
  } catch (e) {
    console.error(e);
    alert('Payment failed. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Head title="Checkout" />

    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <Link href="/" class="flex items-center space-x-2">
              <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
              <span class="text-xl font-bold text-gray-900">BeanStack</span>
            </Link>
          </div>
          <div>
            <Link :href="route('cart.index')" class="text-sm text-indigo-600 hover:text-indigo-700">Back to Cart</Link>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Form -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment</h2>
          <div id="card-element" class="p-3 border border-gray-300 rounded-md"></div>
          <label class="mt-4 flex items-center gap-3 text-sm text-gray-700">
            <input type="checkbox" v-model="saveCard" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            Save card for future payments
          </label>
          <button
            @click="submit"
            :disabled="isSubmitting"
            class="mt-6 w-full bg-indigo-600 text-white py-3 px-4 rounded-md font-medium hover:bg-indigo-700 disabled:bg-gray-300"
          >
            <span v-if="isSubmitting">Processing...</span>
            <span v-else>Pay {{ formatted(total) }}</span>
          </button>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium">{{ formatted(subtotal) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span class="font-medium">{{ shipping === 0 ? 'FREE' : formatted(shipping) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Tax (10%)</span><span class="font-medium">{{ formatted(tax) }}</span></div>
            <div class="border-t pt-3 flex justify-between text-base">
              <span class="font-medium text-gray-900">Total</span>
              <span class="text-xl font-bold text-gray-900">{{ formatted(total) }}</span>
            </div>
          </div>
          <ul class="mt-6 divide-y">
            <li v-for="item in cartItems" :key="item.id" class="py-3 flex justify-between text-sm">
              <span class="text-gray-700">{{ item.product.name }} × {{ item.quantity }}</span>
              <span class="font-medium">${{ (item.product.price * item.quantity).toFixed(2) }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
