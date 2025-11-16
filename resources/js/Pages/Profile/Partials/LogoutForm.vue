<script setup>
import { router } from '@inertiajs/vue3';

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Account Actions
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Manage your account security and access.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <!-- Logout Button -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-2">Sign Out</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Sign out of your account. You will be redirected to the login page.
                </p>
                <button
                    @click="logout"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Sign Out
                </button>
            </div>
        </div>
    </section>
</template>