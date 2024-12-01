<template>
    <div class="w-full h-full ">
        <header class="header">
            <nav class="navbar">
                <button @click="currentView = 'SupplierManagement'" :class="{ active: currentView === 'SupplierManagement' }">
                    Поставщики
                </button>
                <button @click="currentView = 'CreateProduct'" :class="{ active: currentView === 'CreateProduct' }">
                    Товары
                </button>
                <button @click="currentView = 'CreateOrder'" :class="{ active: currentView === 'CreateOrder' }">
                    Поставки
                </button>
                <button @click="currentView = 'Notifications'" :class="{ active: currentView === 'Notifications' }">
                    Уведомления
                </button>
                <button @click="logout">
                    Выйти
                </button>
            </nav>
        </header>

        <main class="main">
            <component :is="currentView" :from-department="fromDepartment" class="content" />
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
import axios from "axios";
import {onBeforeMount} from "vue";

const checkUserRole = async () => {
    const token = localStorage.getItem('authToken');
    if (token) {
        try {
            const response = await axios.get("/api/user/role", {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            });
            if (response.data.role !== 'admin' && response.data.role !== 'procurement-manager') {
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
            fromDepartment: ""
        };
    },
    watch: {
        currentView(newView){
            if(newView === "Notifications"){
                this.fromDepartment = "procurement-manager"
            }
        }
    },
    methods: {
        logout() {
            localStorage.removeItem("authToken");
            window.location.href = "/login";
        }
    }

};
</script>

<style>


</style>
