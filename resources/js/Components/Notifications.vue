<template>
    <div>
        <h2>Уведомления</h2>
        <div class="notification-form">
            <h3>Отправить уведомление</h3>
            <form @submit.prevent="sendNotification">
                <input v-model="newNotification.title" type="text" placeholder="Заголовок" />
                <textarea v-model="newNotification.message" placeholder="Сообщение"></textarea>
                <select v-model="newNotification.department">
                    <option value="logistics">Логистика</option>
                    <option value="sales">Продажи</option>
                    <option value="management">Управление</option>
                </select>
                <button type="submit">Отправить</button>
            </form>
        </div>

        <div class="notification-list">
            <h3>Полученные уведомления</h3>
            <ul>
                <li
                    v-for="notification in notifications"
                    :key="notification.id"
                    :class="{ read: notification.is_read }"
                >
                    <h4>{{ notification.title }}</h4>
                    <p>{{ notification.message }}</p>
                    <small>Отдел: {{ notification.department }}</small>
                    <button @click="markAsRead(notification.id)" v-if="!notification.is_read">
                        Пометить как прочитанное
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            notifications: [],
            newNotification: {
                title: "",
                message: "",
                department: "logistics",
            },
        };
    },
    methods: {
        async fetchNotifications() {
            const response = await axios.get("/api/notifications", {
                params: { department: "management" }, // Укажите отдел текущего пользователя
            });
            this.notifications = response.data;
        },
        async sendNotification() {
            await axios.post("/api/notifications", this.newNotification);
            alert("Уведомление отправлено!");
            this.newNotification = { title: "", message: "", department: "logistics" };
        },
        async markAsRead(id) {
            await axios.patch(`/api/notifications/${id}/read`);
            this.notifications = this.notifications.map((n) =>
                n.id === id ? { ...n, is_read: true } : n
            );
        },
    },
    mounted() {
        this.fetchNotifications();
    },
};
</script>

<style>
.notification-form {
    margin-bottom: 20px;
}
.notification-list li {
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px 0;
    list-style-type: none;
}
.notification-list li.read {
    background-color: #e6ffe6;
}
</style>
