import { ref } from 'vue'
import Active from '@/Components/Backend/AdminDashboard/Users/Active.vue'
import Deleted from '@/Components/Backend/AdminDashboard/Users/Deleted.vue'
import WishRequest from '@/Components/Backend/AdminDashboard/Users/Details/WishRequest.vue'
import ChangePassword from '@/Components/Backend/AdminDashboard/Users/Details/ChangePassword.vue'

const components = {
    Active,
    Deleted
}
const activeComponent = ref('Active')

const detailsComponents = {
    WishRequest,
    ChangePassword
}
const detailsActiveComponent = ref('WishRequest')

export default function useUser(){

    return {
        components,
        activeComponent,
        detailsComponents, 
        detailsActiveComponent
    }
}