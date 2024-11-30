<script setup>
import { ref } from "vue";
import axios from "axios";

const orderId = ref("");
const clientId = ref("");
const invoiceId = ref("");
const orderInfo = ref(null);
const invoiceResponse = ref(null);
const invoices = ref([]);
const invoiceInfo = ref(null);
const isLoading = ref(false);
const errorMessage = ref("");


const fetchOrderInfo = async () => {
  if (!orderId.value) return;

  isLoading.value = true;
  errorMessage.value = "";
  orderInfo.value = null;

  try {
    const response = await axios.get(`/api/order/${orderId.value}/status/processing`);
    orderInfo.value = response.data;
  } catch (error) {
    errorMessage.value = "Не удалось загрузить информацию о заказе.";
  } finally {
    isLoading.value = false;
  }
};

const createInvoice = async () => {
  if (!orderId.value) return;

  isLoading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.post("/api/accountant/invoice", { orderId: Number(orderId.value) });
    invoiceResponse.value = response.data;
    alert("Счет успешно создан!");
  } catch (error) {
    errorMessage.value = "Не удалось создать счет.";
  } finally {
    isLoading.value = false;
  }
};

const fetchInvoicesByClientId = async () => {
  if (!clientId.value) return;

  isLoading.value = true;
  errorMessage.value = "";
  invoices.value = [];

  try {
    const response = await axios.get(`/api/accountant/invoices/client/${clientId.value}`);
    invoices.value = response.data;
  } catch (error) {
    errorMessage.value = "Не удалось загрузить инвойсы клиента.";
  } finally {
    isLoading.value = false;
  }
};

const fetchInvoiceById = async () => {
  if (!invoiceId.value) return;

  isLoading.value = true;
  errorMessage.value = "";
  invoiceInfo.value = null;

  try {
    const response = await axios.get(`/api/invoice/${invoiceId.value}`);
    invoiceInfo.value = response.data;
  } catch (error) {
    errorMessage.value = "Не удалось загрузить инвойс.";
  } finally {
    isLoading.value = false;
  }
};

const handleSearchOrder = () => {
  fetchOrderInfo();
};

const handleSearchInvoicesByClientId = () => {
  fetchInvoicesByClientId();
};

const handleSearchInvoiceById = () => {
  fetchInvoiceById();
};
</script>

<template>
  <div>
    <h3>Счета клиентам</h3>

    <label for="order-id">Введите Order ID:</label>
    <input
        type="number"
        id="order-id"
        v-model="orderId"
        placeholder="Введите ID заказа"
    />
    <button @click="handleSearchOrder">Поиск по Order ID</button>

    <div v-if="isLoading">Загрузка...</div>
    <div v-else-if="errorMessage">{{ errorMessage }}</div>
    <div v-else-if="orderInfo" class="order-info">
      <h4>Информация о заказе</h4>
      <p><strong>ID:</strong> {{ orderInfo.id }}</p>
      <p><strong>Сумма:</strong> {{ orderInfo.totalAmount }}</p>
      <p><strong>Статус:</strong> {{ orderInfo.status }}</p>
      <p><strong>Адрес:</strong> {{ orderInfo.address }}</p>
      <p><strong>Дата создания:</strong> {{ orderInfo.createdAt }}</p>

      <h5>Товары:</h5>
      <ul>
        <li v-for="item in orderInfo.orderItems" :key="item.productName">
          {{ item.productName }} - {{ item.quantity }} {{ item.unit }} - {{ item.totalAmount }}$
        </li>
      </ul>

      <button @click="createInvoice">Создать счет</button>
    </div>

    <div v-if="invoiceResponse" class="invoice-response">
      <h4>Счет успешно создан:</h4>
      <p><strong>ID счета:</strong> {{ invoiceResponse.id }}</p>
      <p><strong>Order ID:</strong> {{ invoiceResponse.orderId }}</p>
      <p><strong>Выставлен:</strong> {{ invoiceResponse.createdAt }}</p>
      <p><strong>Выставил:</strong> {{ invoiceResponse.issuedBy }}</p>
    </div>

    <label for="client-id">Введите Client ID:</label>
    <input
        type="number"
        id="client-id"
        v-model="clientId"
        placeholder="Введите ID клиента"
    />
    <button @click="handleSearchInvoicesByClientId">Поиск по Client ID</button>

    <div v-if="invoices.length > 0">
      <h4>Инвойсы клиента:</h4>
      <ul>
        <li v-for="invoice in invoices" :key="invoice.id" class="invoice-item">
          <p><strong>ID инвойса:</strong> {{ invoice.id }}</p>
          <p><strong>Order ID:</strong> {{ invoice.orderId }}</p>
          <p><strong>Дата создания:</strong> {{ invoice.createdAt }}</p>
          <p><strong>Дата оплаты:</strong> {{ invoice.paidAt || "Не оплачено" }}</p>
          <p><strong>Выставил:</strong> {{ invoice.issuedBy }}</p>
        </li>
      </ul>
    </div>

    <label for="invoice-id">Введите Invoice ID:</label>
    <input
        type="number"
        id="invoice-id"
        v-model="invoiceId"
        placeholder="Введите ID инвойса"
    />
    <button @click="handleSearchInvoiceById">Поиск по Invoice ID</button>

    <!-- Информация об инвойсе -->
    <div v-if="invoiceInfo" class="invoice-info">
      <h4>Информация об инвойсе:</h4>
      <p><strong>Invoice ID:</strong> {{ invoiceInfo.id }}</p>
      <p><strong>Order ID:</strong> {{ invoiceInfo.orderId }}</p>
      <p><strong>Сумма:</strong> {{ invoiceInfo.totalAmount }}$</p>
      <p><strong>Дата создания:</strong> {{ invoiceInfo.createdAt }}</p>
      <p><strong>Дата оплаты:</strong> {{ invoiceInfo.paidAt || "Не оплачено" }}</p>
      <p><strong>Выставил:</strong> {{ invoiceInfo.issuedBy }}</p>
    </div>
  </div>
</template>

<style scoped>
label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button {
  padding: 10px 20px;
  border: none;
  background-color: #4caf50;
  color: white;
  cursor: pointer;
  border-radius: 5px;
  margin-bottom: 20px;
}

button:hover {
  background-color: #45a049;
}

.order-info, .invoice-response, .invoice-info, .invoice-list {
  margin-top: 20px;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.invoice-item {
  padding: 10px;
  border: 1px solid #ddd;
  margin-bottom: 10px;
  background-color: #f9f9f9;
}
</style>
