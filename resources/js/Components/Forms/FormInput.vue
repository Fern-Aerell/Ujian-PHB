<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    label: string;
    name: string;
    type: 'text' | 'password' | 'email' | 'number' | 'date';
    placeholder: string;
    model: string;
}>();

const emit = defineEmits(['update:modelValue']);

const inputValue = computed({
    get: () => props.model,
    set: (value) => emit('update:modelValue', value)
});

</script>

<template>
    <div class="flex flex-col">
        <label :for="props.name">{{ props.label }}</label>
        <input 
            :type="props.type" 
            :name="props.name" 
            :id="props.name" 
            v-model="inputValue" 
            :placeholder="props.placeholder" 
        />
    </div>
</template>