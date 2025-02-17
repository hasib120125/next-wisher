<template>
    <Head title="Guide" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div v-html="guide[language=='english' ? 'description' : language]"></div>
        </template>
    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue';
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import { size } from 'lodash';
import { onMounted, ref } from 'vue';

const guide = ref({})
const language = ref('english')

onMounted(()=> {
    if (localStorage.language) {
        language.value = localStorage.language
    }
    axios.get(`pages?type=talent-guide`)
        .then(res => {
            if (size(res.data)==1) {
                guide.value = res.data[0]
            }
        })
})

</script>


<style scoped>
.content {
    grid-template-columns: 250px 1fr;
}
</style>