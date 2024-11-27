<template>
    <div class="w-full h-full ">
        <header class="header">
            <nav class="navbar">
                <button @click="currentView = 'SupplierManagement'" :class="{ active: currentView === 'SupplierManagement' }">
                    Поставщики
                </button>
                <button @click="currentView = 'CreateProduct'" :class="{ active: currentView === 'CreateProduct' }">
                    Добавить товар
                </button>
                <button @click="currentView = 'CreateOrder'" :class="{ active: currentView === 'CreateOrder' }">
                    Разместить заказ
                </button>
                <button @click="currentView = 'Notifications'" :class="{ active: currentView === 'Notifications' }">
                    Уведомления
                </button>
            </nav>
        </header>

        <main class="main">
            <component :is="currentView" class="content" />
        </main>
    </div>
</template>

<script>
import SupplierManagement from "../Components/ProductManager/ManageSuppliers.vue";
import CreateProduct from "../Components/ProductManager/CRUD/CreateProduct.vue";
import CreateOrder from "../Components/ProductManager/CRUD/CreateSupplierOrder.vue";
import Notifications from "../Components/Notifications.vue";
import "@/css/procurement-manager.css";
import {useToast} from "vue-toastification";


export default {
    components: {
        SupplierManagement,
        CreateProduct,
        CreateOrder,
        Notifications,
    },
    setup() {
        const toast = useToast();
        return { toast };
    },
    data() {
        return {
            currentView: "SupplierManagement",
        };
    },
};
</script>

<style>


</style>
