<script setup>
import StudentRow from './StudentRow.vue';

defineProps({
    students: Array,
    loading: Boolean,
    isAdmin: Boolean,
});

const emit = defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="card">
        <h2>Student Records</h2>

        <p v-if="loading">Loading students...</p>
        <p v-else-if="students.length === 0" class="muted">No students found.</p>

        <table v-else>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <StudentRow
                    v-for="student in students"
                    :key="student.id"
                    :student="student"
                    :is-admin="isAdmin"
                    @edit="emit('edit', $event)"
                    @delete="emit('delete', $event)"
                />
            </tbody>
        </table>
    </div>
</template>
