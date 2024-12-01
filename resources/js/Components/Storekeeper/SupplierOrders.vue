<template>
    <div class="supplier-orders">
        <h2>Поступления на склад</h2>
        <!-- Кнопка обновления -->
        <div class="refresh-container">
            <button @click="fetchSupplierOrders" :disabled="loading" class="refresh-button">
                {{ loading ? "Обновление..." : "Обновить список" }}
            </button>
        </div>
        <div v-if="loading" class="loading">
            Загрузка...
        </div>
        <div v-else>
            <div v-if="orders.length === 0" class="no-orders">
                Нет поступлений для регистрации.
            </div>
            <div v-else>
                <div
                    v-for="order in orders"
                    :key="order.id"
                    class="order-card"
                >
                    <h3>{{ order.product }}</h3>
                    <p>Поставщик: {{ order.supplier }}</p>
                    <p>Количество: {{ order.quantity }}</p>
                    <p>Сумма: {{ order.total_amount }} ₽</p>
                    <p>Дата поступления: {{ formatDate(order.created_at) }}</p>
                    <button
                        @click="registerOrder(order.id)"
                        :disabled="order.registering"
                        class="register-button"
                    >
                        {{ order.registering ? "Регистрация..." : "Зарегистрировать" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const orders = ref([]);
const loading = ref(false);

const fetchSupplierOrders = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/storekeeper/supplier-orders/delivered");
        orders.value = response.data.map((order) => ({
            ...order,
            registering: false,
        }));
    } catch (error) {
        console.error("Ошибка загрузки поступлений:", error);
    } finally {
        loading.value = false;
    }
};

const registerOrder = async (orderId) => {
    const order = orders.value.find((o) => o.id === orderId);
    if (!order) return;

    order.registering = true;
    try {
        await axios.delete(`/api/storekeeper/supplier-order/${orderId}`);
        alert("Поступление успешно зарегистрировано!");
        orders.value = orders.value.filter((o) => o.id !== orderId);
    } catch (error) {
        console.error("Ошибка регистрации поступления:", error);
        alert("Не удалось зарегистрировать поступление. Попробуйте позже.");
    } finally {
        order.registering = false;
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleString("ru-RU", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

onMounted(fetchSupplierOrders);
</script>

<style scoped>
.supplier-orders {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

h2 {
    text-align: center;
    color: #1e293b;
    font-size: 24px;
}

.refresh-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.refresh-button {
    padding: 10px 20px;
    background-color: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.refresh-button:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.refresh-button:hover:not(:disabled) {
    background-color: #2563eb;
}

.loading {
    text-align: center;
    font-size: 18px;
    color: #475569;
}

.no-orders {
    text-align: center;
    font-size: 18px;
    color: #475569;
}

.order-card {
    background-color: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.order-card h3 {
    margin-bottom: 10px;
    color: #1e293b;
    font-size: 20px;
}

.order-card p {
    margin: 5px 0;
    font-size: 14px;
    color: #475569;
}

.register-button {
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #16a34a;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    transition: background-color 0.3s ease;
}

.register-button:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.register-button:hover:not(:disabled) {
    background-color: #15803d;
}
</style>
