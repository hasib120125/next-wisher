<template>
    <Head title="Wish Request" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <h1 class="text-lg">
                {{ Helper.translate('Please setup an amount to charge for Wish Request') }}
            </h1>
        
            <div class="grid gap-6">
                <h2 class="font-bold mt-5">{{ Helper.translate('Enter Amount') }}</h2>
                <div class="flex gap-10 w-full lg:w-1/2">
                    <div class="relative mb-6">
                        <CInput v-model="form.amount" type="number" min="30" placeholder="Amount" class="w-full"/>
                        <span class="absolute top-full left-0 text-xs text-red-500">{{ Helper.translate(form.errors.amount, true) }}</span>
                    </div>
                    <div>
                        <button @click="handleSave" class="bg-red-500 text-white font-bold px-4 py-2 rounded">
                            {{ Helper.translate('Update/Save') }}
                        </button>
                    </div>
                </div>
                <div class="grid items-center gap-2">
                    <h2 class="font-bold mt-5">{{ Helper.translate('Activate') }}</h2>
                    <Switch v-model="form.status" @change="handleSave" :loading="loading" class="font-bold"/>
                </div>

                <Link :href="route('item.preview', $page.props.auth.user.id)" class="px-4 mt-10 py-2 rounded text-xl bg-black text-white font-bold text-center w-fit">
                  {{ Helper.translate('Preview Profile') }}
                </Link>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import CInput from '@/Components/Global/CInput.vue'
import Switch from '@/Components/Global/Switch.vue'
import { Head, useForm, Link, usePage } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import { isEmpty } from 'lodash'
import { onMounted, ref } from '@vue/runtime-core'


const form = useForm({
    amount: null,
    status: 0,
    type: 'wish',
});

const loading = ref(false)

onMounted(()=> {
    let wish = usePage().props.value.wish;
    if (!isEmpty(wish)) {
        form.amount = wish.amount;
        form.status = wish.status;
    }
})

const handleSave = () => {
    // 30
    // 2500
    if (form.amount < 30) {
        form.errors.amount = Helper.translate(`Minimum`) + ` ${Helper.priceFormate(30)}`;
        return;
    }
    if (form.amount > 2500) {
        form.errors.amount = Helper.translate(`Maximum`) + ` ${Helper.priceFormate(2500)}`;
        return;
    }
    form.post(route('talent.wish.saveAmount'),{
        onBefore(){
            loading.value = true
        },
        onSuccess(e){
            if (!isEmpty(e.props.errors)) {
                form.status = usePage().props.value.wish.status;
            }
            loading.value = false;
        },
        onError(){
            form.status = usePage().props.value.wish.status;
            loading.value = false;
        }
    });
}

</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>