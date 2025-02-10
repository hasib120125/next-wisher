import axios from 'axios';
import { isEmpty } from 'lodash';
import { ref, watch } from 'vue'

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

    const makeVisitors = (payload) => {
        axios.post(route('visit', payload))
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


