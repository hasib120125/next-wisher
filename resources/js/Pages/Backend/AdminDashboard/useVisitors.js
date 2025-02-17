import Helper from '@/Helper';
import axios from 'axios';
import { isEmpty, get } from 'lodash';
import { ref, watch } from 'vue'
import Swal from 'sweetalert2'

const onlineVisitors = ref({
    talents: 0,
    total_hits: 0,
    users: 0,
    visitors: 0
})

const visitors = ref({
    talents: 0,
    total_hits: 0,
    users: 0,
    visitors: 0
})

const userSummery = ref({
    new_users: 0,
    talent_applications: 0,
    talent_applications_approved: 0,
})

const rejectPopup = ref(false)
const visitorsAnalyticsDate = ref([])
const visitorsDemographyDate = ref([])
const hitDelay = 19 * 1000
const visitorFilter = ref({
    from: null,
    to: null,
})

export default function useVisitors(){
    const getOnlineVisitors = async (visitorFilter) => {
        const visitors = await axios.post(route('online_visitors'), visitorFilter)
            .then(res => res.data);
        onlineVisitors.value = visitors;
        return visitors;
    }
    const getVisitors = async (visitorFilter) => {
        const all_visitor = await axios.post(route('get_visitors'), visitorFilter).then(res => res.data);
            visitors.value = all_visitor;
        return all_visitor;
    }
    const getVisitorsAnalyticsDate = async (visitorFilter) => {
        visitorsAnalyticsDate.value = await axios.post(route('getVisitorsAnalyticsDate'), visitorFilter).then(res => res.data);
    }
    const getVisitorsDemographyDate = async (visitorFilter) => {
        visitorsDemographyDate.value = await axios.post(route('getVisitorsDemographyDate'), visitorFilter).then(res => res.data);
    }

    const getUserSummery = async (visitorFilter) => {
        userSummery.value = await axios.post(route('getUserSummery'), visitorFilter).then(res => res.data);
    }


    const refreshVisitors = () => {
        location.reload()
        // getOnlineVisitors()
        // getVisitors()
        // getVisitorsAnalyticsDate()
    }

    const showDeclinePopup = () => {
        Swal.fire({
            title: Helper.translate('Your account is suspended'),
            html: `
                ${Helper.translate('We were not able to approve your account for some reason.')} </br>
                ${Helper.translate('Please contact us at support@nextwisher.com ')}
            `,
            icon: 'warning',
            confirmButtonColor: '#4acb6f',
            cancelButtonColor: '#ef4444',
            confirmButtonText: Helper.translate('Ok'),
            showCancelButton: false,
            showConfirmButton: true
        })
        .then(() => {
            location.reload()
        })
    
        // Swal.getConfirmButton().onclick = () => {
        //     console.log('some')
        //     Swal.clickCancel()
        // }
    }

    let blockVisit = false
    const makeVisitors = (payload) => {
        if(blockVisit) return
        axios.post(route('visit', payload))
            .then(res => {
                if(get(res, 'data.suspend_popup') == true) {
                    showDeclinePopup()
                    blockVisit=true
                }
            })
    }

    return {
        visitorFilter,
        onlineVisitors,
        visitors,
        userSummery,
        getOnlineVisitors,
        getVisitors,
        getVisitorsAnalyticsDate,
        makeVisitors,
        getUserSummery,
        refreshVisitors,
        hitDelay,
        visitorsAnalyticsDate,
        getVisitorsDemographyDate,
        visitorsDemographyDate
    }
}


