import Applications from '@/Components/Backend/AdminDashboard/TalentApplications/Applications.vue'
import Declined from '@/Components/Backend/AdminDashboard/TalentApplications/Declined.vue'
import { ref } from 'vue'

const components = {
    Applications,
    Declined
}

const activeComponent = ref('Applications')
export default function useTalentApplications(){

    return {
        components,
        activeComponent
    }
}