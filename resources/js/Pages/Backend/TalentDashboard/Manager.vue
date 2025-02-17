<template>
    <Head title="Guide" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div>
                <div>
                    <p class="mb-5">
                        <strong class="font-bold">
                            {{ Helper.translate('Do you want to maximize your income?') }}
                        </strong>
                        {{ Helper.translate('Expand your visibility and give millions of people or companies around the world the opportunity to contact you for various occasions.') }}
                    </p>
                    <p class="mb-5">
                        {{ Helper.translate('Nextwisher can act as your personal booking agency for appearances, speaking engagements, products or advertising endorsements, corporate events, private parties and more around the world.') }}
                    </p>
                    <p class="mb-5">
                        {{ Helper.translate('By completing the information below and activating this option you authorize Nextwisher to act as your booking agency. We will present to you any offer received.') }}
                    </p>
                </div>
                <div class="mt-14 flex flex-col gap-5">
                    <button @click="() => {
                        managerPopup=true
                        form.is_manager = false
                    }" class="py-2 px-5 hover:opacity-80 rounded-full bg-[#2589ff] text-white">
                        {{ Helper.translate('I do not have a manager') }}
                    </button>
                    <button 
                        @click="() => {
                            managerPopup=true
                            form.is_manager = true
                        }" 
                        class="py-2 px-5 hover:opacity-80 rounded-full bg-[#0d41dd] text-white"
                    >
                        {{ Helper.translate('I have a manager') }}
                    </button>
                </div>
            </div>
            <Modal v-model="managerPopup">
                <div class="bg-white w-[300px] md:min-w-[500px] rounded">
                    <div class="py-4 px-5 border-b font-semibold text-xl">
                        {{ Helper.translate('Contact Information') }}
                    </div>
                    <form @submit.prevent="handleSave" class="py-2 px-5 grid gap-4">
                        <CInput 
                            v-model="form.name"
                            placeholder="Name"
                            :error="form.errors.name"
                            v-if="form.is_manager"
                        />
                        <CInput 
                            v-model="form.phone" 
                            :error="form.errors.phone"
                            placeholder="Phone Number" 
                        />
                        <CInput 
                            v-model="form.confirm_phone"
                            :error="form.errors.confirm_phone"
                            placeholder="Confirm Phone Number"
                        />
                        <CInput
                            v-model="form.email"
                            type="email"
                            :error="form.errors.email"
                            placeholder="Email"
                        />
                        <div class="flex justify-end mb-5">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="py-2 px-5 bg-[#0d41dd] disabled:cursor-not-allowed hover:opacity-80 rounded text-white flex gap-2 items-center"
                            >
                                <Preloader
                                    class="w-5 h-5"
                                    v-if="form.processing"
                                />
                                {{ Helper.translate('Submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import CInput from '@/Components/Global/CInput.vue'
import Modal from '@/Components/Library/Modal.vue'
import Preloader from '@/Components/Global/Preloader.vue';
import Helper from '@/Helper'
import { onMounted, ref } from 'vue';
import { isEmpty } from 'lodash';

const props = defineProps({
    manager: Object,
})

const form = useForm({
    id: null,
    name: '',
    phone: '',
    confirm_phone: '',
    email: '',
    is_manager: false,
})

const managerPopup = ref(false)

const handleSave = () => {
    form.post(route('talent.manager.save'), {
        onFinish(e) {
            if(isEmpty(form.errors)) {
                managerPopup.value = false
                form.reset()
            }
        }
    })
}

onMounted(() => {
    if(props.manager) {
        form.id = props.manager?.id
        form.name = props.manager?.name
        form.phone = props.manager?.phone
        form.confirm_phone = props.manager?.phone
        form.email = props.manager?.email
        form.is_manager = props.manager?.is_manager || false
    }
})

</script>


<style scoped>
.content {
    grid-template-columns: 250px 1fr;
}
</style>