<template>
    <Master>
        <div class="px-4">
            <div class="container mx-auto py-5">
                <MemberCard
                    class="mx-auto"
                    :name="Helper.translate(talent.name)"
                    :designation="talent.email"
                />

                <div class="flex gap-4 items-center py-3 font-bold mt-5">
                    <label class="flex gap-2 items-center cursor-pointer">
                        <input type="radio" v-model="form.type" value="myself" checked class="accent-black">
                        {{ Helper.translate('Myself') }}
                    </label>
                    <label class="flex gap-2 items-center cursor-pointer">
                        <input type="radio" v-model="form.type" value="someoneElse" name="who" class="accent-black">
                        {{ Helper.translate('Someone else') }}
                    </label>
                </div>
                
                <div class="relative mb-6" v-if="form.type == 'myself'">
                    <CInput v-model="form.name" placeholder="Enter your name" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.name, true) }}</span>
                </div>
                <div class="relative mb-6" v-if="form.type == 'someoneElse'">
                    <CInput v-model="form.from" placeholder="From" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.from, true) }}</span>
                </div>
                <div class="relative mb-6" v-if="form.type == 'someoneElse'">
                    <CInput v-model="form.for" placeholder="For" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.for, true) }}</span>
                </div>

                <h3 class="mt-6 font-bold text-lg">{{ Helper.translate('Gender') }}</h3>
                <div class="relative">
                    <div class="flex gap-4 items-center pt-3">
                        <label class="flex gap-2 items-center cursor-pointer">
                            <input type="radio" v-model="form.gender" name="gander" value="female" class="accent-black">
                            {{ Helper.translate('Female') }}
                        </label>
                        
                        <label class="flex gap-2 items-center cursor-pointer">
                            <input type="radio" v-model="form.gender" name="gander" value="male" class="accent-black">
                            {{ Helper.translate('Male') }}
                        </label>
                    </div>
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.gender, true) }}</span>
                </div>

                <h3 class="mt-6 font-bold text-lg">{{ Helper.translate('Occasion') }}</h3>
                <div class="relative mb-6">
                    <CSelect v-model="form.occassion_id" :options="structured" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.occassion_id, true) }}</span>
                </div>

                <h3 class="mt-6 font-bold text-lg">{{ Helper.translate('Instructions') }}</h3>
                <div class="relative mb-6">
                    <CTextarea v-model="form.instruction" placeholder="Write Instructions" characterLimit="500" rows="4" />
                    <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.instruction, true) }}</span>
                </div>

                <button @click="handleBefore" class="bg-[var(--btn-bg-color)] text-white px-6 py-2 rounded block max-w-fit mx-auto mt-4">
                    {{ Helper.translate('Continue to payment') }}
                </button>
            </div>
        </div>
    </Master>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import CTextarea from '@/Components/Global/CTextarea.vue'
import MemberCard from '@/Components/Global/MemberCard.vue'
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import Master from '../Master.vue'
import Helper from '@/Helper'
import { map, isEmpty, constant } from 'lodash'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    talent: {
        type: Object,
        default: {}
    },
    calender: {
        type: Object,
        default: {}
    },
    ocasions: {
        type: Array,
        default: []
    },
    type: String
});

const structured = ref([])

onMounted(()=> {
    let ocasions = map(props.ocasions, item => {
        return {
            key: item.id,
            value: item.name
        }
    });
    ocasions.unshift({
        key: null,
        value: 'Select occasion'
    });
    structured.value = ocasions;
});

const form = useForm({
    type: 'myself',
    name: null,
    from: null,
    for: null,
    gender: null,
    occassion_id: null,
    instruction: null,
});

function handleBefore() {
    let data = {}
    data.type =form.type;
    if (form.type == 'myself') {
        data.name = validate('name');
    } else {
        data.from = validate('from');
        data.for = validate('for');
    }

    data.gender = validate('gender');
    data.occassion_id = validate('occassion_id');
    data.instruction = validate('instruction');
    data.payType = props.type;
    let amount = Helper.getMaintenanceCharge(props.calender.price) + (+props.calender.price);
    

    if (!isEmpty(form.errors)) {
        return;
    }

    Helper.confirm(`
    ${Helper.translate('You have to pay')} ${Helper.priceFormate(amount)} <br>
        ${Helper.translate(`${Helper.priceFormate(props.calender.price)} + ${Helper.priceFormate(Helper.getMaintenanceCharge(props.calender.price))} ${Helper.translate('Service Fee')}`)}
    `, ()=>{
        localStorage.setItem('cashType', JSON.stringify(data));
        Inertia.get(route('payment.calenderPurchase', {
            talentId: props.talent.id, 
            calenderId: props.calender.id
        }));
    });
}

const validate = (name) => {
    if(isEmpty(form[name].toString())) {
        form.errors[name] = `${name.replace('_', ' ')} is required`
    } else {
        delete form.errors[name]
    }
    return form[name];
}
</script>