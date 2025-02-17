<template>
    <div v-if="user" class="flex md:gap-4 gap-2 items-center">
        <div v-if="!checkAccountStatus()" class="relative">
            <span v-if="mailCount" class="absolute bottom-[50%] left-[55%] text-[10px] font-bold min-w-4 h-4 bg-[var(--notification-badge-color)] rounded-full grid content-center p-1">
                {{ Helper.translate(mailCount, true) }}
            </span>
            <svg class="sm:w-4 sm:h-4 w-3 h-3" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M9 12a3.14 3.14 0 0 1-1.64-.462L0 7.056V14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V7.056l-7.354 4.48A3.148 3.148 0 0 1 9 12z"/>
                <path fill="currentColor" d="M17.466 5.04 9.6 9.83a1.144 1.144 0 0 1-1.2 0L.534 5.04a1.09 1.09 0 0 1-.49-1.24 1.14 1.14 0 0 1 1.09-.8h15.733a1.138 1.138 0 0 1 1.09.8 1.09 1.09 0 0 1-.49 1.24z"/>
            </svg>
        </div>
        <Link v-else :href="route('mail')" class="relative">
            <span v-if="mailCount" class="absolute bottom-[50%] left-[55%] text-[10px] font-bold min-w-4 h-4 bg-[var(--notification-badge-color)] rounded-full grid content-center p-1">
                {{ Helper.translate(mailCount, true) }}
            </span>
            <svg class="sm:w-4 sm:h-4 w-3 h-3" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M9 12a3.14 3.14 0 0 1-1.64-.462L0 7.056V14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V7.056l-7.354 4.48A3.148 3.148 0 0 1 9 12z"/>
                <path fill="currentColor" d="M17.466 5.04 9.6 9.83a1.144 1.144 0 0 1-1.2 0L.534 5.04a1.09 1.09 0 0 1-.49-1.24 1.14 1.14 0 0 1 1.09-.8h15.733a1.138 1.138 0 0 1 1.09.8 1.09 1.09 0 0 1-.49 1.24z"/>
            </svg>
        </Link>
        <!-- <button class="relative group" v-if="get($page.props, 'auth.user.role') == 'user'">
            <span v-if="size(notifications)" class="absolute bottom-[50%] left-[55%] md:text-[10px] text-[8px] font-bold w-full md:min-w-4 md:h-4  min-w-[14px] h-[14px] bg-[var(--notification-badge-color)] rounded-full grid content-center p-1">
                {{ Helper.translate(size(notifications), true) }}
            </span>
            <svg class="sm:w-5 sm:h-5 w-4 h-4" fill="currentColor" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 8a6 6 0 1 1 12 0v2.917c0 .703.228 1.387.65 1.95L20.7 15.6a1.5 1.5 0 0 1-1.2 2.4h-15a1.5 1.5 0 0 1-1.2-2.4l2.05-2.733a3.25 3.25 0 0 0 .65-1.95V8zm6 13.5A3.502 3.502 0 0 1 8.645 19h6.71A3.502 3.502 0 0 1 12 21.5z"/></svg>
            <div class="scale-0 group-hover:scale-100 transition-transform duration-75 origin-top-right absolute bg-white w-[300px] z-50 right-0 top-[calc(100%+0px)] text-black shadow-xl max-h-[400px] overflow-y-auto">
                <div 
                    v-for="(item, index) in notifications"
                    :key="index"
                    @click="handleNotificationClick(item)"
                    class="flex py-2 px-2 gap-2 hover:bg-slate-100 select-none"
                >
                    <div class="w-7 h-7 bg-slate-700 text-white rounded-full flex items-center justify-center">
                        {{ index+1 }}
                    </div>
                    <div class="divide-y px-2 text-left flex-1">
                        <h2 class="font-semibold text-black">{{ Helper.translate(item.title) }}</h2>
                        <p class="text-slate-600 text-sm">{{ get(item.talent, 'username') }} {{ Helper.translate(item.details) }}. <span class="font font-semibold">View</span></p>
                        <p class="text-slate-600 text-sm">{{ Helper.dateFormate(item.created_at) }}</p>
                    </div>
                </div>
            </div>
        </button> -->
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { computed, onMounted, ref, watchEffect } from 'vue'
import { get, size } from 'lodash'
import { Link } from '@inertiajs/inertia-vue3'
import useMail from '@/Pages/Backend/Mail/useMail'
import axios from 'axios'
import Helper from '@/Helper'

const notifications = ref([])

const { mailList, mailCount, getMailCount } = useMail()

const checkAccountStatus = () => {
    // const talent = usePage().props.value.talent
    const { user } = usePage().props.value.auth
    if (get(user, 'role') == 'user') return true;
    return !(!get(user, 'profile_image') || !get(user, 'video_path'))
}

onMounted(()=> {
    if (get(usePage().props.value,'auth.user')) {
        axios.get(route('getNotification'))
            .then(res => {
                notifications.value = res.data
            });
    }
})

watchEffect(() => {
    getMailCount()
})

const makeSlug = (name) => {
    if (name) {
        return String(name).replace(' ', '-')
    }
    return '';
}

const handleNotificationClick = (item) => {
    let url = '#';
    if (item.title == 'New video posted.') {
        url = `${usePage().props.value.ziggy.url}/my-life/${item.talent_id}/subscribe`
    } else {
        url = `${usePage().props.value.ziggy.url}/talents/${item.talent_id}/${makeSlug(get(item.talent, 'name'))}`
    }
    axios.post(route('seenNotification', {id: item.id}))
        .then(res => {
            location.href = url
        })
}

const user = computed(() => {
    if(usePage().props.value.auth.user){
        const { role, name } = get(usePage().props.value, 'auth.user')
        return { role, name }
    }

    return null
})

const getInboxUnseenMailCount = computed(() => {
    let mailData = []
    if(user.value.role == 'talent'){
        mailData = mailList.value.filter(item => (item.role == 'user') && !item.seen)
        console.log(mailList.value);
    }
    if(user.value.role == 'user'){
        mailData = mailList.value.filter(item => (item.role == 'talent') && !item.seen)
    }

    return mailData.length
})
</script>

<style lang="scss" scoped>

</style>