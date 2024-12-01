<template>
    <div class="unpaid-orders-container">
        <h2 class="text-2xl font-semibold mb-6">Неоплаченные инвойсы</h2>
        <div v-if="unpaidInvoices.length > 0">
            <div v-for="(invoice, index) in unpaidInvoices" :key="invoice.id" class="unpaid-order bg-white p-6 rounded-lg shadow-lg mb-4">
                <h3 class="text-xl font-medium mb-2">Инвойс #{{ invoice.id }}</h3>
                <p><strong>Заказ:</strong> #{{ invoice.orderId }}</p>
                <p><strong>Дата создания:</strong> {{ invoice.createdAt }}</p>
                <p v-if="invoice.paidAt">Оплачен: {{ invoice.paidAt }}</p>
                <div class="mt-4" v-if="!invoice.paidAt">
                    <button @click="payInvoice(invoice.id)" class="bg-green-500 text-white p-3 rounded-md hover:bg-green-600">
                        Оплатить
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
            <p>Нет неоплаченных инвойсов.</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            unpaidInvoices: [],
        };
    },
    methods: {
        async fetchUnpaidInvoices() {
            try {
                const response = await axios.get("/api/client/invoices");
                this.unpaidInvoices = response.data.filter(invoice => !invoice.paidAt);
            } catch (error) {
                console.error("Ошибка при загрузке инвойсов:", error);
            }
        },

        async payInvoice(invoiceId) {
            try {
                const response = await axios.patch(`/api/client/invoice/${invoiceId}/pay`);

                if (response.status === 200) {
                    alert(`Инвойс #${invoiceId} успешно оплачен!`);
                    this.fetchUnpaidInvoices();
                }
            } catch (error) {
                console.error("Ошибка при оплате инвойса:", error);
                alert("Не удалось выполнить оплату.");
            }
        },
    },
    mounted() {
        this.fetchUnpaidInvoices();
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
