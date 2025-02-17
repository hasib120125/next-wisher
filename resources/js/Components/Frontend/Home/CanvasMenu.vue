<template>
    <div>
        <button @click="isOpen = true" class="text-white absolute top-4 left-4">
            <svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm6 7h12v2H9v-2zm-6 7h18v2H3v-2z"/></svg>
        </button>
        <OffCanvas v-model="isOpen" />
    </div>
</template>

<script setup>
    import OffCanvas from '@/Components/Global/OffCanvas.vue'
    import { ref } from 'vue'
    const isOpen = ref(false)
</script>

<style lang="scss" scoped>

</style>