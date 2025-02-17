<template>
    <div class="flex gap-5 mb-5">
        <template
            v-for="(item, index) in options"
            :key="index"
            class="border"
        >
            <button
                :class="{
                    'font-black border-b-2 border-black': modelValue == item.value,
                    'text-blue-500': item.value == 'list'
                }"
                @click="() => {
                    if(item.value == 'list') {
                        $emit('onListClick')
                        return
                    }
                    modelValue = item.value
                }"
            >
                {{ Helper.translate(item.title) }}
            </button>
        </template>
    </div>
</template>

<script setup>
import Helper from '@/Helper';

const props = defineProps({
    options: Array,
})

const modelValue = defineModel()

</script>