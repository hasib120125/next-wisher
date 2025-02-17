<template>
    <Head :title="Helper.translate('Talents profile')" />
    <Master>
        <div 
            
            :class="route().current('item.preview') ? 'fixed top-0 left-0 w-full h-full bg-slate-800/80 z-50 flex items-center justify-center' : 'xl:pt-14 pt-4'"
            @click.self="backSelf(route().current('item.preview'))"
        >
            <button 
                v-if="route().current('item.preview')"
                @click="back" 
                type="button" 
                class="md:w-12 md:h-12 w-8 h-8 flex items-center justify-center rounded-full mb-4 absolute bg-red-500"
                :class="route().current('item.preview') ? 'bg-red-500 text-white right-5 top-5' : 'right-full mr-2'"
            >
                <CloseIcon v-if="route().current('item.preview')" class="!w-4 !h-4" />
                <AngleLeftIcon v-else />
            </button>

            <div 
                class="px-4"
                :class="route().current('item.preview') ? 'bg-white rounded-md flex py-5 xl:max-w-[75vw] justify-center w-full max-h-[80vh] h-full overflow-y-auto' : ''"
            >
                <div class="container mx-auto relative xl:max-w-[1000px] max-w-full  w-full">
                    <!-- <button
                        v-if="!route().current('item.preview')"
                        @click="back" 
                        type="button" 
                        class="md:w-12 md:h-12 w-6 h-6 flex items-center justify-center rounded-full mb-4 absolute sm:top-0 sm:left-0 -left-1 -top-10 z-20"
                        :class="route().current('item.preview') ? 'bg-red-500 text-white left-full ml-5' : 'right-full mr-2'"
                    >
                        <CloseIcon v-if="route().current('item.preview')" />
                        <AngleLeftIcon v-else class="md:-translate-x-[140px]" />
                    </button> -->
                    <TalentProfileInfo 
                        class="md:hidden block md:max-w-full max-w-[350px] mx-auto mb-6" 
                        :talent="talent"
                    />
                    <div 
                        class="flex md:flex-row flex-col md:items-start items-center gap-6"
                        :class="{'pointer-events-none items-center': route().current('item.preview')}"
                    >

                        <div class="video customRatio md:w-[350px] w-full flex-shrink-0 height-[402px] overflow-hidden relative">
                            <div class="video customRatio height-[402px] overflow-hidden relative">
                                <Video 
                                    v-if="talent.video_path"
                                    :src="`${$page.props.asset}${talent.video_path}`" 
                                    class="w-full absolute bottom-0"
                                />
                            </div>
                        </div>


                        <div class="flex flex-col gap-2 md:max-w-full max-w-[350px]">  
                            <TalentProfileInfo class="md:block hidden" :talent="talent" />

                            <div class="lg:flex block items-end gap-6 mb-4">
                                <TalentProfileLinks :talent="talent" class="sm:w-[300px] w-full flex-grow-0 flex-shrink-0" />
                                <MoneyBack class="lg:max-w-full sm:max-w-[300px] max-w-full lg:my-0 my-6" />
                            </div>

                            <!-- <button 
                                @click="get($page.props, 'auth.user.role') == 'user' ? handleFollow(talent) : null" 
                                :disabled="followForm.processing" type="button"
                                :class="{'pointer-events-none': get($page.props, 'auth.user.role') != 'user'}"
                                class="self-end flex items-center sticky bottom-4 bg-[var(--btn-bg-color)] text-[var(--btn-text-color)] px-8 py-2 text-xl rounded font-semibold shadow mx-auto disabled:opacity-80"
                            >
                                <LoaderIcon v-if="followForm.processing" />
                                <template v-if="talent.isFollow">
                                    {{ Helper.translate('Unfollow') }}
                                </template>
                                <template v-else>
                                    {{ Helper.translate('Follow') }}
                                </template>
                            </button> -->
                        </div>
                        <!-- <div class="calendar flex flex-col">
                            <div class="bg-red-100 h-full relative">
                                <CalendarPreview :calendars="talent.calendars" />
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <Modal v-if="tipsModal" />
    </Master>
</template>

<script setup>
import Master from './Master.vue'
// import Video from '@/Components/Global/Video.vue'
import { useForm, Head } from '@inertiajs/inertia-vue3'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import { get } from 'lodash'
import Helper from '@/Helper'
import CalendarPreview from '@/Components/Backend/TalentDashboard/CalendarPreview.vue'
import LoaderIcon from '@/Components/Global/Icons/LoaderIcon.vue'
import Modal from '@/Components/Library/Modal.vue'
import { ref } from 'vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import Video from '@/services/Video.vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'
import MoneyBack from '@/Components/Global/MoneyBack.vue'
import TalentProfileLinks from '@/Components/Frontend/TalentProfileLinks.vue'
import TalentProfileInfo from '@/Components/Frontend/TalentProfileInfo.vue'

const props = defineProps({
    talent: Object,
})

const tipsModal = ref(false);



const followForm = useForm({})
const handleFollow = (talent) => {
    axios.post(route('item.followTalents', talent.id))
        .then(res => {
            talent.isFollow = res.data?.isFollow
            toast.success(`${Helper.translate(res.data?.message)} ${talent.name}`)
        })
}
const back = () => {
    history.back()
}
const backSelf = (isPreview=false) => {
    if (isPreview) {
        history.back()
    }
}
</script>