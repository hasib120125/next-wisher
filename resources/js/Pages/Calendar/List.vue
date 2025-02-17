<template>
    <GlobalLayout>
        <Head title="Calenders" />
        <Navigation />
        <div v-if="!isEmpty($page.props.calendars)" class="grid gap-14 p-10">
            <CalendarItem 
                v-for="item in $page.props.calendars" 
                :key="item.id" 
                :item="item" 
            />
        </div>
        <Alert v-else :title="Helper.translate('No calendar created!')" />
    </GlobalLayout>
</template>

<script setup>
import CalendarItem from '@/Components/Backend/TalentDashboard/Calendar/CalendarItem.vue'
import Navigation from '@/Components/Backend/TalentDashboard/Calendar/Navigation.vue'
import Alert from '@/Components/Global/Alert.vue'
import GlobalLayout from '@/Layouts/GlobalLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { isEmpty } from 'lodash';
import Helper from '@/Helper'
</script>