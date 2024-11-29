<template>
    <div class="unpaid-orders-container">
        <h2 class="text-2xl font-semibold mb-6">Неоплаченные заказы</h2>
        <div v-if="unpaidOrders.length > 0">
            <div v-for="(order, index) in unpaidOrders" :key="order.id" class="unpaid-order bg-white p-6 rounded-lg shadow-lg mb-4">
                <h3 class="text-xl font-medium mb-2">Заказ #{{ order.id }}</h3>
                <p><strong>Адрес:</strong> {{ order.address }}</p>
                <p><strong>Сумма:</strong> {{ order.totalAmount }} ₽</p>
                <p><strong>Статус:</strong> {{ order.status }}</p>
                <div class="mt-4">
                    <button @click="payOrder(order.id)" class="bg-green-500 text-white p-3 rounded-md hover:bg-green-600">
                        Оплатить
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
            <p>Нет неоплаченных заказов.</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        ordersHistory: Array,
    },
    computed: {
        unpaidOrders() {
            return this.ordersHistory.filter(order => order.status === 'неоплачен');
        },
    },
    methods: {
        async payOrder(orderId) {
            try {
                const response = await this.simulatePayment(orderId);

                if (response.status === 'оплачен') {
                    alert(`Заказ #${orderId} оплачен!`);
                    this.$emit('order-updated');
                }
            } catch (error) {
                console.error('Ошибка при оплате заказа:', error);
                alert('Не удалось выполнить оплату.');
            }
        },
        async simulatePayment(orderId) {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve({ id: orderId, status: 'оплачен' });
                }, 1000);
            });
        },
    },
};
</script>

<style scoped>
.unpaid-orders-container {
    margin-top: 20px;
}

.unpaid-order {
    padding: 15px;
    background-color: #f9f9f9;
    margin-bottom: 15px;
}

button {
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>
