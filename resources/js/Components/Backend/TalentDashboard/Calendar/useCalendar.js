import { cloneDeep, get } from "lodash"
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { ref, computed } from "vue"
import { DemoImageListForCalendar, defaultWeeksList, weeksList } from '@/Components/Backend/TalentDashboard/Calendar/calendarData.js'
import { useForm, usePage } from "@inertiajs/inertia-vue3"
import { Inertia } from "@inertiajs/inertia";
import Helper from "@/Helper";

const previewMode = ref(0)
const defaultPayload = {
    user_id: null,
    year: new Date().getFullYear(),
    selectedPageIndex: new Date().getMonth() + 1,
    language: 'english',
    weekStartDay: 0,
    theme: 'black',
    title: null,
    settings: cloneDeep(DemoImageListForCalendar)
}
const calendarPayload = ref({...defaultPayload})
let timeoutId = null

export default function useCalendar() 
{
    const auth = computed(() => usePage().props.value.auth)
    const getDateList = (selectedPageIndex) => {
        let selectedMonth = selectedPageIndex
        let selectedYear = calendarPayload.value.year
        // Note: month and week index start from 0
        const dateList = []

        // work with previous month---
        let prevMonthLastDay = new Date(selectedYear, selectedMonth, 0).getDay()
        let selectedMonthFirstDay = new Date(selectedYear, selectedMonth, 1).getDay()
        let prevMonthLastDate    = new Date(selectedYear, selectedMonth, 0).getDate()

        const selectedMonthFirstDayIndexAccordingToModifiedWeeks = weeksList.value.findIndex((item) => {
            return (item.value == defaultWeeksList[selectedMonthFirstDay].value)
        })

        for(let i=0; i < selectedMonthFirstDayIndexAccordingToModifiedWeeks; i++){
            dateList.push({
                date: (prevMonthLastDate-selectedMonthFirstDayIndexAccordingToModifiedWeeks)+(i+1),
                isActive: false,
                isInactive: true
            })
        }

        // work with current month -----
        const selectedMonthLastDate = new Date(selectedYear, selectedMonth+1, 0).getDate()
        for(let i = 1; i <= selectedMonthLastDate; i++)
        {
            let isActive = false
            let date = new Date()
            if(date.getFullYear() === selectedYear && date.getMonth() === selectedMonth && date.getDate() === i){
                isActive = true
            }

            dateList.push({
                date: i,
                isActive,
                isInactive: false
            })
        }
        
        // work with next month -----
        const selectedMonthLastDay = new Date(selectedYear, selectedMonth+1, 0).getDay()
        const selectedMonthLastDayIndexAccordingToModifiedWeeks = weeksList.value.findIndex((item) => {
            return (item.value == defaultWeeksList[selectedMonthLastDay].value)
        })
        
        for(let i = 1; i < (7 - selectedMonthLastDayIndexAccordingToModifiedWeeks); i++){
            dateList.push({
                date: i,
                isActive: false,
                isInactive: true
            })
        }
        
        return dateList
    }

    const weekChanger = (weekIndex) => {
        let myWeeks = cloneDeep(defaultWeeksList)
        let modifiedWeeks = {}

        let splicedWeeks = myWeeks.splice(weekIndex)
        modifiedWeeks = (splicedWeeks.concat(myWeeks))

        calendarPayload.weekStartDay = weekIndex
        weeksList.value = modifiedWeeks
    }

    const getSelectedPage = (index) => {
        return calendarPayload.value.settings[index]
    }

    const saveCalendar = () => 
    {
        calendarPayload.value.user_id = auth.value.user.id
        let notValid = calendarPayload.value.settings.find(item => {
            return item.path == ''
        })
        if(notValid || !calendarPayload.value.title){
            toast.error(Helper.translate("Incomplete!"), {
                position: "top-right",
                autoClose: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
                theme: "light",
            })
            return
        }

        const form = useForm({...calendarPayload.value})
        form.post(route('calendar_save'))
    }

    const updateCalendar = (item) => {
        clearTimeout(timeoutId)
        timeoutId = setTimeout(() =>{
        axios.post(`/edit-calendar/${item.id}/${auth.value.user.id}`, item)
            toast.success(Helper.translate('Data Updated'), {
                position: "top-right",
                autoClose: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
                theme: "light",
            })
        }, 1500)
    }

    const updateCalendarPrice = (price, id) => {
        let data = {
            user_id: auth.value.user.id,
            id,
            price
        }

        Inertia.post(route('calendar_price_update', data))
    }

    // const updateCalendarTitle = (title, id) => {
    //     let data = {
    //         user_id: auth.value.user.id,
    //         id,
    //         title
    //     }

    //     Inertia.post(route('calendar_price_update', data))
    // }

    const deleteCalendar = (calendar_id) => {
        Helper.confirm('Do you really want to delete?', () => {
            Inertia.delete(route('calendar_delete', calendar_id))
            toast.warn('Calendar Deleted !', {
                position: "top-right",
                autoClose: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
                theme: "light",
            })
        })
    }

    const makeCalendarSaleable = (calendar, e) => {
        if(!calendar.title){
            calendar.is_salable = 0
            toast.warning(Helper.translate('Calendar title required'), {
                position: "top-right",
                autoClose: 4000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
                progress: undefined,
                theme: "light",
            })

            return
        }
        Inertia.post(route('salable', calendar.id), {salable: e.target.checked})
    }
    

    return {
        getDateList,
        weekChanger,
        saveCalendar,
        updateCalendar,
        deleteCalendar,
        updateCalendarPrice,
        makeCalendarSaleable,
        calendarPayload,
        getSelectedPage,
        previewMode,
        defaultPayload
    }
}