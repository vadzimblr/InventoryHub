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
                <button @click="currentTab = 'notifications'" :class="{ active: currentTab === 'notifications' }">
                    Уведомления
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
            <div v-if="currentTab === 'notifications'" class="content">
                <Notifications :fromDepartment="department" />
            </div>
        </main>
    </div>
</template>

<script setup>
import {onBeforeMount, ref} from "vue";
import SupplierOrders from "../Components/Storekeeper/SupplierOrders.vue";
import Waybills from "../Components/Storekeeper/Waybills.vue";
import Notifications from "../Components/Notifications.vue";
import axios from "axios";

const currentTab = ref("supplierOrders");
const department = ref("storekeeper");  // Это ваш департамент
const logout = () => {
    localStorage.removeItem("authToken");
    window.location.href = "/login";
};
const checkUserRole = async () => {
    const token = localStorage.getItem('authToken');
    if (token) {
        try {
            const response = await axios.get("/api/user/role", {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            });
            if (response.data.role !== 'admin' && response.data.role !== 'storekeeper') {
                document.title = 'Unauthenticated';
                window.location.href = '/login'
            }
        } catch (error) {
            console.error("Error fetching user role:", error);
            window.location.href = '/login'
        }
    } else {
        console.error('No token found, please log in again.');
        document.title = 'Unauthenticated';
        window.location.href = '/login'
    }
};

onBeforeMount(() => {
    checkUserRole();
});
</script>

<style scoped>
/* Ваши стили */
</style>
