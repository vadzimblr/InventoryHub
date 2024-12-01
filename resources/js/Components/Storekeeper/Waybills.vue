<template>
    <div class="shipments">
        <h2>Отгрузка и выписка накладных</h2>


        <div class="waybill-form">
            <h3>Выписать накладную</h3>
            <form @submit.prevent="createWaybill">
                <label for="invoiceId">Введите номер счета, на который выписывается накладная:</label>
                <input
                    type="number"
                    id="invoiceId"
                    v-model="invoiceId"
                    required
                />
                <button type="submit" :disabled="loading">Выписать накладную</button>
            </form>
        </div>

        <!-- Список накладных -->
        <div class="waybill-list">
            <h3>Список накладных</h3>
            <button @click="fetchWaybills" :disabled="loading">
                {{ loading ? "Загрузка..." : "Обновить список" }}
            </button>
            <div v-if="loading" class="loading">Загрузка...</div>
            <div v-else>
                <div v-if="waybills.length === 0" class="no-waybills">
                    Накладных нет.
                </div>
                <div v-else>
                    <ul>
                        <li
                            v-for="waybill in waybills"
                            :key="waybill.id"
                            class="waybill-item"
                        >
                            <p><strong>Накладная №{{ waybill.id }}</strong></p>
                            <p>Номер накладной: {{ waybill.invoiceId }}</p>
                            <p>Отгрузка: {{ waybill.storekeeper }}</p>
                            <p>Дата отгрузки: {{ formatDate(waybill.handoffTime) }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const invoiceId = ref(null);
const waybills = ref([]);
const loading = ref(false);

const createWaybill = async () => {
    if (!invoiceId.value) return;

    loading.value = true;
    try {
        const response = await axios.post("/api/storekeeper/waybill", {
            invoiceId: invoiceId.value,
        });
        alert(`Накладная №${response.data.id} успешно выписана!`);
        invoiceId.value = null;
        fetchWaybills();
    } catch (error) {
        console.error("Ошибка при выписке накладной:", error);
        alert("Не удалось выписать накладную. Попробуйте позже.");
    } finally {
        loading.value = false;
    }
};

const fetchWaybills = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/storekeeper/waybills");
        waybills.value = response.data;
    } catch (error) {
        console.error("Ошибка загрузки накладных:", error);
        alert("Не удалось загрузить накладные. Попробуйте позже.");
    } finally {
        loading.value = false;
    }
};

// Форматирование даты
const formatDate = (date) => {
    return new Date(date).toLocaleString("ru-RU", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Загружаем накладные при монтировании
fetchWaybills();
</script>

<style scoped>
.shipments {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
    gap: 20px;
    color: #475569;
}

h2 {
    font-size: 24px;
    color: #1e293b;
}

.waybill-form,
.waybill-list {
    width: 100%;
    max-width: 600px;
}

.waybill-form form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.waybill-form label {
    font-size: 16px;
}

.waybill-form input {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #cbd5e1;
}

.waybill-form button {
    padding: 10px;
    background-color: #16a34a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.waybill-form button:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.waybill-form button:hover:not(:disabled) {
    background-color: #15803d;
}

.waybill-list button {
    padding: 10px 20px;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.waybill-list button:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}

.waybill-list button:hover:not(:disabled) {
    background-color: #2563eb;
}

.loading {
    text-align: center;
    font-size: 18px;
    color: #475569;
}

.no-waybills {
    text-align: center;
    font-size: 18px;
    color: #475569;
}

.waybill-item {
    background-color: #f1f5f9;
    padding: 15px;
    margin: 10px 0;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.waybill-item p {
    margin: 5px 0;
    font-size: 14px;
    color: #475569;
}
</style>
