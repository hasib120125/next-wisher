<template>
    <label class="relative block">
        <span v-if="!modelValue && isEmpty(modelValue)" class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
        <select @change="$emit('change')" v-model="getModelValue" :type="type" class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow">
            <template v-for="(option, index) in options" :key="index">
                <option value="" v-if="getKey(option) != 0 && !getKey(option)" selected>{{ Helper.translate(getValue(option), true) }}</option>
                <option v-else :value="getKey(option)">{{ Helper.translate(getValue(option), true) }}</option>
            </template>
        </select>
        <span class="customBorder"></span>
    </label>
</template>

<script setup>
    import { isEmpty } from 'lodash'
    import { computed } from 'vue'
    import Helper from '@/Helper'

    const props = defineProps({
        options: Array,
        modelValue: [String, Number, Boolean]
    })


    const getModelValue = computed({
        get(){
            return props.modelValue
        },
        set(value){
            emit('update:modelValue', value)
        }
    })
    const emit = defineEmits()

    const getKey = (option) => {
        if(typeof option == 'string'){
            return option
        }
        return option.key
    }
    const getValue = (option) => {
        if(typeof option == 'string'){
            return option
        }

        return option.value
    }
</script>

<style scoped>
.customBorder{
    display: block;
    border-bottom: 1px solid #0002;
    position: relative;
}
.customBorder::before{
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0px;
    border-bottom: 1px solid red;
    transition: 0.3s ease-in-out;
}

.myInput:focus + .customBorder::before{
    width: 100%;
}
.remove-shadow{
    box-shadow: none;
}
</style>