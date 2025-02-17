<template>
    <div class="min-w-[500px] rounded p-10 bg-white">
            <h3 class="mb-4 font-bold">{{ Helper.translate('Edit Text') }}</h3>
            <div class="mt-5">
                <label class="w-full mb-6 block relative">
                    <h1 class="text-sm font-bold">English</h1>
                    <CInput 
                        type="text" 
                        placeholder="Enter text"
                        v-model="form.english"
                    />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.english, true) }}</span>
                </label>
                <label class="w-full mb-6 block relative">
                    <h1 class="text-sm font-bold">Français</h1>
                    <CInput 
                        type="text" 
                        placeholder="Enter text"
                        v-model="form.french"
                    />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.french, true) }}</span>
                </label>
                <label class="w-full mb-6 block relative">
                    <h1 class="text-sm font-bold">Portugues</h1>
                    <CInput 
                        type="text" 
                        placeholder="Enter text"
                        v-model="form.portugues"
                    />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.portugues, true) }}</span>
                </label>
                <label class="w-full mb-6 block relative">
                    <h1 class="text-sm font-bold">Español</h1>
                    <CInput 
                        type="text" 
                        placeholder="Enter text"
                        v-model="form.spanish"
                    />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.spanish, true) }}</span>
                </label>
            </div>
            <div class="mt-5 flex gap-5 justify-center">
                <button @click="$emit('close')" class="px-3 py-1 rounded bg-red-500 font-semibold text-white shadow-lg">
                    {{ Helper.translate('Cancel') }}
                </button>
                <button @click="handleSave" class="px-3 py-1 rounded bg-green-500 font-semibold text-white shadow-lg">
                    {{ Helper.translate('Update') }}
                </button>
            </div>
        </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import { isEmpty } from 'lodash'
import CInput from '@/Components/Global/CInput.vue'
import Helper from "@/Helper"
import { onMounted } from "vue"

const props = defineProps({
    language: Object,
});

const form = useForm({
    id: null,
    english: null,
    french: null,
    portugues: null,
    spanish: null,
    settings: null,
});

onMounted(()=> {
    console.log(props.language);
    form.id = props.language.id
    form.english = props.language.english
    form.french = props.language.french
    form.portugues = props.language.portugues
    form.spanish = props.language.spanish
    form.settings = props.language.settings
});
const emit = defineEmits(['close']);
const handleSave = () => {
    form.post(route('admin.saveLanguage'), {
        onSuccess(e) {
            if (!isEmpty(e.props.errors)) return;
            form.reset();
            emit('close');
        }
    });
}
</script>