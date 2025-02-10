<template>
    <div>
        <h2 class="font-black text-xl mb-2">
            {{ Helper.translate('Biography') }}
        </h2>
        <div class="relative">
            <CTextarea
                v-model="localBio"
                rows="6"
                characterLimit="150"
                :isDefault="true"
                inputClass="px-4 border-gray-300 rounded"
            />
            <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.bio, true) }}</span>
        </div>
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
    import { computed } from 'vue'
    import CTextarea from '@/Components/Global/CTextarea.vue'

    const props = defineProps({
        modelValue: String,
        form: Object
    })

    const emit = defineEmits()
    const localBio = computed({
        set(value){
            emit('update:modelValue', value)
        },
        get(){
            return props.modelValue
        }
    })
</script>