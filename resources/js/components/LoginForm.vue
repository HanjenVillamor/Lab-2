<script setup>
import { ref } from 'vue';

defineProps({
    error: String,
    loading: Boolean,
});

const emit = defineEmits(['login']);

const email = ref('');
const password = ref('');

function submit() {
    emit('login', { email: email.value, password: password.value });
}
</script>

<template>
    <div class="card login">
        <h2>Login</h2>
        <p v-if="error" class="alert error">{{ error }}</p>
        <form @submit.prevent="submit">
            <label>Email</label>
            <input v-model="email" type="email" required />
            <label>Password</label>
            <input v-model="password" type="password" required />
            <button class="btn primary" :disabled="loading">
                {{ loading ? 'Logging in...' : 'Login' }}
            </button>
        </form>
    </div>
</template>
Sent
Compose
Write to Han ON
