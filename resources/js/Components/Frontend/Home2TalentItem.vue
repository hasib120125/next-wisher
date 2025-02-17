<template>
    <div class="bg-slate-900/80 border border-slate-500 relative customRatio overflow-hidden">
        <button 
            v-if="role && role == 'admin'"
            @click="$emit('onClick')"
            class="absolute top-3 right-3 bg-white/50 hover:bg-white duration-300 p-2 rounded z-20"
        >
            <EditIcon class="w-4 h-4" />
        </button>
        <div
            v-if="!isEmpty(talent)" 
            class="w-full h-full block relative cursor-pointer"
            @click="handleClick(talent, get($page.props, 'auth.user'))"
        >
            <a 
                v-if="downloadable"
                class="absolute downloadImage top-4 right-4 z-20 bg-sky-500 text-white p-2 rounded"
                :href="`${$page.props.asset}${get(talent, 'profile_image')}`"
                download
            >
                <DownloadIcon class="w-4 h-4" />
            </a>
            <span v-if="isLoadingImage" class="w-8 h-8 absolute -ml-4 -mt-4 top-1/2 left-1/2 z-20 animate-spin">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <img 
                @load="isLoadingImage=false"
                onerror="this.src='/no-image-found.webp'"
                :src="`${$page.props.asset}${get(talent, 'profile_image')}`" 
                class="w-full h-full object-cover object-center"
            />
            <div v-if="blueName" class="absolute bottom-0 w-full">
                <div v-if="getWishAmount(talent)" class="ml-2 bg-slate-800 inline-block rounded-full px-3 py-0.5 text-white mb-2">
                    {{ Helper.priceFormate(getWishAmount(talent)) }}
                </div>
                <div class="flex items-center text-[#efefef] bg-[#268AFF]">
                    <div class="px-4 py-1 truncate w-full drop-shadow font-semibold text-left">
                        {{ get(talent, 'username') }}
                    </div>
                    <slot name="rating" />
                </div>
            </div>
            <div v-else class="absolute bottom-0 p-4 w-full truncate drop-shadow text-[#efefef] font-semibold text-left">
                <p v-for="(name, index) in get(talent, 'username').split(' ')" :key="index">
                    {{ name }}
                </p>
            </div>
        </div>
        <div v-else class="w-full h-full block relative cursor-pointer">
            <img class="w-full h-full object-cover object-center" src="/no-image-found.webp" alt="">
        </div>
    </div>
</template>

<script setup>
    import { get, isEmpty, find } from 'lodash'
    import { Link } from '@inertiajs/inertia-vue3'
    import EditIcon from '@/Icons/EditIcon.vue'
    import { ref, onMounted } from 'vue'
    import DownloadIcon from '@/Icons/DownloadIcon.vue'
    import Helper from '@/Helper'
import { Inertia } from '@inertiajs/inertia'


    const isLoadingImage = ref(true)
    const props = defineProps({
        role: String,
        talent: Object,
        blueName: {
            type: Boolean,
            default: false,
        },
        ind: Number,
        downloadable: Boolean
    })

    const handleClick = (user, authUser) => {
        // if (user && authUser) {
        if (true) {
            Inertia.get(route('item.details', {
                id: user.id,
                username: `${String(user.username ?? user.name).toLowerCase().replaceAll(' ', '-')}`
            }));
        } else {
            Helper.gustClickAlert(false)
        }
    }

    const getWishAmount = (talent) => {
        let found = find(talent.talent_earning_type, item => item.type == 'wish')
        return get(found, 'amount') || 0
    }

</script>