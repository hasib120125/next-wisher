<template>
    <label class="relative block" :class="disabled && 'pointer-events-none opacity-50 bg-gray-50'">
        <span v-if="modelValue == null || !String(modelValue).length" class="absolute left-0 right-0 top-3 pointer-events-none opacity-40 whitespace-nowrap block w-full truncate">
            {{ Helper.translate(placeholder) }}
        </span>
        <input @input="updateValue" :value="modelValue" :type="type" class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow bg-transparent">
        <span class="customBorder"></span>
        <div 
            v-if="characterLimit"
            class="limit block text-right mt-1 italic text-xs"
            :class="(writtenCharacter >= characterLimit) && 'text-red-500 font-bold text-md'"
        >
           {{ writtenCharacter }}/{{ characterLimit }}
        </div>
    </label>
</template>

<script setup>
    import { isEmpty } from 'lodash'
    import { ref } from 'vue'
    import Helper from '@/Helper'
    
    const props = defineProps({
        type: String,
        placeholder: String,
        characterLimit: Number, 
        modelValue: {
            type: [String, Number],
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        }
    })

    const writtenCharacter = ref(0)
    const emit = defineEmits()
    const updateValue = (e) => {
        let value = e.target.value
        writtenCharacter.value = value.length
        if(value.length >= props.characterLimit){
            e.target.value = value.slice(0, (props.characterLimit - 1))
        }
        emit('update:modelValue', value)
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