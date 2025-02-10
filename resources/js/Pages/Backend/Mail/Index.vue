<template>
    <Master>
        <MailMaster>
            <template v-slot:left>
                <LeftSide />
            </template>
            <template v-slot:content>
                <MailContent />
            </template>
            <template v-slot:mail>
                <MailWriter />
            </template>
        </MailMaster>
    </Master>
</template>

<script setup>
import Master from '../Master.vue'
import MailWriter from '@/Components/Backend/Mail/MailWriter.vue'
import MailMaster from '@/Pages/Backend/Mail/Master.vue'
import LeftSide from '@/Components/Backend/Mail/LeftSide.vue'
import MailContent from '@/Components/Backend/Mail/MailContent.vue'
import useMail from '@/Pages/Backend/Mail/useMail'
import { onMounted, onUpdated } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const props = defineProps({
    emails: {
        type: Array,
        default: []
    }
})

const { activeComponent, components, mailList, getMail, loadingMail } = useMail()

onMounted(()=>{
    // mailList.value = usePage().props.value.emails
    getMail()
})


</script>