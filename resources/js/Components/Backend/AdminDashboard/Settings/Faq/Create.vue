<template>
    <h1 class="text-lg my-2 mb-4 flex items-center gap-6">{{ Helper.translate('Create Faq') }}</h1>
    <div class="relative mb-6">
        <CInput type="text" v-model="form.question" :placeholder="Helper.translate('Question')" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.question, true) }}</span>
    </div>
    <div class="relative mb-6">
        <CInput type="text" v-model="form.answer" :placeholder="Helper.translate('Answer')" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.answer, true) }}</span>
    </div> 
    <div class="relative mb-6">
        <CSelect
            class="w-full"
            :options="faqTypes"
            v-model="form.type"
        />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.type, true) }}</span>
    </div> 
    <button @click="handleSave" class="bg-green-500 text-white ml-auto block font-semibold px-4 py-2 rounded mt-2"> {{ Helper.translate('Save') }} </button>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import CInput from '@/Components/Global/CInput.vue'
import Helper from '@/Helper'
import { isEmpty } from 'lodash'
import CSelect from '@/Components/Global/CSelect.vue';



const form = useForm({
    question: null,
    answer: null,
    type: 'user',
    settings: null,
})

const faqTypes = [
    {
        key: 'users',
        value: 'Users'
    },
    {
        key: 'talents',
        value: 'Talents'
    },
]

const handleSave = () => {
    form.post(route('admin.saveFaq'), {
        onSuccess(e) {
            if (!isEmpty(e.props.errors)) return;
            form.reset()
        }
    })
}

</script>