import axios from 'axios'
import Swal from 'sweetalert2'
import moment from 'moment'
import { filter, get, isEmpty } from 'lodash'
import { onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { toast } from 'vue3-toastify'

let languages = ref(null)
let settings  = null
const Helper = {}

const init = async () => {
    languages.value = await axios.get('languages')
    settings  = await axios.get('settings')
}
init()

Helper.pauseControl = (playStatus, index, videosWrapper) => {
    videosWrapper.forEach((item, i) => {
        if (i == index || !playStatus) return
        let videoTag = item.querySelector('video')
        videoTag && videoTag.pause()
    })
}

Helper.confirm = (msg=null, cb) => {
    let status = false
    Swal.fire({
        title: Helper.translate('Confirm'),
        html: msg ? `${msg}` : Helper.translate('Are you sure to delete?'),
        icon: 'warning',
        confirmButtonColor: '#4acb6f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: Helper.translate('Confirm'),
        cancelButtonText: Helper.translate('No'),
        showCancelButton: true,
        showConfirmButton: true
    })

    Swal.getConfirmButton().onclick = () => {
        Swal.clickCancel()
        cb()
    }
}

Helper.dateFormate = (date) => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings && siteSettings.dateFormate){
        return moment(date).format(siteSettings.dateFormate)
    }
    return date
}

export const languagePopup = ref(false)
export const showAuthModal = ref(false)

onMounted(()=> {
    if (get(usePage().props, 'auth.user.id')) {
        showAuthModal.value = false;
    }
})

Helper.getCommission = (amount, commission=0) => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.commission){
        commission = siteSettings.commission
    }
    return ((amount*commission)/100);
}

Helper.getMaintenanceCharge = (amount, charge=0) => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.maintenance_charge){
        charge = siteSettings.maintenance_charge
    }
    return ((+amount* +charge)/100);
}

Helper.priceFormate = (price) => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.currencyPosition){
        const { currencyPosition } = siteSettings
        if(currencyPosition == 'right'){
            return `${price}€`
        }
    }

    return `€${price ? price : 0}`
}

Helper.translate = (text, isDynamic=false, language=null) => {
    if(!languages.value || !text) return text
    
    let data = languages.value.data.find(item => {
        return item.english.toString().trim().toLowerCase() == text.toString().trim().toLowerCase()
    })
    
    if(!language && data && get(data, localStorage.language)){
        return get(data, localStorage.language)
    }

    if(language && data && get(data, language)){
        return get(data, language)
    }

    return text
}


Helper.companyName = () => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.companyName){
        return siteSettings.companyName
    }
}

Helper.logo = () => {
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.logo){
        let logo = siteSettings.logo
        return logo
    }
}

Helper.stripeKey = () => {
    let data = {
        public: '',
        privet: ''
    }
    let siteSettings = usePage().props.value.settings;
    if(siteSettings && siteSettings.stripePrivatKey){
        data = {
            public: siteSettings.stripePublicKey,
            privet: siteSettings.stripePrivatKey
        }
    }
    return data
}

Helper.isExpired = (earnings, objectResponse=false) => {
    let filtered = filter(earnings, item => !item.is_expire)
    return objectResponse ? filtered : isEmpty(filtered)
}

Helper.getDevice = () => {
    if(!navigator?.userAgentData){
        return navigator?.oscpu
    }
    return navigator.userAgentData.platform
}

Helper.getBrowser = () => {
    if(!navigator?.userAgentData){
        return navigator?.userAgent
    }
    return `${navigator?.userAgentData.brands[0]?.brand}-${navigator.userAgentData.brands[0]?.version}`
}

Helper.getVideoDuration = (file, cb, validDuration=30) => {
    const video = document.createElement('video');

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (event) => {
        video.src = event.target.result;
        video.addEventListener('loadedmetadata', () => {
            const duration = Math.floor(video.duration)
            cb(duration <= validDuration)
        });
    };
}

Helper.gustClickAlert = (isLoggedIn) => {
    let old = null
    if(!isLoggedIn) {
        // toast.clearAll();
        old = toast.error(Helper.translate('Please login to view profiles'), {
            position: 'top-right',
            maxToasts: 1,
        })
    }
}
Helper.asTalentClickAlert = () => {
    let alertMessage = `
        ${Helper.translate('A talent cannot book a video or send a tip to another talent.')} </br>
        ${Helper.translate('If you wish to do so please create a user account. Thank you')}
    `
    Swal.fire({
        title: Helper.translate('Alert!'),
        html: alertMessage,
        icon: 'warning',
        confirmButtonColor: '#4acb6f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: Helper.translate('Ok'),
        showCancelButton: false,
        showConfirmButton: true
    })
}
Helper.unAuthAlert = () => {
    let alertMessage = `
        ${Helper.translate('Please login or create an account to continue.')}
    `
    Swal.fire({
        title: Helper.translate('Alert!'),
        html: alertMessage,
        icon: 'warning',
        confirmButtonColor: '#4acb6f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: Helper.translate('Ok'),
        showCancelButton: false,
        showConfirmButton: true
    })
}

Helper.MBSIZE = (1024 ** 2);
Helper.MAXTIPS = 500;

export default Helper