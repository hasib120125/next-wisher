<template>
    <div v-if="!isEmpty(getSelectedMail)">
        <div class="flex lg:flex-nowrap flex-wrap gap-5">
            <div class="relative w-full" v-if="getSelectedMail.mailFor == 'calender'">
                <template v-if="!isEmpty(getCalendar(getSelectedMail))">
                    <img :src="getImage(getCalendar(getSelectedMail))" alt="" class="h-full w-full block object-cover customRatio">
                    <h2 v-if="getTitle(getCalendar(getSelectedMail))" class="absolute bottom-0 p-4 bg-white w-full bg-opacity-50 backdrop-blur-md font-semibold truncate">
                        {{ Helper.translate(getTitle(getCalendar(getSelectedMail)), true) }}
                    </h2>
                </template>
            </div>
            <video 
                v-if="getSelectedMail.attachment && $page.props.auth.user.role == 'talent' && getSelectedMail.mailFor == 'wish'"
                :src="`${$page.props.asset}${getSelectedMail.attachment}`"
                class="max-w-[300px] w-full relative"
                controls
            ></video>
            <video
                v-else-if="getSelectedMail.attachment && +get(getSelectedMail, 'talent_earning.download_status') == 1 && getSelectedMail.mailFor == 'wish'"
                :src="`${$page.props.asset}${getSelectedMail.attachment}`"
                class="max-w-[300px] w-full relative"
                controls
            ></video>
            <div v-else-if="getSelectedMail.attachment" class="w-full max-w-[300px] relative h-[350px] rounded">
                <div class="bg-black/30 rounded absolute top-0 left-0 right-0 bottom-0 backdrop-blur flex items-center justify-center">
                    <a 
                        v-if="!isEmpty(getSelectedMail.attachment) && $page.props.auth.user.role == 'user'" 
                        @click="handleReload"
                        target="_blank"
                        :href="`${$page.props.asset}${getSelectedMail.attachment}/download/${getSelectedMail.id}`" 
                        class="bg-blue-500 mt-2 font-bold text-white rounded px-4 py-1 emt-4 block w-fit"
                    >
                        <template v-if="isExpired()">
                            {{ Helper.translate('Download') }}
                        </template>
                        <template v-else>
                            {{ Helper.translate('Download') }}
                        </template>
                    </a>
                </div>
                <img class="w-full h-full object-cover" src="/black-video.png" alt="">
            </div>

            <div class="w-full">
                <div v-if="selectedMailContent.role == 'user'" class="font-bold text-orange-500 mb-2">
                    {{ get(getSelectedMail, 'talent.username') }}
                </div>
                <div class="justify-between w-full">
                    <h1 v-if="getSelectedMail.fulfilled" class="font-bold text-green-500">
                        {{ Helper.translate('REQUEST FULFILLED') }}
                    </h1>
                    <h1 v-else-if="isExpired()" class="font-bold text-red-500">
                        {{ Helper.translate('REQUEST EXPIRED') }}
                    </h1>
                    <div class="flex gap-2" v-if="getSelectedMail.name">
                        <h1 class="font-bold">{{ Helper.translate('Name') }}:</h1>
                        {{ getSelectedMail.name }}
                    </div>
                </div>
                <div class="flex gap-2" v-if="getSelectedMail.for">
                    <h1 class="font-bold">{{ Helper.translate('For') }}:</h1>
                    <span class="capitalize">{{ getSelectedMail.for }}</span>
                </div>
                <div class="flex gap-2" v-if="getSelectedMail.from">
                    <h1 class="font-bold">{{ Helper.translate('From') }}:</h1>
                    <span class="capitalize">{{ getSelectedMail.from }}</span>
                </div>
                <div class="flex gap-2" v-if="getSelectedMail.genders">
                    <h1 class="font-bold">{{ Helper.translate('Gender') }}:</h1>
                    <span class="capitalize">{{ Helper.translate(getSelectedMail.genders) }}</span>
                </div>
                <div class="flex gap-2" v-if="getSelectedMail.occasion">
                    <h1 class="font-bold">{{ Helper.translate('Occasion') }}:</h1>
                    <span class="capitalize">{{ Helper.translate(getSelectedMail.occasion) }}</span>
                </div>
                <div class="grid gap-2 mt-6" v-if="getSelectedMail.instructions">
                    <h1 class="font-bold" v-if="getSelectedMail.role == 'user'">
                        {{ Helper.translate('Instructions') }}:
                    </h1>
                    <h1 class="font-bold" v-else>
                        {{ Helper.translate('Message') }}:
                    </h1>
                    <div v-html="getSelectedMail.instructions" class="grid gap-2"></div>
                </div>

                <a 
                    v-if="!isEmpty(getSelectedMail.attachment) && +get(getSelectedMail, 'talent_earning.download_status') == 1 && $page.props.auth.user.role == 'user'" 
                    target="_blank"
                    @click="handleReload"
                    :href="`${$page.props.asset}${getSelectedMail.attachment}/download/${getSelectedMail.id}`" 
                    class="bg-blue-500 mt-2 font-bold text-white rounded px-4 py-1 emt-4 block w-fit"
                >
                    <template v-if="isExpired()">
                        {{ Helper.translate('Download') }}
                    </template>
                    <template v-else>
                        {{ Helper.translate('Download') }}
                    </template>
                </a>
                <Link 
                    v-if="!isEmpty(getCalendar(getSelectedMail))" target="_blank" 
                    :href="route('calendar.download', getCalendarId(getSelectedMail))"
                    class="bg-blue-500 font-bold text-white rounded px-4 py-1 mt-4 block w-fit"
                >
                    {{ Helper.translate('Download Calendar') }}
                </Link>
                <RatingForm
                    v-if="isEmpty(ratings) && !isEmpty(getSelectedMail.attachment) && +get(getSelectedMail, 'talent_earning.download_status') == 1 && $page.props.auth.user.role == 'user'"
                    :userId="selectedMailContent.user_id"
                    :talentId="selectedMailContent.talent_id"
                    :talentEarningId="selectedMailContent.talent_earning_id"
                    :talent="selectedMailContent.talent"
                />
            </div>
        </div>
    </div>
    <Alert v-else title="No messages" />
