<template>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-center mb-6">Login</h1>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    v-model="email"
                    id="email"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    v-model="password"
                    id="password"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                />
            </div>

            <div class="flex justify-center">
                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    :disabled="isSubmitting"
                >
                    Login
                </button>
            </div>
        </form>

        <p v-if="error" class="text-center text-red-500 mt-4">{{ error }}</p>
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="/register" class="text-blue-500 hover:underline">Register here</a>.
        </p>
    </div>
</template>

<script>
import {setAuthorizationToken} from "../app.js";
import axios from "axios";

export default {
    data() {
        return {
            email: "",
            password: "",
            remember: false,
            error: null,
            isSubmitting: false,
        };
    },
    methods: {
        async submitForm() {
            this.isSubmitting = true;
            this.error = null;

            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });

                const token = response.data.token;
                console.log('Token:', token);

                setAuthorizationToken(token);

                window.location.href = '/products'

            } catch (err) {
                this.error = err.response?.data?.message || 'An error occurred.';
            } finally {
                this.isSubmitting = false;
            }
        },
    },
};
</script>

<style scoped>

</style>
