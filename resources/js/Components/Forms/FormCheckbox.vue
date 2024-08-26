<script setup lang="ts">
import { computed } from 'vue';


const emit = defineEmits(['update:checked']);

const props = defineProps<{
    label: string;
    name: string;
    checked: boolean;
    value: any;
}>();

const  proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});

</script>

<template>
    <div class="flex flex-row gap-[10px] items-center">
        <input 
            type="checkbox" 
            :name="props.label" 
            :id="props.label" 
            v-model="proxyChecked" 
            :value="props.value" 
        />
        <label :for="props.label">{{ props.label }}</label>
    </div>
</template>