import { ref } from 'vue'
import Active from '@/Components/Backend/AdminDashboard/Talents/Active.vue'
import Deleted from '@/Components/Backend/AdminDashboard/Talents/Deleted.vue'
import ChangePassword from '@/Components/Backend/AdminDashboard/Talents/Details/ChangePassword.vue'
import WishSent from '@/Components/Backend/AdminDashboard/Talents/Details/WishSent.vue'
import MyLifeVideos from '@/Components/Backend/AdminDashboard/Talents/Details/MyLifeVideos.vue'
import CalendarsPosted from '@/Components/Backend/AdminDashboard/Talents/Details/CalendarsPosted.vue'

const components = {
    Active,
    Deleted
}
const activeComponent = ref('Active')

const detailsComponents = {
    WishSent,
    MyLifeVideos,
    ChangePassword,
    CalendarsPosted
}

const detailsActiveComponent = ref('WishSent')

const talentData = ref({
    wish_sent: [],
    calender_posted: [],
    mylife_videos: [],
})

export default function useUser(){

    return {
        components,
        activeComponent,
        detailsComponents, 
        detailsActiveComponent,
        talentData
    }
}