import { ref } from 'vue'
import All from '@/Components/Backend/AdminDashboard/Country/All.vue'
import Add from '@/Components/Backend/AdminDashboard/Country/Add.vue'
import Edit from '@/Components/Backend/AdminDashboard/Country/Edit.vue'

const components = {
    All,
    Add,
    Edit
}
const activeComponent = ref('All')
const selectedCountry = ref({})

export default function useCountry(){
    const countryStatus = ref([
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
        countryStatus,
        selectedCountry
    }
}