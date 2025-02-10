<template>
    <form  @submit.prevent="submit" class="block">
        <div>
            <button @click="activeComponent='All'" class="p-1 px-2 pr-4 rounded bg-gray-500 text-white inline-flex items-center w-fit gap-2 mb-4">
                <AngleLeftIcon class="w-5 h-5" />
                {{ Helper.translate('Back') }}
            </button>
        </div>
        <div class="max-w-[500px]">
            <h1 class="text-lg font-semibold mb-4">{{ Helper.translate('Edit Country') }}</h1>
            <div class="relative mb-6">
                <CInput type="text" v-model="form.name" :placeholder="Helper.translate('Country Name')" />
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
            </div>
            <div class="relative">
                <CSelect :options="countryStatus" v-model="form.status"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
            </div>
            <button class="bg-green-500 text-white ml-auto block font-semibold px-4 py-2 rounded mt-2">{{ Helper.translate('Update') }}</button>
        </div>
    </form>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import useCountry from '@/Pages/Backend/AdminDashboard/useCountry.js'
import { useForm } from '@inertiajs/inertia-vue3'
import { isEmpty } from 'lodash'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import Helper from '@/Helper'
import { onMounted } from 'vue'

const { activeComponent, countryStatus, selectedCountry } = useCountry()
const form = useForm({
    id: null,
    country_code: null,
    phone_code: null,
    name: null,
    status: null,
})

onMounted(()=>{
    form.id = selectedCountry.value.id;
    form.country_code = selectedCountry.value.country_code;
    form.phone_code = selectedCountry.value.phone_code;
    form.name = selectedCountry.value.name;
    form.status = selectedCountry.value.status; 
});

const submit = () => {
    form.post(route('admin.country.edit', form.id), {
        onFinish: () => {
            if(isEmpty(form.errors)){
                form.reset('country_code', 'phone_code', 'name', 'status')
                activeComponent.value = 'All'
            }
        }
    })
}
</script>