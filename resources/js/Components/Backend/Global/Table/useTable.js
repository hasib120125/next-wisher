import { ref, onMounted, watch } from 'vue'
import Pagination from '@/Components/Backend/Global/Table/Pagination.vue'
import PageSize from '@/Components/Backend/Global/Table/PageSize.vue'
import Search from '@/Components/Backend/Global/Table/Search.vue'
import { cloneDeep, isEmpty, get } from 'lodash'

const data = ref([])
const pageSizeArray = ref([2, 10, 50, 100, 500])
const defaultPageSize = ref(10)
const pageSize = ref(defaultPageSize.value)
const result = ref([])
const resultPerPage = ref([])
const searchString = ref('')
const currentPageNumber   = ref(1)
const totalPage   = ref(0)
const hasPagination = ref(false)
let cbFunc = ref(null);

export default function tableHelper()
{
    const search = () => 
    {
        currentPageNumber.value = 1
        if(isEmpty(searchString.value)){
            result.value = cloneDeep(data.value)
            resultPerPage.value = cloneDeep(data.value)
            resultPerPage.value.length = (result.value.length > pageSize.value) ? pageSize.value : result.value.length
            totalPage.value = Math.ceil(result.value.length / pageSize.value)
            return
        }
        
        let response = data.value.filter(item => {
            if (cbFunc.value) {
                return cbFunc.value(item, searchString.value)
            }
            else if(
                item?.name.toLowerCase().search(searchString.value.toLowerCase()) >= 0 
                || item?.email.toLowerCase().search(searchString.value.toLowerCase()) >= 0
            ){
                return item
            }
        })
        
        result.value = response
        resultPerPage.value = response
        resultPerPage.value.length = (result.value.length > pageSize.value) ? pageSize.value : result.value.length
        totalPage.value = Math.ceil(result.value.length / pageSize.value)
    }

    const components = {
        Pagination,
        PageSize,
        Search
    }

    onMounted(() => {
        search()
    })

    const initPagination = (dataList) => {
        data.value = dataList
        search()
    }

    watch(data, () => {
        search()
    }, {deep: true})

    return {
        components,
        cbFunc,
        data,
        searchString,
        defaultPageSize,
        pageSize,
        pageSizeArray,
        result,
        search,
        currentPageNumber,
        resultPerPage,
        totalPage,
        hasPagination,
        initPagination
    }
}