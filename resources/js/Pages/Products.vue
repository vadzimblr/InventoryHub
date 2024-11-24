<template>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold mb-6">Products</h1>

        <div v-if="user" class="mb-6">
            <h2 class="text-xl">Hello, {{ user.name }}</h2>
            <p>Your email: {{ user.email }}</p>
        </div>

        <!-- Здесь будет список товаров -->
        <div>
            <!-- Пример вывода списка товаров -->
            <p>Товары для вас:</p>
            <!-- Сюда можно вставить логику для вывода товаров -->
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: Object,  // Пропс для получения данных пользователя
    },
    mounted() {
        if(localStorage.getItem('authToken') == null){
            return window.location.href = '/login'
        }
        console.log('User data:', this.user);  // Для тестирования, чтобы увидеть данные
    },
};
</script>

<style scoped>
/* Добавьте свои стили */
</style>
