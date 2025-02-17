<template>
    <Head title="Earnings" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>

            <Widget />
            <Analytics class="border-b border-t py-10 mt-5" />

            <div class="flex md:justify-between justify-center md:flex-nowrap flex-wrap gap-4 items-center mt-10">
                <h1 class="font-semibold">{{ Helper.translate('Payout History') }}</h1>
                <button @click="payoutModal = true" class="bg-red-500 px-4 py-1 rounded text-white font-semibold">
                    {{ Helper.translate('Request Payout') }}
                </button>
            </div>
            <Modal v-model="payoutModal">
                <PayoutRequest @close="payoutModal = false" />
            </Modal>

            <Table class="border border-b-0" />

        </template>
    </DashboardLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue'
import Table from '@/Components/Backend/TalentDashboard/Payout/Table.vue'
import Widget from '@/Components/Backend/TalentDashboard/Payout/Widget.vue'
import Modal from '@/Components/Library/Modal.vue'
import PayoutRequest from '@/Pages/Backend/TalentDashboard/PayoutRequest.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { Head, usePage } from '@inertiajs/inertia-vue3'
import { isEmpty } from 'lodash'
import Analytics from '@/Pages/Backend/TalentDashboard/Analytics.vue'
import Helper from '@/Helper'

const { components, data, resultPerPage } = useTable()

const payoutModal = ref(false)

const props = defineProps({
    requests: {
        type: Array,
        default: []
    }
})
</script>


<style scoped>
.content {
    grid-template-columns: 250px 1fr;
}
</style>