<template>
    <section class="admin-page">
        <h1>Admin Dashboard</h1>

        <!-- Create User Section -->
        <section class="form-section">
            <h2>Create User</h2>
            <form @submit.prevent="createUser" class="form-container">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Enter name"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter email"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="Enter password"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="Confirm password"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" v-model="form.role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn">Create</button>
            </form>
        </section>

        <!-- Users List Section -->
        <section class="users-section">
            <h2>Users List</h2>
            <table class="users-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                </tr>
                </tbody>
            </table>
        </section>
    </section>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                role: "user",
            },
            users: [], // Array to hold the list of users
        };
    },
    methods: {
        async createUser() {
            try {
                // Replace with actual API endpoint
                const response = await axios.post("/api/users", this.form);
                alert("User created successfully!");
                this.users.push(response.data.user); // Add the new user to the list
                this.resetForm();
            } catch (error) {
                alert("Error creating user: " + error.response.data.message);
            }
        },
        resetForm() {
            this.form = {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                role: "user",
            };
        },
        async fetchUsers() {
            try {
                const response = await axios.get("/api/users"); // Replace with actual API endpoint
                this.users = response.data.users;
            } catch (error) {
                alert("Error fetching users: " + error.response.data.message);
            }
        },
    },
    mounted() {
        this.fetchUsers(); // Fetch users when the component is mounted
    },
};
</script>

<style scoped>
.admin-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

h1,
h2 {
    text-align: center;
    color: #333;
}

.form-section,
.users-section {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn {
    padding: 10px;
    font-size: 16px;
    color: white;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.users-table th,
.users-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.users-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.users-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
</style>
