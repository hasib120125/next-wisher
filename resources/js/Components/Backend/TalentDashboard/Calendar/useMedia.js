import axios from "axios"
import { usePage } from "@inertiajs/inertia-vue3"
import { ref } from "vue"
import Helper from "@/Helper"
import useCalendar from "./useCalendar"

const allMedia = ref([])
export default function useMedia() {
    const { calendarPayload } = useCalendar()
    let timeoutId = null
    const getMedia = () => {
        clearTimeout(timeoutId)
        setTimeout(async () => {
            allMedia.value = await axios.get(`media/${usePage().props.value.auth.user.id}`).then(({data}) => data)
            return allMedia.value
        }, 1000)
    }
    
    const handleImage = (image) => {
        const { selectedPageIndex, settings } = calendarPayload.value
        settings[selectedPageIndex].path = image
    }

    const deleteMedia = (mediaId) => {
        Helper.confirm(Helper.translate("Do you really want to delete?"), async () => {
            let status = await axios.delete(`media/delete/${mediaId}/${usePage().props.value.auth.user.id}`)
            if(status) {
                getMedia()
            }
        })

    }

    return {
        getMedia,
        handleImage,
        deleteMedia,
        allMedia
    }
}