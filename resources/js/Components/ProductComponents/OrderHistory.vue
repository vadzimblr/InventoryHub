<template>
    <div v-if="ordersHistory.length > 0" class="order-history">
        <div v-for="(order, index) in ordersHistory" :key="order.id" class="order-history-item bg-white p-4 rounded-lg shadow-md mb-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-medium">Заказ #{{ order.id }}</h3>
                <p class="text-sm text-gray-500">{{ order.createdAt }}</p>
            </div>

            <div class="flex justify-between items-center mt-4">
                <p>Адрес: <span class="font-semibold">{{ order.address }}</span></p>
                <p>Статус: <span class="font-semibold">{{ order.status }}</span></p>
            </div>

            <div class="mt-4">
                <p class="font-semibold">Итоговая сумма: {{ order.totalAmount }} ₽</p>
            </div>

            <div class="mt-4">
                <button @click="toggleOrderDetails(index)" class="text-blue-500">
                    {{ orderDetailsVisible[index] ? 'Скрыть детали' : 'Показать детали' }}
                </button>
            </div>

            <div v-if="orderDetailsVisible[index]" class="order-details mt-2">
                <ul>
                    <li v-for="(item, idx) in order.orderItems" :key="idx">
                        Продукт: {{ item.productName }} | Количество: {{ item.quantity }} | Цена: {{ item.totalAmount }} ₽
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div v-else>
        <p>История заказов пуста.</p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            ordersHistory: [],
            orderDetailsVisible: [],
        };
    },
    mounted() {
        this.loadOrdersHistory();
    },
    methods: {
        async loadOrdersHistory() {
            try {
                const response = await axios.get('/api/client/orders');
                this.ordersHistory = response.data;
                console.log(this.ordersHistory)
                this.orderDetailsVisible = Array(this.ordersHistory.length).fill(false);
            } catch (error) {
                console.error("Ошибка при загрузке истории заказов:", error);
                alert('Не удалось загрузить историю заказов');
            }
        },

        toggleOrderDetails(index) {
            this.orderDetailsVisible[index] = !this.orderDetailsVisible[index];
        },
    },
};
</script>

<style scoped>
.order-history {
    margin-top: 20px;
}

.order-history-item {
    padding: 10px;
    background-color: #f9f9f9;
    margin-bottom: 10px;
}

button {
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

.order-details {
    margin-top: 10px;
}
</style>
