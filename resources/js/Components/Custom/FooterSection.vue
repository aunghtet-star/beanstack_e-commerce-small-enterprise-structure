<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
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
</template>