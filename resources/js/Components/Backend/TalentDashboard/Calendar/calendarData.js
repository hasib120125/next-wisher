import { cloneDeep } from 'lodash'
import { ref } from 'vue'

let DemoImages = [
    'https://images.unsplash.com/photo-1665686308827-eb62e4f6604d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669702055322-4813687f30c7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669811241780-218e5ac908ef?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzM3x8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669805734206-af04c69f988b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzOHx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669723008642-b00fa9d10b76?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0NHx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://plus.unsplash.com/premium_photo-1663047346199-9516fe33fce1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw3M3x8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669673748084-605b2cbb1330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw4NXx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669725337418-f81459093abd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw4OXx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1665686310429-ee43624978fa?ixlib=rb-4.0.3&ixid=MnwxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxMDB8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669639613055-7c5a1b28eddd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMDJ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669630814501-9b0dd220a763?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMDZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669721147818-8e61322954d9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMTZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669635449285-7b7502cf8692?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMjh8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669735873083-b862e5d7394f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxNjZ8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1658273114792-61abaaa69f1b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxNzN8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669665465893-422c2fbc714f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxOTB8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669708620518-9d7f309118b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyMDB8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669665465818-51a667863853?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyMDl8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669570094762-828f3dfaf675?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyMTd8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669555514519-261ee068c278?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyNDV8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669543295261-ba78b679ca3d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyNTF8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669602447755-74c563399373?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyNjl8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1669604274367-ff17a0ce20cc?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyNzF8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://plus.unsplash.com/premium_photo-1664360971614-593e3a23d8df?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8aGlsbHN8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1445363692815-ebcd599f7621?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8aGlsbHN8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1471958680802-1345a694ba6d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1580917388462-2e09d69dff51?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTJ8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1584293406330-efdc2a30a0a3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjF8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1584392394119-415a6c774964?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjN8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1576629142406-d2cd7f3c73f4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MzZ8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1584293406238-ad97f2ac6753?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NDF8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1603335610070-f088b0fa1aca?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NzB8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1541675154750-0444c7d51e8e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8ODR8fGhpbGxzfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1433477077279-9354d2d72f6b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTYwfHxoaWxsc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1491497895121-1334fc14d8c9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTY4fHxoaWxsc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1558138818-d44c4dea7a6a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjA5fHxoaWxsc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://plus.unsplash.com/premium_photo-1669754166472-d5867f2dc924?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjM1fHxoaWxsc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
    'https://images.unsplash.com/photo-1557968790-e56b3d76dbaf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjUxfHxoaWxsc3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
]

// this weeks data also available in calendarState.jsx
let defaultWeeksList = [
    {
        key: 0,
        value: 'Sunday'
    }, 
    {
        key: 1,
        value: 'Monday'
    }, 
    {
        key: 2,
        value: 'Tuesday'
    }, 
    {
        key: 3,
        value: 'Wednesday'
    }, 
    {
        key: 4,
        value: 'Thursday'
    }, 
    {
        key: 5,
        value: 'Friday'
    },
    {
        key: 6,
        value: 'Saturday'
    }
]
const weeksList = ref(cloneDeep(defaultWeeksList))

const listOfMonth = [
    {
        key: 0,
        value: 'January'
    },
    {
        key: 1,
        value: 'February'
    },
    {
        key: 2,
        value: 'March',
    },
    {
        key: 3,
        value: 'April',
    },
    {
        key: 4,
        value: 'May',
    },
    {
        key: 5,
        value: 'June',
    },
    {
        key: 6,
        value: 'July',
    },
    {
        key: 7,
        value: 'August',
    },
    {
        key: 8,
        value: 'September',
    },
    {
        key: 9,
        value: 'October',
    },
    {
        key: 10,
        value: 'November',
    },
    {
        key: 11,
        value: 'December',
    }
]
const languageList = [
    {
        key: 'english',
        value: 'English'
    }, 
    {
        key: 'french',
        value: 'French'
    }, 
    {
        key: 'spanish',
        value: 'Spanish'
    },
    {
        key: 'portugues',
        value: 'Portuguese'
    }
]
const yearList = []

const defaultTextConfig = {
    position: 'absolute',
    top: 0,
    left: 0,
    color: '#000000',
    textAlign: 'center',
    fontSize: 20,
    fontWeight: '400',
    fontFamily: '',
    textDecoration: 'none'
}

const sampleTitle = ''
const DemoImageListForCalendar = [
    {
        name: 'cover', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'January', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'February', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'March', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'April', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'May', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'June', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'July', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'August', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'September', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'October', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'November', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'December', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
    {
        name: 'end', 
        path: '', 
        text: {
            title: sampleTitle,
            style: cloneDeep(defaultTextConfig)
        }
    },
]


// create year list start------
let selectedYear = new Date().getFullYear()
let nextYearLimit = selectedYear + 17

for(selectedYear; selectedYear <= nextYearLimit; selectedYear++){
    yearList.push({
        key: selectedYear,
        value: selectedYear
    })
}


export { 
    defaultWeeksList,
    weeksList, 
    languageList, 
    yearList, 
    listOfMonth,
    DemoImages,
    DemoImageListForCalendar
}