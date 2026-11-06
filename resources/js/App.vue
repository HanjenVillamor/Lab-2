<script setup>
import { ref, computed, onMounted } from 'vue';
import { request } from './api';
import LoginForm from './components/LoginForm.vue';
import StudentForm from './components/StudentForm.vue';
import StudentList from './components/StudentList.vue';

const user = ref(JSON.parse(localStorage.getItem('user') || 'null'));
const students = ref([]);
const loading = ref(false);
const saving = ref(false);
const loginLoading = ref(false);
const loginError = ref('');
const message = ref('');
const messageType = ref('success');
const formErrors = ref({});
const editing = ref(null);

const isAdmin = computed(() => user.value && user.value.role === 'admin');

function notify(text, type = 'success') {
    message.value = text;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 4000);
}

function clearSession() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    user.value = null;
    students.value = [];
}

async function login(credentials) {
    loginLoading.value = true;
    loginError.value = '';
    const res = await request('POST', '/login', credentials);
    loginLoading.value = false;

    if (!res.ok) {
        loginError.value = (res.data && res.data.message) || 'Login failed';
        return;
    }

    localStorage.setItem('token', res.data.token);
    localStorage.setItem('user', JSON.stringify(res.data.user));
    user.value = res.data.user;
    notify('Login successful');
    loadStudents();
}

async function logout() {
    await request('POST', '/logout');
    clearSession();
}

async function loadStudents() {
    loading.value = true;
    const res = await request('GET', '/students');
    loading.value = false;

    if (res.status === 401) {
        clearSession();
        return;
    }
    if (res.ok) {
        students.value = res.data.data;
    } else {
        notify('Failed to load students', 'error');
    }
}

async function saveStudent(data) {
    saving.value = true;
    formErrors.value = {};

    const res = editing.value
        ? await request('PUT', '/students/' + editing.value.id, data)
        : await request('POST', '/students', data);

    saving.value = false;

    if (res.status === 422) {
        formErrors.value = res.data.errors || {};
        notify('Please fix the validation errors', 'error');
        return;
    }
    if (res.status === 403) {
        notify('Forbidden: admin only', 'error');
        return;
    }
    if (!res.ok) {
        notify((res.data && res.data.message) || 'Something went wrong', 'error');
        return;
    }

    notify(res.data.message);
    editing.value = null;
    loadStudents();
}

async function deleteStudent(student) {
    if (!confirm('Delete ' + student.name + '?')) return;

    const res = await request('DELETE', '/students/' + student.id);

    if (res.ok) {
        notify(res.data.message);
        loadStudents();
    } else {
        notify((res.data && res.data.message) || 'Delete failed', 'error');
    }
}

function cancelEdit() {
    editing.value = null;
    formErrors.value = {};
}

onMounted(() => {
    if (user.value) loadStudents();
});
</script>

<template>
    <div class="container">
        <header>
            <h1>Student Management</h1>
            <div v-if="user" class="userbar">
                <span>{{ user.name }} ({{ user.role }})</span>
                <button class="btn small" @click="logout">Logout</button>
            </div>
        </header>

        <p v-if="message" :class="['alert', messageType]">{{ message }}</p>

        <LoginForm v-if="!user" :error="loginError" :loading="loginLoading" @login="login" />

        <template v-else>
            <StudentForm
                v-if="isAdmin"
                :student="editing"
                :errors="formErrors"
                :saving="saving"
                @save="saveStudent"
                @cancel="cancelEdit"
            />
            <p v-else class="alert info">You are a regular user: view only.</p>

            <StudentList
                :students="students"
                :loading="loading"
                :is-admin="isAdmin"
                @edit="editing = $event"
                @delete="deleteStudent"
            />
        </template>
    </div>
</template>