</template>

<script setup>
import Helper from '@/Helper'
import useMail from '@/Pages/Backend/Mail/useMail'
import { isEmpty, get } from 'lodash'
import { computed, onMounted, watchEffect } from 'vue'
import Alert from '@/Components/Global/Alert.vue'
import CalendarPreview from '../TalentDashboard/CalendarPreview.vue'
import { Link } from '@inertiajs/inertia-vue3'
import RatingForm from '@/Components/Backend/UserDashboard/RatingForm.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { ratings, getRatings, loadingRatings } from '@/Components/Backend/UserDashboard/useRatings.js'
import { Inertia } from '@inertiajs/inertia'

const { getSelectedMail, isExpired, selectedMailContent } = useMail()
const page = usePage()
const userId = computed(() => {
    return page.props.value.auth.user.id
})
const getCalendar = (item) => {
    if(!isEmpty(item.settings)){
        const { calendar } = JSON.parse(item.settings)
        return calendar.settings[0]
    }
    return false
}
const getCalendarId = (item) => {
    if(!isEmpty(item.settings)){
        const { calendar } = JSON.parse(item.settings)
        return calendar.id
    }
    return false
}
const getImage = (item) => {
    return item.path
}
const getTitle = (item) => {
    return item.text.title
}

const handleReload = () => {
    // Inertia.reload()
    setTimeout(() => {
        location.reload()
    }, 200)
}


watchEffect(() => {
    getRatings({
        userId: selectedMailContent.value?.user_id, 
        talentId: selectedMailContent.value?.talent_id,
        talentEarningId: selectedMailContent.value?.talent_earning_id
    })
})
</script>