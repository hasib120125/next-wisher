import axios from "axios"
import { ref } from "vue"

const ratings = ref(null)
const loadingRatings = ref(false)
const getRatings = async (params) => {
    loadingRatings.value = true
    const { data } = await axios.get(`ratings`, {
        params: {...params}
    })
    ratings.value = data
    loadingRatings.value = false
}
const getAverageRatings = (data) => {
    const avg = _getTotalRatings(data) > 0 ? _getTotalRatings(data)/data.length : 0
    return avg.toFixed(1)
}

const getRatingCountGroupBy = (data) => {
    const ratingsList = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
    }

    data.length && data.forEach(item => {
        ratingsList[item.rating] += 1
    })
    return ratingsList
}
const insertRatings = async (payload) => {
    axios.post(`/ratings`, payload)
}



const _getTotalRatings = (data) => {
    let totalRatings = 0
    data && data.length && data.forEach(item => {
        totalRatings += item.rating
    })

    return totalRatings
}


export {
    ratings,
    loadingRatings,
    getRatings,
    getAverageRatings,
    getRatingCountGroupBy,
    insertRatings,
}