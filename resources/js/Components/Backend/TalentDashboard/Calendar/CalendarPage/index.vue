<template>
  <div class="cover_photo wrapper h-full relative">
    <div 
      class="calendar shadow" 
      :class="(page.name !== 'cover' && page.name !== 'end') ? 'h-[60%]' : 'h-full'"
      id='calendar_main_wrapper'
    >
      <img 
        v-if="!isEmpty(page.path)"
        class="back_image h-full w-full block object-cover object-center"
        :src="page.path"
        alt='' 
      />
      <MoveableBox :selectedPageIndex="selectedPageIndex" />
    </div>
    <Calendar 
      v-if="page.name !== 'cover' && page.name !== 'end'"
      :selectedPageIndex="selectedPageIndex"
    />
  </div>
</template>

<script setup>
  import { computed } from 'vue'
  import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
  import Calendar from '@/Components/Backend/TalentDashboard/Calendar/CalendarPage/Calendar.vue'
  import MoveableBox from '@/Components/Backend/TalentDashboard/Calendar/MoveableBox/index.vue'
  import { isEmpty } from 'lodash'

  const props = defineProps({
    selectedPageIndex: {
      type: [Number, String],
      default: 0
    }
  })
  const { getSelectedPage } = useCalendar()
  const page = computed(() => getSelectedPage(props.selectedPageIndex))
</script>

<style scoped>
.wrapper{
  background-color: #fff;
  min-width: 500px;
  max-width: 500px;
  height: 800px;
  margin: auto;
  aspect-ratio: 1/1.6;
  overflow: hidden;
}
.calendar{
  background-color: #fff;
}
.body{
  height: 100%;
}
.calendar .image{
  height: 60%;
  background-color: #fff;
}
.calendar .image img{
  height: 100%;
  width: 100%;
  object-fit: cover;
  object-position: center;
}

.cover_image,
.back_image{
  aspect-ratio: 1/1.4;
  object-fit: cover;
}
</style>