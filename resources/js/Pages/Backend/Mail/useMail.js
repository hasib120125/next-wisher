import { ref, computed, watch } from 'vue'
import Inbox from '@/Components/Backend/Mail/LeftSideComponents/Inbox.vue'
import SendBox from '@/Components/Backend/Mail/LeftSideComponents/SendBox.vue'
import axios from 'axios'
import { usePage } from '@inertiajs/inertia-vue3'
import { get } from 'lodash'

const activeComponent = ref('Inbox')
const components = {
    Inbox,
    SendBox
}
const mailList = ref([])
const mailCount = ref(null)
let timeout;
const selectedMailContent = ref({})
const toggleLeftSidebarInMobile = ref(false)
const isSmallDevice = ref(false)
const loadingMail = ref(false)

export default function useMail()
{
    const getMail = () => {
        loadingMail.value = true
        let tempMailList = mailList.value
        mailList.value = []
        axios.get(route('getMail'))
            .then(res => {
                if (res.data) {
                    mailList.value = res.data
                }
                loadingMail.value = false
            }).catch(err => {
                loadingMail.value = false
                mailList.value = tempMailList
            });
    }

    const makeMailSelected = (item) => {
        toggleLeftSidebarInMobile.value = false
        mailList.value.forEach(mail => mail.isSelected = false)
        item.isSelected = true
        item.seen = true
    }
    const makeMailDeselected = () => {
        mailList.value.forEach(mail => mail.isSelected = false)
    }
    const getSelectedMail = computed(() => {
        selectedMailContent.value = mailList.value.find(mail => mail.isSelected) 
        return selectedMailContent.value
    })
    const isExpired = () => {
        let currentDate = new Date()
        let expirationDate = (getSelectedMail.value?.expirationDate) ? new Date(getSelectedMail.value.expirationDate) : null

        return expirationDate < currentDate
    }
    const getMailCount = () => {
        if (get(usePage().props.value, 'auth.user')) {
            axios.post(route('getMailCount'))
                .then(res => {
                    mailCount.value = res.data;
                });
        }
    }

    watch(mailList, () => {
        if (getSelectedMail.value?.id) {
            let id = getSelectedMail.value?.id;
            clearTimeout(timeout)
            timeout = setTimeout(()=> {
                axios.post(route('seenMail', {
                    id: id
                })).then(getMailCount)
            }, 500)
        }
        // hit api to update in database
    }, { deep: true })
    return {
        activeComponent,
        components,
        mailList,
        loadingMail,
        mailCount,
        makeMailSelected,
        getMailCount,
        getMail,
        getSelectedMail,
        isExpired,
        toggleLeftSidebarInMobile,
        isSmallDevice,
        makeMailDeselected,
        selectedMailContent
    }
}