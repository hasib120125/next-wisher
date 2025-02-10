<template>
    <div class="max-w-[800px] bg-white relative mx-auto">
        <div class="px-4 py-4 border-b font-bold">{{ Helper.translate('Send Reply') }}</div>
        <div class="p-4">
            <h1 class="font-semibold mb-1">
                {{ Helper.translate('Name') }}: {{ Helper.translate(item.name, true) }}
            </h1>
            <h2 class="font-semibold mb-1">
                {{ Helper.translate('Email') }}: {{ item.email }}
            </h2>
            <h2 class="font-semibold mb-3 border-b pb-3">{{ Helper.translate('Subject') }}: {{ item.subject }}</h2>

            <h2 class="font-semibold mb-1">{{ Helper.translate('Message') }}: </h2>
            <p class="">
                {{ item.message }}
            </p>
        </div>
        <div class="p-4">
            <div class="relative">
                <textarea v-model="form.replay" placeholder="Write Reply" class="border border-gray-400 block w-full row-4 border-opacity-20 p-4" />
                <span v-if="form.errors.replay" class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.replay, true) }}
                </span>
            </div>
            <div class="flex justify-end gap-4 mt-4">
                <button 
                    @click="$emit('close')" 
                    class="px-4 py-1 bg-red-500 rounded block font-bold text-white"
                >
                    {{ Helper.translate('Cancel') }}
                </button>
                <button 
                    @click="handleReplay(item)" 
                    :disabled="form.processing" 
                    class="px-4 py-1 bg-green-500 rounded font-bold text-white flex items-center"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ Helper.translate('Reply') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper.js'
import { useForm } from '@inertiajs/inertia-vue3'
import { isEmpty } from 'lodash';

defineProps({
    item: Object
})

const form = useForm({
    replay: null
})

const emit = defineEmits(['close'])

const handleReplay = (item) => {
    form.post(route('admin.replayContact', item.id), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset()
                emit('close')
            }
        }
    })
}

</script>