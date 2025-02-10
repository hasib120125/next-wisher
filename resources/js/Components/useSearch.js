import axios from "axios";
import { ref } from "vue"

const searchQuery = ref('')
const searchResult = ref([])
let searchToken;
let timeOut;

const handleSearch = () => {
    clearTimeout(timeOut)
    if (!searchQuery.value) {
        searchResult.value = []
        return;
    }
    searchToken = axios.CancelToken.source();

    timeOut = setTimeout(() => {
        axios.post(route('search'), {
                q: searchQuery.value
            }, {
                cancelToken: searchToken.token
            })
            .then(res => {
                searchResult.value = res.data
            }).catch(err => {})
    }, 500)
}

const cancelSearch = () => {
    if (searchToken) {
        searchToken.cancel()
    }
}

const getName = (talent) => {
    const { username } = talent
    return  username
}

export {
    searchQuery,
    searchResult,
    cancelSearch,
    getName,
    handleSearch
}