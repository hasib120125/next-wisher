<template>
    <form  @submit.prevent="submit" class="block">
        <button @click="activeComponent='All'" class="p-1 px-2 pr-4 rounded bg-gray-500 text-white inline-flex items-center w-fit gap-2 mb-4">
            <AngleLeftIcon class="w-5 h-5" />
            {{ Helper.translate('Back') }}
        </button>
        <div class="max-w-[500px]">
            <h1 class="text-lg font-semibold mb-4">{{ Helper.translate('Add Country') }}</h1>
            <div class="relative mb-6">
                <CInput type="text" v-model="form.name" :placeholder="Helper.translate('Country Name')" />
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
            </div>
            <div class="relative">
                <CSelect :options="countryStatus" v-model="form.status"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
            </div>
            <button class="bg-green-500 text-white ml-auto block font-semibold px-4 py-2 rounded mt-2">{{ Helper.translate('Save') }}</button>
        </div>
    </form>
</template>

<script setup>
import { isEmpty } from 'lodash'
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import useCountry from '@/Pages/Backend/AdminDashboard/useCountry.js'
import { useForm } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'

const { activeComponent, countryStatus } = useCountry()

const form = useForm({
    country_code: '',
    phone_code: '',
    name: '',
    status: countryStatus.value[0].key
})

const submit = () => {
    form.post(route('admin.country'), {
        onFinish: () => {
            if(isEmpty(form.errors)){
                form.reset('country_code', 'phone_code', 'name', 'status')
                activeComponent.value = 'All'
            }
        }
    })
}
</script>