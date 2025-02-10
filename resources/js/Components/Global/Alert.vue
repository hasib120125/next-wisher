<template>
    <div class="px-4 py-4 text-gray-400 text-center w-full">
        {{ Helper.translate(title) }}
    </div>
</template>

<script setup>
    import Helper from '@/Helper';
    defineProps({
        title: {
            type: String,
            default: 'No data found!'
        }
    })
</script>