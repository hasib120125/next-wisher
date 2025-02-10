<template>
    <div class='grid justify-center mb-5'>
        <label class='bg-green-500 px-5 py-1 mb-2 font-semibold cursor-pointer text-white rounded shadow mx-auto inline-block'>
            {{ Helper.translate('Upload File') }}
            <input 
                type="file" 
                @change="async (e) => {
                    form.files = e.target.files
                    handleFileUpload()
                }"
                hidden 
                multiple 
                accept="image/*"
            />
        </label>
    </div>
    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
        {{ form.progress.percentage }}%
    </progress>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import useMedia from '@/Components/Backend/TalentDashboard/Calendar/useMedia.js'
import Helper from '@/Helper'

const { getMedia } = useMedia()
const form = useForm({
    files: null,
})

const handleFileUpload = () => {
    form.post(route('file_upload'), {
        onSuccess: () => {
            getMedia()
        }
    })
}
</script>