<template>
    <div>
        <h2>Добавить товар в систему</h2>
        <form @submit.prevent="addProduct">
            <div>
                <label for="name">Название товара:</label>
                <input v-model="product.name" type="text" id="name" placeholder="Название товара" required />
            </div>
            <div>
                <label for="description">Описание товара:</label>
                <br>
                <textarea
                    v-model="product.description"
                    id="description"
                    placeholder="Описание товара"
                ></textarea>
            </div>
            <div>
                <label for="price">Цена товара:</label>
                <input v-model.number="product.price" type="number" id="price" placeholder="Цена товара" required />
            </div>
            <div>
                <label for="supplier">Поставщик:</label>
                <select v-model="product.supplierId" id="supplier" required>
                    <option disabled value="">Выберите поставщика</option>
                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                        {{ supplier.name }}
                    </option>
                </select>
            </div>
            <div>
                <label for="unit">Единица измерения:</label>
                <input v-model="product.unit" type="text" id="unit" placeholder="Единица измерения (e.g., item)" required />
            </div>
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
                description: "",
                price: null,
                supplierId: "",
                quantity: 0,
                unit: "item",
            },
            suppliers: [],
        };
    },
    async created() {
        try {
            const response = await axios.get("/api/suppliers");
            this.suppliers = response.data;
        } catch (error) {
            console.error("Ошибка при загрузке списка поставщиков:", error);
        }
    },
    methods: {
        async addProduct() {
            try {
                await axios.post("/api/product", this.product);
                this.product = {
                    name: "",
                    description: "",
                    price: null,
                    supplierId: "",
                    quantity: 0,
                    unit: "item",
                };
                alert("Товар успешно добавлен!");
            } catch (error) {
                console.error("Ошибка при добавлении товара:", error);
                alert("Не удалось добавить товар. Попробуйте снова.");
            }
        },
    },
};
</script>

<style scoped>
form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 400px;
    margin: 0 auto;
}

label {
    font-weight: bold;
}

input,
textarea,
select,
button {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button {
    background-color: #4caf50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
</style>
