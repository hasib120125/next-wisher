<template>
    <form @submit.prevent="submit" class="block">
        <button @click="activeComponent='All'" class="p-1 px-2 pr-4 rounded bg-gray-500 text-white inline-flex items-center w-fit gap-2 mb-4">
            <AngleLeftIcon class="w-5 h-5" />
            {{ Helper.translate('Back') }}
        </button>
        <div class="max-w-[500px]">
            <h1 class="text-lg font-semibold mb-4">{{ Helper.translate('Edit Occasion') }}</h1>
            <div class="relative mb-6">
                <CInput type="text" v-model="form.name" placeholder="Occasion Name" />
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
            </div>
            <div class="relative">
                <CSelect :options="occasionStatus" v-model="form.status"/>
                <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.status, true) }}</span>
            </div>
            <button
                class="bg-green-500 text-white ml-auto block font-semibold px-4 py-2 rounded mt-2"
                :class="!!form.processing && 'pointer-events-none opacity-70'"
            >
                {{ Helper.translate('Update') }}
            </button>
        </div>
    </form>
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import Checkbox from '@/Components/Global/Checkbox.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import useOccasion from '@/Pages/Backend/AdminDashboard/useOccasion.js'
import { useForm } from '@inertiajs/inertia-vue3'
import { isEmpty, filter, map } from 'lodash'
import Helper from '@/Helper'
import { onMounted } from '@vue/runtime-core'
import { ref } from 'vue'

const { activeComponent, selectedOccasion, occasionStatus } = useOccasion()

const form = useForm({
    id: selectedOccasion.value.id,
    name: selectedOccasion.value.name,
    status: selectedOccasion.value.status, 
})

const submit = () => {
    form.post(route('admin.ocassion.edit', form.id), {
        onFinish: () => {
            if(isEmpty(form.errors)){
                selectedOccasion.value = {}
                activeComponent.value = 'All'
            }
        }
    })
}
</script>

<style lang="scss" scoped>

</style>