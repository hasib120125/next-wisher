<template>
    <div    
        class="flex flex-col space-y-4" 
        v-if="get($page.props, 'auth.user.role') == 'user'"
    >
        <div class="">
            <h3 class="font-semibold text-lg mb-4">{{ Helper.translate('Personalized video') }}</h3>
            <div class="bg-gray-100 text-center space-y-4 p-4 rounded-lg">
                <p>
                    {{ Helper.translate("Book me for a personalized video for any occasion and let's make it memorable.") }}
                    <div v-if="!($page.props.wish && +getWishAmount($page.props.wish) > 0)" class="text-center font-bold">
                        {{ Helper.translate('Unavailable now') }}
                    </div>
                </p>
                <span v-if="Number(get($page.props, 'wish.amount')) > 0" class="block w-fit px-4 py-1 bg-red-500 rounded-2xl mx-auto text-white font-bold">
                    {{ Helper.priceFormate(get($page.props, 'wish.amount') || 0) }}
                </span>
            </div>
        </div>
        <!-- <Link v-if="$page.props.mylife" :href="route('payment.subscribe',{talentId: talent.id})" class="px-4 py-2 rounded text-xl bg-black text-white font-bold text-center">
            {{ Helper.translate('My Life') }}
        </Link> -->
        <Link v-if="$page.props.wish && +getWishAmount($page.props.wish) > 0" :href="route('payment.info', {talentId: talent.id, name: String(talent.name).replace(' ', '-')})" class="px-4 py-2 text-xl bg-red-600 rounded-2xl text-white font-bold text-center">
            {{ Helper.translate('Book') }}
        </Link>
        <Link v-if="$page.props.tips && +getWishAmount($page.props.wish) > 0" :href="route('payment.tips.amount',{talentId: talent.id, name: String(talent.name).replace(' ', '-')})" class="px-4 py-2 text-xl bg-sky-500 rounded-2xl text-white font-bold text-center">
            {{ Helper.translate('Send tip') }}
        </Link>
    </div>
    <div class="flex flex-col space-y-4" v-else>
        <div class="">
            <h3 class="font-semibold text-lg mb-4">{{ Helper.translate('Personalized video') }}</h3>
            <div class="bg-gray-100 text-center space-y-4 p-4 rounded-lg">
                <p>
                    {{ Helper.translate("Book me for a personalized video for any occasion and let's make it memorable.") }}
                    <div v-if="!($page.props.wish && +getWishAmount($page.props.wish) > 0)" class="text-center font-bold">
                        {{ Helper.translate('Unavailable now') }}
                    </div>
                </p>
                <span v-if="Number(get($page.props, 'wish.amount')) > 0" class="block w-fit px-4 py-1 bg-red-500 rounded-2xl mx-auto text-white font-bold">
                    {{ Helper.priceFormate(get($page.props, 'wish.amount') || 0) }}
                </span>
            </div>
        </div>
        <div v-if="$page.props.wish" class="px-4 py-2 select-none rounded-2xl text-xl bg-red-600 text-white font-bold text-center">
            {{ Helper.translate('Book') }}
        </div>
        <!-- <div v-if="$page.props.mylife" class="px-4 py-2 rounded text-xl bg-black text-white font-bold text-center">
            {{ Helper.translate('My Life') }}
        </div> -->
        <div v-if="$page.props.wish && $page.props.tips" class="px-4 py-2 rounded-2xl text-xl bg-sky-500 text-white font-bold text-center">
            {{ Helper.translate('Send tip') }}
        </div>
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
    import { get } from 'lodash'
    import { Link } from '@inertiajs/inertia-vue3'


    const props = defineProps({
        talent: Object,
    })

    const getWishAmount = (wish) => {
        return Number(get(wish,'amount')) || 0
    }
    
</script>