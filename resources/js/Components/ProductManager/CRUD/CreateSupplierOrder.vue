<template>
    <div class="container">
        <h2>Управление заказами у поставщиков</h2>

        <div class="navigation">
            <button
                class="nav-button"
                :class="{ active: currentPage === 'createOrder' }"
                @click="currentPage = 'createOrder'">
                Создать заказ
            </button>
            <button
                class="nav-button"
                :class="{ active: currentPage === 'viewAllOrders' }"
                @click="currentPage = 'viewAllOrders'">
                Просмотр всех заказов
            </button>
            <button
                class="nav-button"
                :class="{ active: currentPage === 'viewOrderDetails' }"
                @click="currentPage = 'viewOrderDetails'">
                Просмотр заказа
            </button>
        </div>

        <div class="content">
            <div v-if="currentPage === 'createOrder'">
                <h3>Создание заказа</h3>
                <form @submit.prevent="placeOrder">
                    <h3>Продукт</h3>
                    <select v-model="order.product_id" @change="onProductChange">
                        <option
                            v-for="product in products"
                            :key="product.id"
                            :value="product.id">
                            {{ product.name }}
                        </option>
                    </select>

                    <label>Количество</label>
                    <input type="number" v-model="order.quantity" placeholder="Количество" min="1" />

                    <h3>Поставщик (Автозаполнение)</h3>
                    <p>{{ order.supplier_name || 'Не выбран' }}</p> <!-- Имя поставщика -->

                    <button type="submit">Разместить заказ</button>
                </form>
            </div>

            <div v-if="currentPage === 'viewAllOrders'">
                <h3>Все заказы</h3>

                <label for="supplierId" class="input-label">Введите Supplier ID:</label>
                <input
                    type="number"
                    v-model="supplierId"
                    @input="fetchOrders"
                    placeholder="Введите ID поставщика"
                    class="input-field"
                />

                <div v-if="orders.length > 0">
                    <div v-for="order in orders" :key="order.id" class="order-item">
                        <p>{{ order.supplier }} - {{ order.product }} - {{ order.quantity }} - {{ order.status }}</p>
                        <button @click="viewOrderDetails(order.id)">Просмотр заказа</button>
                    </div>
                </div>
                <div v-else>
                    <p>Нет заказов для выбранного поставщика.</p>
                </div>
            </div>

            <div v-if="currentPage === 'viewOrderDetails' && currentOrder">
                <h3>Детали заказа</h3>
                <p>Поставщик: {{ currentOrder.supplier }}</p>
                <p>Продукт: {{ currentOrder.product }}</p>
                <p>Количество: {{ currentOrder.quantity }}</p>
                <p>Общая сумма: {{ currentOrder.total_amount }}</p>
                <p>Статус: {{ currentOrder.status }}</p>
                <p>Дата размещения: {{ currentOrder.created_at }}</p>
                <button @click="currentPage = 'viewAllOrders'">Назад</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            currentPage: 'createOrder',
            suppliers: [],
            products: [],
            orders: [],
            currentOrder: null,
            supplierId: null,
            order: {
                supplier_id: null,
                supplier_name: "",
                product_id: null,
                quantity: 1,
            },
        };
    },
    methods: {
        async fetchData() {
            const supplierResponse = await axios.get("/api/suppliers");
            const productResponse = await axios.get("/api/products");
            this.suppliers = supplierResponse.data;
            this.products = productResponse.data;
        },
        async fetchOrders() {
            if (this.supplierId) {
                const orderResponse = await axios.get(`/api/procurement-manager/supplier-orders/supplier/${this.supplierId}`);
                this.orders = orderResponse.data;
            } else {
                this.orders = [];
            }
        },
        async fetchOrderDetails(orderId) {
            const orderDetailsResponse = await axios.get(`/api/procurement-manager/supplier-order/${orderId}`);
            this.currentOrder = orderDetailsResponse.data;
            this.currentPage = 'viewOrderDetails';
        },
        async placeOrder() {
            const orderData = {
                supplierId: this.order.supplier_id,
                productId: this.order.product_id,
                quantity: this.order.quantity,
            };

            await axios.post("/api/procurement-manager/supplier-order", orderData)
                .then(response => {
                    alert("Заказ размещён!");
                    this.order = {supplier_name: "", product_id: null, quantity: 1};
                    this.currentPage = 'viewAllOrders';
                })
                .catch(error => {
                    console.error('Ошибка при размещении заказа:', error);
                });
        },
        async onProductChange() {
            const selectedProduct = this.products.find(product => product.id === this.order.product_id);
            if (selectedProduct) {
                const supplierResponse = await axios.get(`/api/product/${this.order.product_id}/supplier`);
                this.order.supplier_id = supplierResponse.data.supplierId;

                const supplier = this.suppliers.find(supplier => supplier.id === this.order.supplier_id);
                this.order.supplier_name = supplier ? supplier.name : "Не найден";
            }
        },
        viewOrderDetails(orderId) {
            this.fetchOrderDetails(orderId);
        },
    },
    mounted() {
        this.fetchData();
    },
};
</script>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    text-align: center;
}

.navigation {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
}

.nav-button {
    padding: 10px 20px;
    background-color: #034c35;
    border: 1px solid #ccc;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
}

.nav-button.active {
    background-color: #4CAF50;
    color: white;
}

.content {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.order-item {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}

button {
    padding: 5px 10px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color: #4caf50;
}

input[type="number"] {
    margin-top: 5px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 60px;
}
.input-label {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

.input-field {
    padding: 10px;
    font-size: 16px;
    width: 100%;
    max-width: 300px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
</style>
