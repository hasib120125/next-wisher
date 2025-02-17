<template>
    <div>
        <h3 class="text-center font-light mb-5">
            {{ Helper.translate('Enter Payout Amount') }}
        </h3>

        <Tabs
            :options="[
                {title: 'Canada', value: 'canada'},
                {title: 'Europe & UK', value: 'europe'},
                {title: 'Outside Europe', value: 'outside'},
                {title: '(List)', value: 'list'},
            ]"
            v-model="selectedTab"
            @onListClick="openCountryList=true"
        />

        <CanadaForm 
            v-if="selectedTab=='canada'"
            @close="$emit('close')"
        />
        <EuropeForm
            v-if="selectedTab=='europe'"
            @close="$emit('close')"
        />
        
        <OutsideForm
            v-if="selectedTab=='outside'"
            @close="$emit('close')"
        />
    </div>
    <Modal
        v-model="openCountryList"
    >
        <CountryList />
    </Modal>
</template>

<script setup>
import Tabs from "./fragments/Tabs.vue"
import Helper from '@/Helper'
import Modal from '@/Components/Library/Modal.vue'
import { ref } from 'vue'
import CountryList from './fragments/CountryList.vue'
import CanadaForm from './fragments/CanadaForm.vue'
import EuropeForm from './fragments/EuropeForm.vue'
import OutsideForm from './fragments/OutsideForm.vue'

const selectedTab = ref('canada')
const openCountryList = ref(false)

</script>