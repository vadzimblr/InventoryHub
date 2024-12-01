<script setup>
import {ref, onMounted} from 'vue';
import axios from 'axios';

const pendingOrders = ref([]);
const expandedOrderId = ref(null);
const confirmationDialog = ref({
    show: false,
    orderId: null,
    action: '',
    message: ''
});

const fetchPendingOrders = async () => {
    try {
        const response = await axios.get('/api/account-manager/orders/pending');
        pendingOrders.value = response.data;
    } catch (error) {
        console.error('Ошибка при загрузке заказов:', error);
    }
};

const updateOrderStatus = async () => {
    const {orderId, action} = confirmationDialog.value;
    const url = `/api/account-manager/order/${orderId}/${action}`;
    try {
        await axios.patch(url);
        fetchPendingOrders();
        confirmationDialog.value.show = false;
    } catch (error) {
        console.error(`Ошибка при обновлении статуса заказа ${orderId}:`, error);
    }
};

const toggleOrderDetails = (orderId) => {
    expandedOrderId.value = expandedOrderId.value === orderId ? null : orderId;
};

const openConfirmationDialog = (orderId, action) => {
    confirmationDialog.value = {
        show: true,
        orderId,
        action,
        message:
            action === 'mark-as-processing'
                ? 'Вы точно хотите отметить заказ как "В обработке"?'
                : 'Вы точно хотите отменить заказ?'
    };
};

const closeConfirmationDialog = () => {
    confirmationDialog.value.show = false;
};

const refreshOrders = () => {
    fetchPendingOrders();
};

onMounted(fetchPendingOrders);
</script>

<template>
    <div class="pending-orders">
        <h1><strong>Заказы в ожидании</strong></h1>
        <div class="refresh-button-container">
            <button @click="refreshOrders" class="refresh-button">Обновить заказы</button>
        </div>
        <br>
        <div v-if="pendingOrders.length === 0" class="empty">
            <p>Нет заказов в ожидании.</p>
        </div>
        <div v-else class="order-list">
            <div
                v-for="order in pendingOrders"
                :key="order.id"
                class="order-card"
                @click="toggleOrderDetails(order.id)"
            >
                <div class="order-header">
                    <h2>Заказ #{{ order.id }}</h2>
                    <p><strong>Общая сумма:</strong> ${{ order.totalAmount }}</p>
                    <p><strong>Адрес:</strong> {{ order.address }}</p>
                    <p><strong>Создан:</strong> {{ order.createdAt }}</p>
                </div>
                <div
                    v-if="expandedOrderId === order.id"
                    class="order-details"
                >
                    <h3>Товары:</h3>
                    <ul>
                        <li
                            v-for="item in order.orderItems"
                            :key="item.productId"
                        >
                            <strong>ID товара: {{ item.productId }}</strong>
                            <p>{{ item.quantity }} x {{ item.productName }} ({{ item.unit }}) - ${{
                                    item.totalAmount
                                }}</p>
                        </li>
                    </ul>
                </div>
                <div class="actions">
                    <button @click.stop="openConfirmationDialog(order.id, 'mark-as-processing')" class="confirm">
                        Подтвердить
                    </button>
                    <button @click.stop="openConfirmationDialog(order.id, 'mark-as-cancelled')" class="cancel">
                        Отменить
                    </button>
                </div>
            </div>
        </div>

        <div v-if="confirmationDialog.show" class="modal-backdrop">
            <div class="modal">
                <p>{{ confirmationDialog.message }}</p>
                <div class="modal-actions">
                    <button @click="updateOrderStatus" class="confirm">Да</button>
                    <button @click="closeConfirmationDialog" class="cancel">Нет</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.pending-orders {
    padding: 1rem;
    max-width: 100%;
}

.order-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}

.order-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    width: 100%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: background-color 0.3s;
}

.order-card:hover {
    background-color: #f9f9f9;
}

.order-header h2 {
    margin: 0;
}

.order-details {
    margin-top: 1rem;
    border-top: 1px solid #ddd;
    padding-top: 0.5rem;
    color: #555;
}

.actions {
    margin-top: 1rem;
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button.confirm {
    background-color: #4caf50;
    color: #fff;
}

button.cancel {
    background-color: #f44336;
    color: #fff;
}

.empty {
    text-align: center;
    color: #666;
}

.refresh-button-container {
    margin-top: 1rem;
    text-align: center;
}

.refresh-button {
    padding: 0.5rem 1rem;
    background-color: #1e40af;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.refresh-button:hover {
    background-color: #3b82f6;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal {
    background: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
}

.modal-actions {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
}
</style>
