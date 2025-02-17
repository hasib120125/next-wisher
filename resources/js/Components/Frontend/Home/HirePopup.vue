<template>
    <form @submit.prevent="handleSubmit" class="bg-white w-full md:min-w-[800px] rounded pb-5 pt-5">
        <div class="text-xl font-bold py-2 px-5">{{ Helper.translate('Contact Form') }}</div>
        <div class="py-2 px-5 grid grid-cols-2 gap-5">
            <label class="grid gap-0.5">
                <div class="flex items-center">
                    {{ Helper.translate('Name') }}
                    <span class="text-red-500">*</span>
                </div>
                <input 
                    v-model="form.name"
                    type="text" 
                    :placeholder="Helper.translate('Name')"
                    required
                />
                <div v-if="form.errors.name" class="text-red-500 text-xs">
                    {{ form.errors.name }}
                </div>
            </label>
            <label class="grid gap-0.5">
                <div class="flex items-center">
                    {{ Helper.translate('Phone number') }}
                    <span class="text-red-500">*</span>
                </div>
                <input 
                    v-model="form.phone" 
                    type="text" 
                    :placeholder="Helper.translate('Phone number')"
                    required
                />
                <div v-if="form.errors.phone" class="text-red-500 text-xs">
                    {{ form.errors.phone }}
                </div>
            </label>
            <label class="grid gap-0.5">
                <div class="flex items-center">
                    {{ Helper.translate('Email') }}
                    <span class="text-red-500">*</span>
                </div>
                <input 
                    type="email" 
                    v-model="form.email"
                    :placeholder="Helper.translate('Email')"
                />
                <div v-if="form.errors.email" class="text-red-500 text-xs">
                    {{ form.errors.email }}
                </div>
            </label>
            <label class="grid gap-0.5">
                <span>{{ Helper.translate('Company name') }}</span>
                <input 
                    type="text" 
                    v-model="form.company_name"
                    :placeholder="Helper.translate('Company name')"
                />
            </label>
            <label class="grid gap-0.5">
                <div class="flex items-center">
                    {{ Helper.translate('Celebrity name') }}
                    <span class="text-red-500">*</span>
                </div>
                <input 
                    type="text" 
                    v-model="form.celebrity_name"
                    :placeholder="Helper.translate('Celebrity name')"
                    required
                />
                <div v-if="form.errors.celebrity_name" class="text-red-500 text-xs">
                    {{ form.errors.celebrity_name }}
                </div>
            </label>
            <label class="grid gap-0.5">
                <span>{{ Helper.translate('Budget (in$)') }}</span>
                <input 
                    type="number" 
                    v-model="form.budget"
                    :placeholder="Helper.translate('Budget (in$)')"
                />
            </label>
            <label class="grid gap-0.5">
                <span>{{ Helper.translate('Event date') }}</span>
                <input 
                    type="date" 
                    v-model="form.event_date"
                    :placeholder="Helper.translate('Event date')"
                />
            </label>
            <label class="grid gap-0.5">
                <span>{{ Helper.translate('Event location (City and country)') }}</span>
                <input 
                    type="text" 
                    v-model="form.event_location"
                    :placeholder="Helper.translate('Event location (City and country)')"
                />
            </label>
            <label class="grid gap-0.5 col-span-2">
                <span>{{ Helper.translate('Comments - Provide me more details about your request') }}</span>
                <textarea v-model="form.comment" rows="2" :placeholder="Helper.translate('Comments - Provide mode details about request')"></textarea>
            </label>
        </div>
        <div class="px-5 mt-3 flex items-center justify-between">
            <div class="flex-1">
                <span class="text-red-500">*</span> 
                {{ Helper.translate('Mandatory fields') }}
            </div>
            <button class="py-1 px-5 rounded bg-[#000547] text-white flex items-center gap-3">
                <Preloader v-if="form.processing" class="w-5 h-5" />
                {{ Helper.translate('Submit Form') }}
            </button>
        </div>
    </form>        d
</template>

<script setup>  
import { ref } from "vue";
import { get, isEmpty } from 'lodash'
import Helper from "@/Helper";
import Preloader from "@/Components/Global/Preloader.vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    item: {
        type: Object,
        default: {}
    }
})

const form = useForm({
    user_id: get(props, 'item.id'),
    name: '',
    phone: '',
    email: '',
    company_name: '',
    celebrity_name: '',
    budget: '',
    event_date: '',
    event_location: '',
    comment: '',
})

const emit = defineEmits(['close'])

const handleSubmit = () => {
    form.post(route('saveHire'), {
        onFinish(e) {
            if(isEmpty(form.errors)) {
                form.reset()
                emit('close')
            }
        }
    })
}


</script>