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
    import LeftSide from '@/Components/Backend/UserDashboard/LeftSide.vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import axios from 'axios';
    import { onMounted, ref } from 'vue';
    import { size } from 'lodash';
    import Swal from 'sweetalert2';
    import Helper from '@/Helper';

    const guide = ref({})
    const language = ref('english')

    onMounted(()=> {
        if (localStorage.language) {
            language.value = localStorage.language
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('user-verifyed') == 'success') {
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: `${Helper.translate('Your email has been verified.')}`,
                    text: `${Helper.translate('Welcome to your dashboard.')}`,
                    confirmButtonText: `${Helper.translate('Ok')}`,
                }).then(result => {
                    location.href = location.href.replace(location.search, '')
                })
            }, 1200);
        }
        axios.get(`pages?type=user-guide`)
            .then(res => {
                if (size(res.data)==1) {
                    guide.value = res.data[0]
                }
            })
    })

</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>