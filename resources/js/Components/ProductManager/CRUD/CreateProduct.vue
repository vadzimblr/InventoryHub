<template>
    <div>
        <h2>Добавить товар</h2>
        <form @submit.prevent="addProduct">
            <input v-model="product.name" type="text" placeholder="Название товара" />
            <input v-model="product.price" type="number" placeholder="Цена товара" />
            <button type="submit">Добавить</button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            product: {
                name: "",
                price: null,
            },
        };
    },
    methods: {
        async addProduct() {
            await axios.post("/api/products", this.product);
            this.product = { name: "", price: null };
            alert("Товар добавлен!");
        },
    },
};
</script>
