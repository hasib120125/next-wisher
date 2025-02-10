<template>
    <h1 class="text-lg my-2 mb-4 flex items-center gap-6">{{ Helper.translate('Edit Page') }}</h1>
    <div class="relative mb-6">
        <CInput type="text" v-model="form.title" :placeholder="Helper.translate('Title')" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.title, true) }}</span>
    </div> 
    <div class="relative mb-6">
        <CSelect v-model="form.type" :options="pageTypes" />
        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.type, true) }}</span>
    </div>
    <div class="block mt-8">
        <span>English</span>
        <ckeditor class="mb-4" :editor="ClassicEditor" v-model="form.description" :config="editorConfig"></ckeditor>
    </div>
    <div class="block mt-8">
        <span>Français</span>
        <ckeditor class="mb-4" :editor="ClassicEditor" v-model="form.french" :config="editorConfig"></ckeditor>
    </div>
    <div class="block mt-8">
        <span>Portugues</span>
        <ckeditor class="mb-4" :editor="ClassicEditor" v-model="form.portugues" :config="editorConfig"></ckeditor>
    </div>
    <div class="block mt-8">
        <span>Español</span>
        <ckeditor class="mb-4" :editor="ClassicEditor" v-model="form.spanish" :config="editorConfig"></ckeditor>
    </div>
    <div class="flex gap-5 justify-end">
        <button @click="$emit('canceledit')" class="bg-red-500 text-white block font-semibold px-4 py-2 rounded mt-2">
            {{ Helper.translate('Cancel') }}
        </button>
        <button @click="handleSave" class="bg-green-500 text-white block font-semibold px-4 py-2 rounded mt-2">
            {{ Helper.translate('Update') }}
        </button>
    </div>
</template>

<script setup>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { useForm } from '@inertiajs/inertia-vue3'
import CInput from '@/Components/Global/CInput.vue'
import { onMounted, ref, defineEmits } from 'vue'
import Helper from '@/Helper'
import { isEmpty } from 'lodash'
import CSelect from '@/Components/Global/CSelect.vue'

const props = defineProps({
    selectedPage: Object,
});

const editorConfig = ref({
    toolbar: {
        items: [
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            'undo',
            'redo'
        ]
    }
})

const pageTypes = [
    {
        key: null,
        value: Helper.translate('-Select page type-')
    },
    {
        key: 'privacy-policy',
        value: Helper.translate('Privacy policy')
    },
    {
        key: 'terms-of-service',
        value: Helper.translate('Terms of service')
    },
    {
        key: 'user-guide',
        value: Helper.translate('User guide')
    },
    {
        key: 'talent-guide',
        value: Helper.translate('Talent guide')
    },
    {
        key: 'calender-guide',
        value: Helper.translate('Calendar guide')
    },
]

const form = useForm({
    id: null,
    title: null,
    slug: null, 
    type: null, 
    settings: null,
    description: '',
    french: '',
    portugues: '',
    spanish: '',
})

onMounted(()=>{
    let selectedPage = props.selectedPage;
    form.id = selectedPage.id;
    form.title = selectedPage.title;
    form.slug = selectedPage.slug;
    form.type = selectedPage.type;
    form.email = selectedPage.email;
    form.settings = selectedPage.settings;
    form.description = selectedPage.description;
    form.french = selectedPage.french;
    form.portugues = selectedPage.portugues;
    form.spanish = selectedPage.spanish;
});

const emit = defineEmits(['canceledit']);

const handleSave = () => {
    form.post(route('admin.savePage'), {
        onSuccess(e) {
            if (!isEmpty(e.props.errors)) return;
            form.reset()
            emit('canceledit');
            let editorContent = document.querySelector('.ck-editor__main>.ck-content');
            if(editorContent) editorContent.innerHTML = '';
        }
    })
}


</script>