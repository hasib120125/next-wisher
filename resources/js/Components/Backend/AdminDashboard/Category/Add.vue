<template>
    <form @submit.prevent="submit" class="block">
        <button @click="activeComponent='All'" class="p-1 px-2 pr-4 rounded bg-gray-500 text-white inline-flex items-center w-fit gap-2 mb-4">
            <AngleLeftIcon class="w-5 h-5" />
            {{ Helper.translate('Back') }}
        </button>
        <div class="max-w-[500px]">
            <h1 class="text-lg font-semibold mb-4">{{ Helper.translate('Add Category') }}</h1>
            <div class="relative mb-6">
                <CInput type="text" v-model="form.name" :placeholder="Helper.translate('Category Name')" />
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
            </div>
            <label class="flex items-center gap-1">
                <input type="checkbox" v-model="isChild">
                {{ Helper.translate('Make child category') }}
            </label>
            <div class="relative" v-if="isChild">
                <CSelect :options="categories" v-model="form.parent_id"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
            </div>
            <div class="relative">
                <CSelect :options="categoryStatus" v-model="form.status"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
            </div>
            <button
                class="bg-green-500 text-white ml-auto block font-semibold px-4 py-2 rounded mt-2"
                :class="!!form.processing && 'pointer-events-none opacity-70'"
            >
                {{ Helper.translate('Save') }}
            </button>
        </div>
    </form>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import useCategory from '@/Pages/Backend/AdminDashboard/useCategory.js'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { isEmpty, filter, map } from 'lodash'
import { ref, onMounted } from 'vue'
import Helper from '@/Helper'

const { activeComponent, categoryStatus } = useCategory()

const form = useForm({
    name: '',
    status: categoryStatus.value[0].key,
    parent_id: null
})

const isChild = ref(false);

const parentCategories = ref([]);
const categories = ref([]);
const page = usePage();

onMounted(()=>{
    parentCategories.value = filter(page.props.value.categories, item => item.parent_id)
    categories.value = map(page.props.value.parents, item => {
        return {
            key: item.id,
            value: item.name
        };
    })
    categories.value.unshift({
        key: null,
        value: Helper.translate('Select parent')
    });
});

const submit = () => {
    if (!isChild.value) {
        form.parent_id = null;
    }
    form.post(route('admin.category'), {
        onFinish: () => {
            if(isEmpty(form.errors)){
                form.reset('name', 'status')
                activeComponent.value = 'All'
            }
        }
    })
}
</script>