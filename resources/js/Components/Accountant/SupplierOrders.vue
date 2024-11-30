<template>
    <div>
        <h2>Поставщик - Заказы</h2>

        <!-- Создание нового счета -->
        <div>
            <h3>Создать новый счет</h3>
            <form @submit.prevent="createBill">
                <label for="supplierOrderId">ID Заказа Поставщика:</label>
                <input v-model="newBill.supplierOrderId" type="number" id="supplierOrderId" required />
                <button type="submit">Создать Счет</button>
            </form>
        </div>

        <!-- Список неоплаченных счетов -->
        <div>
            <h3>Неоплаченные счета</h3>
            <ul>
                <li v-for="bill in unpaidBills" :key="bill.id">
                    <p><strong>Счет ID:</strong> {{ bill.id }} | <strong>Дата создания:</strong> {{ bill.createdAt }}</p>
                    <p><strong>Платежный статус:</strong> {{ bill.paidAt ? 'Оплачено' : 'Не оплачено' }}</p>
                    <button @click="payBill(bill.id)" :disabled="bill.paidAt !== null">Оплатить</button>
                </li>
            </ul>
        </div>

        <!-- Получение счета по ID -->
        <div>
            <h3>Получить счет по ID</h3>
            <form @submit.prevent="getBillById">
                <label for="billId">ID Счета:</label>
                <input v-model="billId" type="number" id="billId" required />
                <button type="submit">Получить Счет</button>
            </form>
            <div v-if="billDetails">
                <h4>Детали Счета</h4>
                <p><strong>ID:</strong> {{ billDetails.id }}</p>
                <p><strong>Дата создания:</strong> {{ billDetails.createdAt }}</p>
                <p><strong>Дата оплаты:</strong> {{ billDetails.paidAt || 'Не оплачено' }}</p>
                <p><strong>Бухгалтер:</strong> {{ billDetails.accountant }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            newBill: {
                supplierOrderId: null,
            },
            unpaidBills: [],
            billId: null,
            billDetails: null,
        };
    },
    mounted() {
        this.fetchUnpaidBills();
    },
    methods: {
        // Создание нового счета
        async createBill() {
            try {
                const response = await axios.post('/api/accountant/bill', this.newBill);
                alert('Счет создан успешно');
                this.newBill.supplierOrderId = null; // Reset form
                this.fetchUnpaidBills(); // Refresh unpaid bills list
            } catch (error) {
                console.error('Ошибка при создании счета:', error);
                alert('Не удалось создать счет');
            }
        },

        // Получение всех неоплаченных счетов
        async fetchUnpaidBills() {
            try {
                const response = await axios.get('/api/accountant/bills/unpaid');
                this.unpaidBills = response.data;
            } catch (error) {
                console.error('Ошибка при получении неоплаченных счетов:', error);
            }
        },

        // Получение счета по ID
        async getBillById() {
            if (!this.billId) {
                alert('Введите ID счета');
                return;
            }
            try {
                const response = await axios.get(`/api/accountant/bill/${this.billId}`);
                this.billDetails = response.data;
            } catch (error) {
                console.error('Ошибка при получении счета:', error);
                alert('Не удалось получить счет');
            }
        },

        // Оплата счета
        async payBill(billId) {
            try {
                await axios.patch(`/api/accountant/bill/${billId}/pay`);
                alert('Счет успешно оплачен');
                this.fetchUnpaidBills(); // Refresh unpaid bills list
            } catch (error) {
                console.error('Ошибка при оплате счета:', error);
                alert('Не удалось оплатить счет');
            }
        },
    },
};
</script>

<style scoped>
/* Добавьте стили для страницы */
h2 {
    text-align: center;
}

form {
    margin-bottom: 20px;
}

button {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:disabled {
    background-color: gray;
}
</style>
