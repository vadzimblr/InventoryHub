<template>
    <div class="orders-container">
        <h1 class="text-2xl font-semibold mb-6">Текущий заказ</h1>

        <div class="current-order bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-medium mb-4">Текущий заказ</h2>

            <div class="mb-4">
                <label for="address" class="block text-gray-700">Адрес доставки:</label>
                <input
                    v-model="currentOrder.address"
                    id="address"
                    type="text"
                    class="w-full p-2 border rounded-md"
                    placeholder="Введите адрес"
                />
            </div>

            <div v-if="currentOrder.orderItems.length > 0">
                <div v-for="(item, index) in currentOrder.orderItems" :key="index" class="order-item mb-4">
                    <div class="flex justify-between items-center w-full">
                        <p class="text-sm font-semibold" @click="toggleProductDetails(index)">
                            {{ item.name }} ({{ item.productId }})
                        </p>
                        <div class="flex items-center space-x-4">
                            <button @click="decreaseQuantity(item)" class="bg-red-500 text-white p-2 rounded-md">-</button>
                            <span class="text-center w-10">{{ item.quantity }}</span>
                            <button @click="increaseQuantity(item)" class="bg-green-500 text-white p-2 rounded-md">+</button>
                            <button @click="removeItem(index)" class="bg-gray-500 text-white p-2 rounded-md">Удалить</button>
                        </div>
                    </div>

                    <ProductDetails
                        :productId="item.productId"
                        :quantity="item.quantity"
                        v-if="selectedProductIndex === index"
                        @product-name="updateProductName($event, index)"
                    />
                </div>
            </div>

            <div v-else>
                <p>Ваш заказ пуст.</p>
            </div>

            <button @click="placeOrder" class="mt-4 bg-blue-500 text-white p-4 rounded-md hover:bg-blue-600">
                Разместить заказ
            </button>
        </div>

        <UnpaidOrders :ordersHistory="ordersHistory" @order-updated="loadOrdersHistory" />

        <h2 class="text-2xl font-semibold mt-8">История заказов</h2>
        <OrderHistory :ordersHistory="ordersHistory" @order-updated="loadOrdersHistory" />
    </div>
</template>

<script>
import axios from "axios";
import ProductDetails from "./ProductDetails.vue";
import OrderHistory from "./OrderHistory.vue";
import UnpaidOrders from "./UnpaidOrders.vue";

export default {
    components: {
        ProductDetails,
        OrderHistory,
        UnpaidOrders,
    },
    data() {
        return {
            currentOrder: {
                address: "",
                orderItems: [],
            },
            ordersHistory: [],
            productDetails: {},
            selectedProductIndex: null,
        };
    },

    mounted() {
        this.loadOrdersHistory();
        this.loadCurrentOrder();
    },

    methods: {
        toggleProductDetails(index) {
            this.selectedProductIndex = this.selectedProductIndex === index ? null : index;
        },

        async loadOrdersHistory() {
            try {
                const response = await axios.get("http://localhost:8080/api/client/orders");
                this.ordersHistory = response.data;
            } catch (error) {
                console.error("Ошибка при загрузке истории заказов:", error);
                this.ordersHistory = [];
            }
        },

        async getProductDetails(productId) {
            if (!this.productDetails[productId]) {
                try {
                    const response = await axios.get(`/api/product/${productId}`);
                    this.productDetails[productId] = response.data;
                } catch (error) {
                    console.error("Ошибка при получении данных о продукте:", error);
                    this.productDetails[productId] = null;
                }
            }
            return this.productDetails[productId];
        },

        loadCurrentOrder() {
            const order = JSON.parse(localStorage.getItem("currentOrder")) || {
                address: "",
                orderItems: [],
            };
            this.currentOrder = order;
        },

        increaseQuantity(item) {
            item.quantity++;
            this.saveCurrentOrder();
        },

        decreaseQuantity(item) {
            if (item.quantity > 1) {
                item.quantity--;
                this.saveCurrentOrder();
            }
        },

        removeItem(index) {
            this.currentOrder.orderItems.splice(index, 1);
            this.saveCurrentOrder();
        },

        saveCurrentOrder() {
            localStorage.setItem("currentOrder", JSON.stringify(this.currentOrder));
        },

        async placeOrder() {
            if (this.currentOrder.address && this.currentOrder.orderItems.length > 0) {
                try {
                    const orderData = {
                        address: this.currentOrder.address,
                        orderItems: this.currentOrder.orderItems.map((item) => ({
                            productId: item.productId,
                            quantity: item.quantity,
                        })),
                    };

                    const response = await axios.post(
                        "http://localhost:8080/api/client/order",
                        orderData
                    );

                    this.currentOrder = { address: "", orderItems: [] };
                    localStorage.removeItem("currentOrder");
                    alert("Заказ размещен!");
                    this.loadOrdersHistory();
                } catch (error) {
                    console.error("Ошибка при размещении заказа:", error);
                    alert("Не удалось разместить заказ.");
                }
            } else {
                alert("Заполните все поля заказа.");
            }
        },

        updateProductName(name, index) {
            this.currentOrder.orderItems[index].name = name;
            this.saveCurrentOrder();
        },
    },
};
</script>

<style scoped>
.orders-container {
    padding: 20px;
}

.current-order {
    margin-bottom: 40px;
}

.order-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.order-item p {
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

button {
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>
