<template>
    <div class="w-full h-full">
        <!-- Шапка -->
        <header class="header">
            <nav class="navbar">
                <button @click="currentTab = 'supplierOrders'" :class="{ active: currentTab === 'supplierOrders' }">
                    Поступления на склад
                </button>
                <button @click="currentTab = 'waybills'" :class="{ active: currentTab === 'waybills' }">
                    Отгрузка и накладные
                </button>
                <button @click="logout">
                    Выйти
                </button>
            </nav>
        </header>

        <main class="main">
            <div v-if="currentTab === 'supplierOrders'" class="content">
                <SupplierOrders />
            </div>
            <div v-if="currentTab === 'waybills'" class="content">
                <Waybills />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from "vue";
import SupplierOrders from "../Components/Storekeeper/SupplierOrders.vue";
import Waybills from "../Components/Storekeeper/Waybills.vue";

const currentTab = ref("supplierOrders");

const logout = () => {
    localStorage.removeItem("authToken");
    window.location.href = "/login";
};
</script>

<style scoped>
.header {
    background-color: #334155;
    padding: 10px 0;
    width: 100%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.navbar {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 0 20px;
    margin: 0 auto;
}

button {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    color: #e2e8f0;
    background-color: #475569;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
}

button.active {
    background-color: #16a34a;
    color: #ffffff;
}

button:hover {
    background-color: #22c55e;
    color: #ffffff;
}


.main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 20px;
}

.content {
    background-color: #f1f5f9;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    width: 100%;
    max-width: 800px;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
