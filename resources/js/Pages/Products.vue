<template>
    <div class="w-full h-full ">
        <header class="header">
            <nav class="navbar">
                <button @click="currentView = 'AllProducts'" :class="{ active: currentView === 'AllProducts' }">
                    Продукты
                </button>
                <button @click="currentView = 'Order'" :class="{ active: currentView === 'Order' }">
                    Заказы
                </button>
                <button @click="currentView = 'Notifications'" :class="{ active: currentView === 'Notifications' }">
                    Выйти
                </button>
            </nav>
        </header>

        <main class="main">
            <component :is="currentView" class="content" />
        </main>
    </div>
</template>

<script>


import AllProducts from "../Components/ProductComponents/AllProducts.vue";
import Order from "../Components/ProductComponents/Order.vue";

export default {
    components: {
        AllProducts,
        Order
    },
    data() {
        return {
            currentView: "AllProducts",
        };
    },
    props: {
        user: Object,
    },
    mounted() {
        if(localStorage.getItem('authToken') == null){
            return window.location.href = '/login'
        }
        console.log('User data:', this.user);
    },
};
</script>

<style scoped>
</style>
