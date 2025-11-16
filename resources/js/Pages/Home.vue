<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ProductCard from '@/Components/ProductCard.vue';
import TestimonialCard from '@/Components/TestimonialCard.vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    trendingProducts: Array,
    categories: Array,
    testimonials: Array,
});

const page = usePage();
const email = ref('');

const isAuthenticated = computed(() => page.props.auth?.user != null);
const cartCount = computed(() => page.props.cartCount || 0);
const wishlistCount = computed(() => page.props.wishlistCount || 0);

const subscribe = () => {
    // Handle newsletter subscription
    console.log('Subscribing:', email.value);
    email.value = '';
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head title="BeanStack - Elevate Your Everyday Style" />

        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-2">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span class="text-xl font-bold text-gray-900">BeanStack</span>
                        </Link>
                    </div>

                    <!-- Center Navigation -->
                    <div class="hidden md:flex space-x-8">
                        <Link href="/products" class="text-gray-700 hover:text-gray-900 font-medium">Shop</Link>
                        <Link href="/category/men" class="text-gray-700 hover:text-gray-900 font-medium">Men</Link>
                        <Link href="/category/women" class="text-gray-700 hover:text-gray-900 font-medium">Women</Link>
                        <Link href="/category/new-arrivals" class="text-gray-700 hover:text-gray-900 font-medium">New Arrivals</Link>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-6">
                        <!-- Search -->
                        <button class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>

                        <!-- User Account -->
                        <Link v-if="!isAuthenticated" :href="route('login')" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </Link>

                        <!-- Wishlist -->
                        <Link v-if="isAuthenticated" :href="route('wishlist.index')" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ wishlistCount }}</span>
                        </Link>

                        <!-- Shopping Cart -->
                        <Link :href="route('cart.index')" class="text-gray-600 hover:text-gray-900 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] leading-none px-1.5 py-0.5 rounded-full">{{ cartCount }}</span>
                        </Link>

                        <Link v-if="$page.props.auth?.user" :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profile
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
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

        <!-- Trending Now Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Trending Now</h2>
                
                <div v-if="trendingProducts && trendingProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="product in trendingProducts"
                        :key="product.id"
                        :product="product"
                    />
                </div>
                <div v-else class="text-center py-12">
                    <p class="text-gray-500">No trending products available at the moment.</p>
                </div>
            </div>
        </section>

        <!-- Shop by Category Section -->
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

        <!-- Testimonials Section -->
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

        <!-- Newsletter Section -->
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

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Customer Service -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4">Customer Service</h3>
                        <ul class="space-y-2">
                            <li><Link href="/contact" class="text-gray-600 hover:text-gray-900">Contact Us</Link></li>
                            <li><Link href="/shipping" class="text-gray-600 hover:text-gray-900">Shipping & Returns</Link></li>
                            <li><Link href="/faq" class="text-gray-600 hover:text-gray-900">FAQ</Link></li>
                            <li><Link href="/size-guide" class="text-gray-600 hover:text-gray-900">Size Guide</Link></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4">Company</h3>
                        <ul class="space-y-2">
                            <li><Link href="/about" class="text-gray-600 hover:text-gray-900">About Us</Link></li>
                            <li><Link href="/careers" class="text-gray-600 hover:text-gray-900">Careers</Link></li>
                            <li><Link href="/press" class="text-gray-600 hover:text-gray-900">Press</Link></li>
                            <li><Link href="/affiliates" class="text-gray-600 hover:text-gray-900">Affiliates</Link></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4">Legal</h3>
                        <ul class="space-y-2">
                            <li><Link href="/terms" class="text-gray-600 hover:text-gray-900">Terms of Service</Link></li>
                            <li><Link href="/privacy" class="text-gray-600 hover:text-gray-900">Privacy Policy</Link></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-600 text-sm">
                    <p>© 2024 BeanStack. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
