<template>
    <Head title="Tips" />
    <Master>
        <div class="px-4 min-h-[602px] h-full">
            <div class="container mx-auto py-5">
                <div class="flex justify-center items-center relative">
                    <button @click="back" class="absolute left-0">
                        <AngleLeftIcon />
                    </button>
                    <MemberCard
                        class="capitalize"
                        :name="$page.props.talent.name"
                        :designation="getCatAndSubCat($page.props.talent)"
                    />
                </div>
                <div class="flex flex-col items-center justify-center gap-4 mt-10">
                    <!-- <h1 class="text-xl font-semibold">{{ Helper.translate(`Tip Payment min ${Helper.priceFormate(minAmount)}`) }}</h1> -->
                    <h1 class="text-xl font-semibold">{{ Helper.translate(`Send tip`) }}</h1>
                    <div class="relative mb-6">
                        <CInput type="number" v-model="form.amount" placeholder="Enter Amount" class="w-[300px] text-center" />
                        <span class="absolute top-full left-0 text-xs text-red-500 text-center block w-full">{{ Helper.translate(getError, true) }}</span>
                    </div>
                    <button @click="handleSave" class="px-4 py-2 rounded text-xl bg-red-600 text-white font-bold text-center">
                        {{ Helper.translate('Continue To Payment') }}
                    </button>
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import Master from '../Master.vue'
import MemberCard from '@/Components/Global/MemberCard.vue'
import CInput from '@/Components/Global/CInput.vue'
import { ref, computed } from 'vue'
import { Link , usePage, useForm, Head } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { get, isEmpty } from 'lodash'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'

const minAmount = computed(() => (get(usePage(), 'props.value.earning.amount')))
const talentId  = computed(() => (get(usePage(), 'props.value.talent.id')))
const talentName  = computed(() => (get(usePage(), 'props.value.talent.name')))

const form = useForm({
    amount: minAmount.value
})

const getCatAndSubCat = (talent) => {
    return `${Helper.translate(get(talent, 'category.name'), true)}/${Helper.translate(get(talent, 'subcategory.name'), true)}`
}

const getError = ref('')

const handleSave = () => {
    if(Number(form.amount) < Number(minAmount.value)) {
        getError.value = `${Helper.translate('Minimum')} ${Helper.priceFormate(minAmount.value)}`
        return
    }
    if(Number(form.amount) > Helper.MAXTIPS) {
        getError.value = `${Helper.translate('Maximum')} ${Helper.priceFormate(Helper.MAXTIPS)}`
        return
    }
    Helper.confirm(`
        ${Helper.translate('You will be charged')} ${Helper.translate(Helper.priceFormate(Number(form.amount) + (+Helper.getMaintenanceCharge(form.amount))))} </br> 
        ${Helper.translate(`${Helper.translate(Helper.priceFormate(form.amount))} + ${Helper.translate(Helper.priceFormate(Helper.getMaintenanceCharge(form.amount)))} ${Helper.translate('Service Fee')}`)}
    `, ()=> {
        console.log(talentId.value, 'talentId')
        form.get(route('payment.tips', {talentId: talentId.value, name: String(talentName.value).replace(' ', '-')}), {
            onSuccess(page){
                if (isEmpty(page.props.errors)) {
                    form.reset(); 
                    // emit('close')
                }
            }
        });
    })
}

const back = () => history.back();
</script>