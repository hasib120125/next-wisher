<template>
    <div class="flex gap-5 items-start">
        <div class="w-52 h-[322px] relative">
            <Link :href="route('calendar.preview', item.id)">
                <div class="border h-full relative w-[200px] pointer-events-none">
                    <div 
                        class="cover_photo wrapper relative w-[500px] h-[800px]"
                        style="transform: scale(0.4); transform-origin: left top;"
                    >
                        <img 
                            v-if="getImage(item)"
                            class="h-full w-full block object-cover object-center"
                            :src="getImage(item)"
                            alt='' 
                        />
                        <h1 class="whitespace-nowrap" :style="getCoverTextStyle(item)">
                            {{ Helper.translate(getCoverName(item)) }}
                        </h1>
                    </div>
                </div>
            </Link>
        </div>

        <div class="grid gap-4">
            <div class="flex gap-4 items-baseline relative">
                <label>
                    $ <input type="number" min="0" class="py-1 border-gray-300 bg-transparent" v-model="price">
                </label>
                <button @click="handleCalendarPrice" class="px-4 py-1 bg-green-500 rounded shadow">
                    {{ Helper.translate('Update') }}
                </button>
                <button @click="deleteCalendar(item.id)" class="px-4 py-1 bg-red-500 text-white rounded shadow">
                    {{ Helper.translate('Delete') }}
                </button>
                
                <label v-if="$page.props.calendars.length > 1" class="flex gap-2 items-center cursor-pointer">
                    <input type="radio" value="1" name="saleable" v-model="item.is_salable" @click="(e) => makeCalendarSaleable(item, e)">
                    {{ Helper.translate('Move to profile') }}
                </label>
                <label v-if="$page.props.calendars.length == 1" class="flex gap-2 items-center cursor-pointer">
                    <input type="checkbox" @input="(e) => makeCalendarSaleable(item, e)" :checked="item.is_salable" >
                    {{ Helper.translate('Move to profile') }}
                </label>

                <Link :href="route('item.preview', $page.props.auth.user.id)" class="px-4 mt-10 py-2 rounded text-xl bg-black text-white font-bold text-center w-fit">
                {{ Helper.translate('Preview Profile') }}
                </Link>
                <div class="absolute top-full mt-2 left-0 text-red-500 text-xs">{{ errorMsg }}</div>
            </div>
            <!-- <CInput 
                placeholder="Enter calendar title"
                v-model="item.title"
                @input="updateCalendar(item)"
            /> -->
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Helper from '@/Helper'
import useCalendar from '@/Components/Backend/TalentDashboard/Calendar/useCalendar.js'
import { isEmpty, cloneDeep } from 'lodash'
import CInput from '@/Components/Global/CInput.vue'

const { updateCalendar, updateCalendarPrice, deleteCalendar, makeCalendarSaleable } = useCalendar()

const props = defineProps({
    item: Object
})
const price = ref(0)
const getCoverName = (item) => {
    return item.settings[0].text.title || ''
}
const getCoverTextStyle = (item) => {
    let style = cloneDeep(props.item?.settings[0].text.style) || {}
    
    if(isEmpty(style)) return ''
    style.fontSize += 'px'
    style.left += 'px'
    style.top += 'px'

    return style
}
const getImage = (item) => {
    return item.settings[0].path
}
const errorMsg = ref('')
const handleCalendarPrice  = () => {
    if(price.value < 10){
        errorMsg.value = Helper.translate(`The Minimum Amount for Calendar should be ${Helper.priceFormate(10)}`)
        return
    }
    errorMsg.value = ''
    updateCalendarPrice(price.value, props.item.id)
}

onMounted(() => {
    price.value = props.item.price
})
</script>