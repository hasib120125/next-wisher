  <template>
  <Draggable @dragend="updateStyle" :init="getInitialPosition" :moveable="!isDisabled">
    <div 
      class='pointer-events-none relative'
      :style="getStyle"
    >
      <button v-if="isDisabled" class='absolute -top-6 pointer-events-auto block' @click="isDisabled = false">
        <svg width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#000" strokeWidth="2" d="m6 13 4.2 3.6L18 7"/></svg>
      </button>
      <textarea 
        ref="textarea"
        title='Double Click to Edit'
        class='pointer-events-auto absolute top-0 z-30 p-0 border-none text-left bg-transparent'
        :class="!isDisabled ? 'resize-none cursor-move' : 'resize cursor-auto'"
        @input="autoResize"
        style="font-size: inherit; color: inherit; text-decoration: inherit; text-align: inherit;"
        v-model="calendarPayload.settings[selectedPageIndex].text.title"
        :readOnly="!isDisabled"
        @dblclick="isDisabled = true"
        @blur="isDisabled = false"
      ></textarea>
    </div>
  </Draggable>
</template>

<script setup>
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import { computed, ref } from 'vue'
import { cloneDeep } from 'lodash'
import Draggable from '@/Components/Library/Draggable.vue'

const props = defineProps({
  selectedPageIndex: {
    type: [Number, String],
    default: 0
  }
})

const { calendarPayload } = useCalendar()
const isDisabled = ref(false)
const textarea = ref()


const getStyle = computed(() => {
  let style = calendarPayload.value.settings[props.selectedPageIndex].text.style
  let myStyle = cloneDeep(style)
  myStyle.fontSize += 'px'
  myStyle.top += 'px'
  myStyle.left += 'px'
  return myStyle
})

const getInitialPosition = computed(() => {
  const {left, top} = calendarPayload.value.settings[props.selectedPageIndex].text.style
  return {
    left, top
  }
})

const updateStyle = ({left, top}) => {
  let style      = calendarPayload.value.settings[props.selectedPageIndex].text.style
      style.top  = top
      style.left = left
}

function autoResize() {
  textarea.value.style.height = "auto";
  textarea.value.style.height = textarea.value.scrollHeight + "px";
}
</script>