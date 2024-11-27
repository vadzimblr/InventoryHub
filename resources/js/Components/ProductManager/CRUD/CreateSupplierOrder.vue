<template>
    <div>
        <h2>Разместить заказ</h2>
        <form @submit.prevent="placeOrder">
            <label>Поставщик</label>
            <select v-model="order.supplier_id">
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                    {{ supplier.name }}
                </option>
            </select>

            <h3>Товары</h3>
            <div v-for="(item, index) in order.items" :key="index">
                <select v-model="item.product_id">
                    <option v-for="product in products" :key="product.id" :value="product.id">
                        {{ product.name }}
                    </option>
                </select>
                <input type="number" v-model="item.quantity" placeholder="Количество" />
                <button @click.prevent="removeItem(index)">Удалить</button>
            </div>
            <button @click.prevent="addItem">Добавить товар</button>
            <button type="submit">Разместить заказ</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            suppliers: [],
            products: [],
            order: {
                supplier_id: null,
                items: [],
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
        addItem() {
            this.order.items.push({ product_id: null, quantity: 1 });
        },
        removeItem(index) {
            this.order.items.splice(index, 1);
        },
        async placeOrder() {
            await axios.post("/api/orders", this.order);
            this.order = { supplier_id: null, items: [] };
            alert("Заказ размещён!");
        },
    },
    mounted() {
        this.fetchData();
    },
};
</script>
