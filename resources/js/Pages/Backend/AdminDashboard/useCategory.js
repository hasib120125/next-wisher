import { ref } from 'vue'
import All from '@/Components/Backend/AdminDashboard/Category/All.vue'
import Add from '@/Components/Backend/AdminDashboard/Category/Add.vue'
import Edit from '@/Components/Backend/AdminDashboard/Category/Edit.vue'

const components = {
    All,
    Add,
    Edit
}

const activeComponent = ref('All')
const selectedCategory = ref({})

export default function useCategory(){
    const categoryStatus = ref([
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
        selectedCategory,
        categoryStatus
    }
}