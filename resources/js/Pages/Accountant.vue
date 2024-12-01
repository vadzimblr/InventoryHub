<script setup>
import {ref, onMounted, onBeforeMount} from "vue";
import ClientInvoices from "../Components/Accountant/ClientInvoices.vue";
import SupplierOrders from "../Components/Accountant/SupplierOrders.vue";
import Notifications from "../Components/Notifications.vue";
import axios from "axios";
import { useRouter } from "vue-router";

const currentTab = ref("clientInvoices");
const department = ref("accountant");
const router = useRouter();

const checkUserRole = async () => {
    const token = localStorage.getItem('authToken');
    if (token) {
        try {
            const response = await axios.get("/api/user/role", {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            });
            if (response.data.role !== 'admin' && response.data.role !== 'accountant') {
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

const logout = () => {
    localStorage.removeItem("authToken");
    router.push("/login");
};
</script>

<template>
    <div class="w-full h-full">
        <header class="header">
            <nav class="navbar">
                <button @click="currentTab = 'clientInvoices'" :class="{ active: currentTab === 'clientInvoices' }">
                    Счета клиентам
                </button>
                <button @click="currentTab = 'supplierOrders'" :class="{ active: currentTab === 'supplierOrders' }">
                    Счета поставщикам
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
            <div v-if="currentTab === 'clientInvoices'" class="content">
                <ClientInvoices />
            </div>
            <div v-if="currentTab === 'supplierOrders'" class="content">
                <SupplierOrders />
            </div>
            <div v-if="currentTab === 'notifications'" class="content">
                <Notifications :fromDepartment="department" />
            </div>
        </main>
    </div>
</template>
