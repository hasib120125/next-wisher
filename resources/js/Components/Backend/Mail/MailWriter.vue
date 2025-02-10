<template>
    <textarea 
        :disabled="isExpired()" 
        placeholder="Write your message" 
        rows="5" 
        v-if="!isExpired() && $page.props.auth.user.role != 'user' && !isVideoSent(getSelectedMail)"
        v-model="form.instructions"
        class="mt-10 block w-full border-gray-300 rounded border-opacity-50 disabled:bg-gray-200 placeholder:text-gray-300" 
    />
    <div v-if="!isExpired() && $page.props.auth.user.role != 'user' && !isVideoSent(getSelectedMail)" class="flex justify-between mt-4 items-center">
        <label class="cursor-pointer flex flex-wrap gap-2 break-all" v-if="$page.props.auth.user.role == 'talent' && !isExpired()">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke="#000" stroke-width="2" d="m22 12-9 9c-6 6-15-3-9-9l9-9c4-4 10 2 6 6l-9 9c-2 2-5-1-3-3l9-9"/></svg>
            <input @input="(e) => handleSelect(e.target.files[0])" type="file" accept="video/*" class="hidden">
            {{ fileName }}
        </label>
        <div v-else></div>
        <button v-if="!isExpired()" :disabled="form.processing" @click="replayMail" class="text-white bg-red-500 font-bold px-6 py-2 rounded flex items-center gap-2">
            <Preloader v-if="form.processing" class="w-4 h-4" />
            {{ Helper.translate('Send') }}
        </button>
    </div>
</template>

<script setup>
import Preloader from '@/Components/Global/Preloader.vue';
import Helper from '@/Helper'
import useMail from '@/Pages/Backend/Mail/useMail'
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { filter, each } from 'lodash';
import { onMounted, ref } from 'vue';

const { getSelectedMail, isExpired, mailList } = useMail()

const fileName = ref('')

const form = useForm({
    instructions: '',
    attachment: null,
    oldMail: {
        ...getSelectedMail.value
    }
})

const isVideoSent = (selectMail) => {
    let isSent = false;
    each(mailList.value, item => {
        if (item.talent_earning_id == selectMail.talent_earning_id && item.attachment) {
            isSent = true;
        }
    })
    return isSent;
}

const handleSelect = (file) => {
    form.attachment = file
    fileName.value = file.name
}

const replayMail = () => { 
    form.post(route('mail.replay'), {
        onSuccess(){
            mailList.value = usePage().props.value.emails
        }
    })
}

</script>

<style lang="scss" scoped>

</style>