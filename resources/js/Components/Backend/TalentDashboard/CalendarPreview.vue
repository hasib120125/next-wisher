<template>
    <template v-if="!isEmpty(getCalendar)">
        <span class="absolute text-white drop-shadow-md text-xl font-bold bg-sky-500 rounded top-1 right-1 py-2 px-4">
            {{ Helper.priceFormate(getCalendar.price) }}
        </span>
        <img :src="getImage(getCalendar)" alt="" class="h-full object-cover customRatio">
        <h2 v-if="getTitle(getCalendar)" class="absolute bottom-0 p-4 bg-white w-full bg-opacity-50 backdrop-blur-md font-semibold truncate">
            {{ Helper.translate(getTitle(getCalendar), true) }}
        </h2>
    </template>
    <div v-else class="flex justify-center items-center  h-full text-xl font-bold">
        {{ Helper.translate('No calendar found') }}
    </div>
    <div v-if="get($page.props, 'auth.user.role') == 'user'" class="absolute z-10 w-full h-full top-0 left-0 cursor-pointer" @click="handleClick(getCalendar)"></div>
</template>

<script setup>
import Helper from '@/Helper'
import { Inertia } from '@inertiajs/inertia';
import { isEmpty, find, size, get } from 'lodash';
import Swal from 'sweetalert2';
import { computed } from 'vue';

const props = defineProps({
    calendars: Object
})

const getCalendar = computed(() => {
    return props.calendars.find(item => item.is_salable)
})

const getImage = (calender) => {
    return calender.settings[0].path;
}

const getTitle = (calender) => {
    return calender.settings[0].text.title;
}

const isPurchased = (earnings, obj=false) => {
    let found = find(earnings, item => +item.is_expire == 0);
    return obj ? found : isEmpty(found)
}

const handleClick = (getCalendar) => {
    if (!getCalendar) {
        return;
    }
    if (!isPurchased(getCalendar.earnings)) {
        return
    }
    let price = Helper.priceFormate(
            Helper.getMaintenanceCharge(
                getCalendar.price
            ) + Number(getCalendar.price)
        )
    Helper.confirm(`
            ${Helper.translate('You will be charged')} ${price} </br> 
            ${Helper.translate(`${Helper.priceFormate(getCalendar.price)} + 
            ${Helper.priceFormate(Helper.getMaintenanceCharge(getCalendar.price))} ${Helper.translate('Service Fee')}`)}
        `, ()=> {
            Inertia.get(route('payment.calenderPurchase', {
                talentId: getCalendar.user_id,
                calenderId: getCalendar.id
            }))
        })
}
</script>