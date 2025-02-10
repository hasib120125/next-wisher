<template>
    <div class="flex">
        <button @click="handleFontSize(-1)" class="px-2 text-lg border border-r-0">-</button>
        <span type="text" class="bg-transparent text-center border border-gray-400 border-opacity-30 px-2 w-[80px] py-0">
            {{ fontSize }}
        </span>
        <button @click="handleFontSize(1)" class="px-2 text-lg border border-l-0">+</button>
    </div>
</template>

<script setup>
    import { ref } from 'vue'

    defineProps({
        modelValue: String
    })
    const emit = defineEmits()
    const fontSize = ref(20)
    const handleFontSize = (direction) => {
        if(direction < 0 && fontSize.value > 0){
            fontSize.value--
        }
        if(direction > 0){
            fontSize.value++
        }
        emit('update:modelValue', fontSize.value)
    }
</script>