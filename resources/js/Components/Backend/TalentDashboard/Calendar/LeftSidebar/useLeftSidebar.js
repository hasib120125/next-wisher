import CalendarConfig from '@/Components/Backend/TalentDashboard/Calendar/LeftSidebar/components/CalendarConfig.vue'
import Media from '@/Components/Backend/TalentDashboard/Calendar/LeftSidebar/components/Media.vue'
import TextConfig from '@/Components/Backend/TalentDashboard/Calendar/LeftSidebar/components/TextConfig.vue'
import { ref } from 'vue'

const activeComponent = ref('CalendarConfig')
export default function useLeftSidebar(){
    const components = {
        CalendarConfig,
        TextConfig,
        Media
    }

    return {
        components,
        activeComponent
    }
}