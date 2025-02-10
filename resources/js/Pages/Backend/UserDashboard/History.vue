<template>
    <Head title="History" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <Header
                :activeTab="activeTab"
                @updateTab="(value) => activeTab=value"
            >
                <template v-slot:pageSize>
                    <component :is="components['PageSize']" />
                </template>
                <template v-slot:search>
                    <component :is="components['Search']" />
                </template>
            </Header>
            <Table
                :payments="resultPerPage"
                class="border border-b-0 mt-5"
            >
            <template v-slot:pagination>
                <component :is="components['Pagination']" />
            </template>
            </Table>
            <!-- <div class="flex justify-between items-center mt-4">
                <h3 class="">Showing 1 to 3 of 3 entries</h3>
                <div class="flex gap-4">
                    <button class="border px-2">
                        <svg class="w-4" width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="m19.031 4.281-11 11-.687.719.687.719 11 11 1.438-1.438L10.187 16 20.47 5.719Z"/></svg>
                    </button>
                    <button class="border px-2">
                        <svg class="w-4" width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M12.969 4.281 11.53 5.72 21.812 16l-10.28 10.281 1.437 1.438 11-11 .687-.719-.687-.719Z"/></svg>
                    </button>
                </div>
            </div> -->
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/UserDashboard/LeftSide.vue'
import Header from '@/Components/Backend/UserDashboard/History/Header.vue'
import Table from '@/Components/Backend/UserDashboard/History/Table.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'
import { computed, ref, watch } from 'vue'
import { filter } from 'lodash'
import { Head } from '@inertiajs/inertia-vue3'

const { components, data, resultPerPage } = useTable()

const props = defineProps({
    payments: {
        type: Array,
        default: []
    }
})

const activeTab = ref('Tips')

watch(()=> {
    let type = activeTab.value
    switch (type) {
        case 'Tips':
            data.value = filter(props.payments, item => item.type == 'tips')
            break;
        case 'Wishes':
            data.value = filter(props.payments, item => item.type == 'wish')
            break;
        case 'My Life':
            data.value = filter(props.payments, item => item.type == 'mylife')
            break;
        case 'Calendar':
            data.value = filter(props.payments, item => item.type == 'calender')
            break;
        default:
            data.value = []
            break;
    }
})


</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>