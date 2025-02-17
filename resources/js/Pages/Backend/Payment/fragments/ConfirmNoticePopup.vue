<template>

    <div @click.self="$emit('close')" class="fixed top-0 left-0 right-0 bottom-0 bg-black/50 backdrop-blur-sm z-[1002] flex items-center justify-center">
        <button @click="$emit('close')" class="text-white absolute top-5 right-5 bg-red-500 p-2 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"></path></svg>
        </button>
        <div class="bg-white w-full max-w-[400px] rounded px-6 py-8 text-center">
            <div class="flex justify-center mb-5 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68" fill="currentColor" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm-8-80V80a8,8,0,0,1,16,0v56a8,8,0,0,1-16,0Zm20,36a12,12,0,1,1-12-12A12,12,0,0,1,140,172Z"></path></svg>
            </div>
            <div class="text-gray-600 font-semibold">
                {{ Helper.translate('The payment amount will be converted and processed in XOF at the following rate') }}:
            </div>
            <div class="text-blue-500 text-xl font-semibold">
                €1 = {{ rate }} {{ currency }}
            </div>
            <div class="text-gray-600 font-semibold">
                {{ Helper.translate('We also charge a 3.5% conversion fee') }}
            </div>
            <div class="text-blue-500 text-xl font-semibold">
                €{{ amount }} = {{ total }} {{ currency }}
            </div>
            <div class="text-red-600 text-xs font-semibold">
                {{ Helper.translate('All amounts are rounded up to the nearest decimal') }}
            </div>
            <div class="text-gray-600 text-xl font-semibold mt-2">
                {{ Helper.translate('You will be charged') }} {{ currency }} {{ total + charge }} <br> {{ total }} + {{ charge }} {{ Helper.translate('conversion Fee') }}
            </div>
            <div class="flex justify-center mt-5">
                <button @click="$emit('submitToPay', total + charge)" :disabled="processing" class="bg-blue-500 disabled:opacity-50 hover:opacity-80 text-white rounded py-2 px-5 shadow-lg active:scale-95">
                    {{ Helper.translate('PAY NOW') }}: {{ currency }} {{ total + charge }}
                </button>
            </div>
        </div>
    </div>

</template>

<script setup>
import Helper from '@/Helper';
import { computed } from 'vue';

const props = defineProps({
    rate: {
        type: Number,
        default: 656,
    },
    amount: {
        type: Number,
        default: 0,
    },
    processing: {
        type: Boolean,
        default: false,
    },
    currency: String,
})

const total = computed(() => {
    return (props.amount * props.rate) || 0
})

const charge = computed(() => {
    let _total = (props.amount * props.rate) || 0
    return parseFloat(Number(0.035 * _total).toFixed(4))
})

</script>