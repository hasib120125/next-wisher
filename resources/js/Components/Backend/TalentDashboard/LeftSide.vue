<template>
    <div @click="showMessage">
        <div 
            class="grid gap-3 sticky top-24"
            :class="checkAccountStatus() && 'pointer-events-none'" 
        >
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.dashboard') ? 'bg-gray-100' : ''"
                :href="route('talent.dashboard')"
            >
                {{ Helper.translate('Guide') }}
            </Link>
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.account') ? 'bg-gray-100' : ''"
                :href="route('talent.account')"
            >
                {{ Helper.translate('Account') }}
            </Link>
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.profile.setup') ? 'bg-gray-100' : ''"
                :href="route('talent.profile.setup')"
            >
                {{ Helper.translate('Profile Setup') }}
            </Link>
            <!-- <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.myLife') ? 'bg-gray-100' : ''"
                :href="route('talent.myLife')"
            >
                {{ Helper.translate('My Life') }}
            </Link> -->
            <!-- <Link :href="route('calendar')"
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
            >
                {{ Helper.translate('Calendars') }}
            </Link> -->
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.wish.request') ? 'bg-gray-100' : ''"
                :href="route('talent.wish.request')"
            >
                {{ Helper.translate('Wish Request') }}
            </Link>
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.tips') ? 'bg-gray-100' : ''"
                :href="route('talent.tips')"
            >
                {{ Helper.translate('Tips') }}
            </Link>
            <!-- <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.analytics') ? 'bg-gray-100' : ''"
                :href="route('talent.analytics')"
            >
                {{ Helper.translate('Analytics') }}
            </Link> -->
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.payout') ? 'bg-gray-100' : ''"
                :href="route('talent.payout')"
            >
                {{ Helper.translate('Earnings') }}
            </Link>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper'
import { Link } from '@inertiajs/inertia-vue3'
import { get } from 'lodash'
import { usePage } from '@inertiajs/inertia-vue3'
import { toast } from 'vue3-toastify'

const checkAccountStatus = () => {
    const talent = usePage().props.value.talent
    const {user} = usePage().props.value.auth
    return !get(user, 'profile_image') || !get(user, 'video_path') || !get(user, 'bio')
}
const showMessage = () => {
    if(checkAccountStatus()){
        toast.error(Helper.translate('Please add a video, a picture and your biography to continue.'), {
            position: "top-right",
            autoClose: 4000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            progress: undefined,
            theme: "light",
        })
    }
}
</script>