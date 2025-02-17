<template>
  <nav class="p-4 bg-white border-b px-10 z-50 sticky top-0">
    <div class='flex' :class="!previewMode && 'justify-between'">
      <div class="flex gap-4">
        <Link v-if="!previewMode" :href="route('talent.dashboard')" class="flex gap-2 items-center font-semibold">
          <DashboardIcon class="w-5 h-5" />
          {{ Helper.translate('Dashboard') }}
        </Link>
        <button v-if="$page.component == 'Calendar/Create' && !previewMode" @click="activeComponent = 'CalendarConfig'" class="flex gap-2 items-center font-semibold">
          <SettingsIcon class="w-5 h-5" />
          {{ Helper.translate('Create') }}
        </button>
        <Link 
          v-if="$page.component != 'Calendar/Create' && !previewMode"
          :href="route('calendar')" 
          class="flex gap-2 items-center font-semibold"
        >
          <SettingsIcon class="w-5 h-5" />
          {{ Helper.translate('Create') }}
        </Link>

        <button v-if="!previewMode" @click="activeComponent = 'Media'" class="flex gap-2 items-center font-semibold">
          <ImageIcon class="w-5 h-5" />
          {{ Helper.translate('My Files') }}
        </button>
        <button v-if="!previewMode" @click="addText" class="flex gap-2 items-center font-semibold">
          <TextIcon class="w-4 h-4" />
          {{ Helper.translate('Add Text') }}
        </button>
        <button v-if="!previewMode" @click="showGuid=true" class="flex gap-2 items-center font-semibold border border-red-500 rounded px-2 bg-red-500 text-white">
          {{ Helper.translate('Guide') }}
        </button>
      </div>
      
      <div class='flex gap-4 items-center font-medium'>
        <button 
          v-if="$page.component == 'Calendar/Create'"
          @click="previewMode = !previewMode"
          :class="previewMode ? 'text-black' : ''"
        >
          {{ !previewMode ? Helper.translate("Preview") : Helper.translate("Back") }}
        </button>

        <button 
          v-if="$page.component == 'Calendar/Create' && !previewMode"
          class='bg-green-500 text-white rounded-full shadow py-1 px-4'
          @click="saveCalendar"
        >
          {{ Helper.translate('Save') }}
        </button>
        <!-- <Link 
          v-if="$page.component != 'Calendar/Create' && !previewMode"
          :href="route('calendar')"
        >
          Create
        </Link> -->
        <Link v-if="$page.component != 'Calendar/List' && !previewMode" :href="route('my_calendars')">
          {{ Helper.translate('My calendars') }}
        </Link>
      </div>
    </div>
  </nav>

  <div v-if="showGuid" class="bg-slate-100 backdrop-blur fixed top-0 left-0 w-full h-full z-[9990] overflow-y-auto shadow-lg">
    <div class="bg-white w-full px-10 py-3 sticky top-0">
      <button @click="showGuid=false" class="py-1 px-3 font-semibold flex items-center gap-1">
        <AngleLeftIcon class="w-4 h-4" />
        {{ Helper.translate('Back') }}
      </button>
    </div>
    <div class="bg-white p-5 rounded w-full max-w-[90vw] mx-auto my-5" v-if="guide">
      <h2 class="text-1xl font-bold mb-4">{{ Helper.translate(guide.title, true) }}</h2>
      <div v-html="guide[language=='english' ? 'description' : language]"></div>
    </div>
  </div>
</template>

<script setup>
import SettingsIcon from '@/Icons/SettingsIcon.vue'
import ImageIcon from '@/Icons/ImageIcon.vue'
import TextIcon from '@/Icons/TextIcon.vue'
import useLeftSidebar from './LeftSidebar/useLeftSidebar'
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import { Link } from '@inertiajs/inertia-vue3'
import DashboardIcon from '@/Icons/DashboardIcon.vue'
import Helper from '@/Helper'
import { ref, onMounted } from 'vue'
import { size } from 'lodash'
import axios from 'axios'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'


const language = ref('english')
const calendarData = ref('')
onMounted(async ()=> {
  const data = await axios.get(`pages`, {params: {type:'calender-guide'}})
  if(data.status){
    calendarData.value = data.data[0]
  }
  if (localStorage.language) {
      language.value = localStorage.language
  }
})

const guideModalToggle = ref(false)
const { activeComponent } = useLeftSidebar()
const { saveCalendar, calendarPayload, previewMode, getSelectedPage } = useCalendar()

const addText = () => {
  activeComponent.value = 'TextConfig'
  let selectedPage = getSelectedPage(calendarPayload.value.selectedPageIndex)
    selectedPage.text.title = 'Add Text'
}

const guide = ref(null)
const showGuid = ref(false)
onMounted(()=>{
  if (localStorage.language) {
    language.value = localStorage.language
  }
  axios.get(`pages?type=calender-guide`)
            .then(res => {
                if (size(res.data)==1) {
                    guide.value = res.data[0]
                }
            })
})

</script>