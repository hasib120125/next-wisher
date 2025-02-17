<template>
    <h1 class="text-lg my-2 mb-4 flex items-center gap-6">{{ Helper.translate('Edit Page') }}</h1>
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
    <div class="flex gap-5 justify-end">
        <button @click="$emit('canceledit')" class="bg-red-500 text-white block font-semibold px-4 py-2 rounded mt-2">
            {{ Helper.translate('Cancel') }}
        </button>
        <button @click="handleSave(selectedItem.id)" class="bg-green-500 text-white block font-semibold px-4 py-2 rounded mt-2">
            {{ Helper.translate('Update') }}
        </button>
    </div>
</template>

<script setup>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { useForm } from '@inertiajs/inertia-vue3'
import CInput from '@/Components/Global/CInput.vue'
import { onMounted, defineEmits } from 'vue'
import Helper from '@/Helper'
import { isEmpty } from 'lodash'
import CSelect from '@/Components/Global/CSelect.vue'

const props = defineProps({
    selectedItem: Object,
});


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

onMounted(()=>{
    let selectedItem = props.selectedItem
    form.question = selectedItem.question
    form.answer = selectedItem.answer
})

const emit = defineEmits(['canceledit']);

const handleSave = (id) => {
    form.post(route('admin.updateFaq', id), {
        onSuccess(e) {
            if (!isEmpty(e.props.errors)) return;
            form.reset()
            emit('canceledit')
        }
    })
}


</script>