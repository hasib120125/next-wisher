<template>
    <div>
        <div 
            class="grid gap-3 sticky top-24"
            :ref="checkAccountStatus()"
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
                v-if="get($page, 'props.auth.user.status') !== 2"
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
                :href="profileCompleteStatus ? route('talent.wish.request') : '#'"
                @click="showMessage"
                v-if="get($page, 'props.auth.user.status') !== 2"
            >
                {{ Helper.translate('Wish Request') }}
            </Link>
            <!-- <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.manager.index') ? 'bg-gray-100' : ''"
                :href="route('talent.manager.index')"
            >
                {{ Helper.translate('Manager') }}
            </Link> -->
            <Link 
                class="px-4 py-2 border font-semibold hover:bg-gray-100"
                :class="route().current('talent.tips') ? 'bg-gray-100' : ''"
                :href="profileCompleteStatus ? route('talent.tips') : '#'"
                @click="showMessage"
                v-if="get($page, 'props.auth.user.status') !== 2"
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
                :href="profileCompleteStatus ? route('talent.payout') : '#'"
                @click="showMessage"
                v-if="get($page, 'props.auth.user.status') !== 2"
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
import { Inertia } from '@inertiajs/inertia'
import { computed, onMounted } from 'vue'
import Swal from 'sweetalert2'

const profileCompleteStatus = computed(() => {
    const {user} = usePage().props.value.auth
    return !(!get(user, 'profile_image') || !get(user, 'video_path') || !get(user, 'bio'))
})
let accessible_routes = ['talent.dashboard', 'talent.account','talent.profile.setup']

const checkAccountStatus = () => {
    const talent = usePage().props.value.talent
    const {user} = usePage().props.value.auth
    let check = !get(user, 'profile_image') || !get(user, 'video_path') || !get(user, 'bio')
    // console.log(check, route().current())
    if(check && !accessible_routes.includes(route().current())) {
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
        Inertia.visit(route('talent.profile.setup'))
        return
    }

    if(get(user,'status') == 2 && !['talent.dashboard', 'talent.account'].includes(route().current())) {
        Inertia.visit(route('talent.account'))
    }

    if(!get(user,'approved_at') && route().current() == 'talent.payout') {
        Inertia.visit(route('talent.profile.setup'))
        return
    }
}

const showMessage = () => {
    if(profileCompleteStatus.value) return
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

onMounted(() => {
    if(get(usePage().props, 'value.earning_disabled')) {
        Swal.fire({
            title: Helper.translate('Info'),
            html: `
                <p>${Helper.translate('It will take up to 72 hours to review your account. Once finalized you will have access to the earnings page.')}</p>
                </br>
                <p>${Helper.translate('However, you can already receive requests from fans and fulfill them.')}</p>
            `,
            icon: 'warning',
            confirmButtonColor: '#4acb6f',
            cancelButtonColor: '#ef4444',
            confirmButtonText: Helper.translate('Ok'),
            showCancelButton: false,
            showConfirmButton: true
        })
    }
})
</script>