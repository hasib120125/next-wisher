import PayoutRequest from '@/Components/Backend/AdminDashboard/Payments/PayoutRequest.vue'
import PayoutsProcessed from '@/Components/Backend/AdminDashboard/Payments/PayoutsProcessed.vue'
import TalentEarning from '@/Components/Backend/AdminDashboard/Payments/TalentEarning.vue'
import Earnings from '@/Components/Backend/AdminDashboard/Payments/Earnings.vue'
import Details from '@/Components/Backend/AdminDashboard/Payments/Details.vue'

import { ref } from 'vue'

const components = {
    PayoutRequest,
    PayoutsProcessed,
    TalentEarning,
    Earnings,
    Details,
}

const activeComponent = ref('PayoutRequest')

export default function payments(){

    return {
        components,
        activeComponent
    }
}