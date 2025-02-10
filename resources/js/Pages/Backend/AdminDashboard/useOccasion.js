import { ref } from 'vue'
import All from '@/Components/Backend/AdminDashboard/Occasion/All.vue'
import Add from '@/Components/Backend/AdminDashboard/Occasion/Add.vue'
import Edit from '@/Components/Backend/AdminDashboard/Occasion/Edit.vue'

const components = {
    All,
    Add,
    Edit
}

const activeComponent = ref('All')
const selectedOccasion = ref({})

export default function useOccasion(){
    const occasionStatus = ref([
        {
            key: '',
            value: 'Select Status'
        },
        {
            key: 1,
            value: 'Enable'
        },
        {
            key: 0,
            value: 'Disable'
        },
    ])

    return {
        components,
        activeComponent,
        selectedOccasion,
        occasionStatus
    }
}