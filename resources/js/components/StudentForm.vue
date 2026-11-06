<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    student: Object,
    errors: Object,
    saving: Boolean,
});

const emit = defineEmits(['save', 'cancel']);

const form = reactive({ name: '', email: '', course: '', age: '' });

watch(
    () => props.student,
    (s) => {
        form.name = s ? s.name : '';
        form.email = s ? s.email : '';
        form.course = s ? s.course : '';
        form.age = s ? s.age : '';
    },
    { immediate: true }
);

function submit() {
    emit('save', { ...form });
}
</script>

<template>
    <div class="card">
        <h2>{{ student ? 'Edit Student' : 'Add Student' }}</h2>
        <form @submit.prevent="submit">
            <label>Name</label>
            <input v-model="form.name" type="text" />
            <small v-if="errors && errors.name" class="field-error">{{ errors.name[0] }}</small>

            <label>Email</label>
            <input v-model="form.email" type="text" />
            <small v-if="errors && errors.email" class="field-error">{{ errors.email[0] }}</small>

            <label>Course</label>
            <input v-model="form.course" type="text" />
            <small v-if="errors && errors.course" class="field-error">{{ errors.course[0] }}</small>

            <label>Age</label>
            <input v-model="form.age" type="number" />
            <small v-if="errors && errors.age" class="field-error">{{ errors.age[0] }}</small>

            <div class="actions">
                <button class="btn primary" :disabled="saving">
                    {{ saving ? 'Saving...' : student ? 'Update' : 'Add' }}
                </button>
                <button v-if="student" type="button" class="btn" @click="emit('cancel')">Cancel</button>
            </div>
        </form>
    </div>
</template>