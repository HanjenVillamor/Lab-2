<script setup>
defineProps({
    student: Object,
    isAdmin: Boolean,
});

const emit = defineEmits(['edit', 'delete']);
</script>

<template>
    <tr>
        <td>{{ student.id }}</td>
        <td>{{ student.name }}</td>
        <td>{{ student.email }}</td>
        <td>{{ student.course }}</td>
        <td>{{ student.age }}</td>
        <td>
            <template v-if="isAdmin">
                <button class="btn small" @click="emit('edit', student)">Edit</button>
                <button class="btn small danger" @click="emit('delete', student)">Delete</button>
            </template>
            <span v-else class="muted">View only</span>
        </td>
    </tr>
</template>