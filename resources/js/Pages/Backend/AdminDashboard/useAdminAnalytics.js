import axios from "axios"
import { ref, watch } from "vue"

const filterData = ref({
    start_date: null,
    end_date: null,
    order_by: null,
})

const earnings = ref({
    wish: 0,
    tips: 0,
    mylife: 0,
    calender: 0,
    revenue_gross: 0,
    revenue: 0,
    chart_data: {
        wish: [],
        tips: [],
        mylife: [],
        calender: [],
        options: [],
    },
})

let timeOut;

export default function useAdminAnalytics() {
    const getEarnings = () => {
        axios.post(route('admin.getTalentEarnings'), filterData.value)
            .then(res => {
                earnings.value = res.data
            })
    }

    watch(filterData, ()=> {
        // if (filterData.value.start_date && filterData.value.end_date) {
            clearTimeout(timeOut)
            timeOut = setTimeout(()=> {
                getEarnings()
            }, 200)
        // }
    }, {
        deep: true
    })

    return {
        earnings,
        filterData,
        getEarnings
    }
}