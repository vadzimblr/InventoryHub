<template>
    <div class="w-full h-full ">
        <header class="header">
            <nav class="navbar">
                <button @click="currentView = 'PendingOrders'" :class="{ active: currentView === 'PendingOrders' }">
                    Размещенные товары
                </button>
                <button @click="currentView = 'CheckProductStock'" :class="{ active: currentView === 'CheckProductStock' }">
                    Проверить наличие на складе
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
            <component
                :is="currentView"
                class="content"
                :from-department="fromDepartment"
            />
        </main>
    </div>
</template>

<script>
import Notifications from "../Components/Notifications.vue";
import "@/css/procurement-manager.css";
import {useToast} from "vue-toastification";
import PendingOrders from "../Components/AccountManager/PendingOrders.vue";
import CheckProductStock from "../Components/AccountManager/CheckProductStock.vue";


export default {
    components: {
        PendingOrders,
        CheckProductStock,
        Notifications,
    },
    setup() {
        const toast = useToast();
        return { toast };
    },
    data() {
        return {
            currentView: "PendingOrders",
            fromDepartment: ""
        };
    },
    watch: {
        currentView(newView){
            if(newView === "Notifications"){
                this.fromDepartment = "account-manager"
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
