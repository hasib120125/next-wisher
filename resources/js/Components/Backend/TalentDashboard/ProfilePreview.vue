<template>
    <div class="px-4">
        <div class="container mx-auto py-5 relative">
            <button @click="back" type="button" class="mb-4 absolute right-full mr-2">
                <AngleLeftIcon />
            </button>
            <div class="grid grid-cols-3 gap-6">
                <div class="video">
                    <!-- <img @click="videoModel=true" :src="`${get($page.props, 'asset')}${talent.profile_image}`" alt=""> -->
                    <Video :poster="talent.video_path" :hasCover="false" :load="true" :cover="talent.profile_image" />
                </div>
                <div class="grid grid-rows-3 items-baseline">
                    <div>
                        <h1 class="text-xl font-bold">{{ Helper.translate(talent.name, true) }}</h1>
                        <h2 class="text-gray-500">
                            {{ Helper.translate(get(talent, 'category.name')) }} /
                            {{ Helper.translate(get(talent, 'subcategory.name')) }}
                        </h2>
                    </div>

                    <div class="grid gap-4">
                        <Link v-if="$page.props.wish" :href="route('payment.info', { talentId: talent.id })" class="px-4 py-2 rounded text-xl bg-red-600 text-white font-bold text-center">
                            {{ Helper.translate('Personalized video') }}: {{ Helper.priceFormate($page.props.wish.amount) }}
                        </Link>
                        <Link v-if="$page.props.mylife" :href="route('payment.subscribe', { talentId: talent.id })" class="px-4 py-2 rounded text-xl bg-black text-white font-bold text-center">
                            {{ Helper.translate('My Life') }}
                        </Link>
                        <Link v-if="$page.props.tips" :href="route('payment.tips.amount', { talentId: talent.id, name: String(talent.name).replace(' ', '-') })" class="px-4 py-2 rounded text-xl bg-sky-500 text-white font-bold text-center">
                            {{ Helper.translate('Tip') }}
                        </Link>
                    </div>

                    <button @click="handleFollow(talent.id)" :disabled="followForm.processing" type="button" class="self-end flex items-center sticky bottom-4 bg-[var(--btn-bg-color)] text-[var(--btn-text-color)] px-8 py-2 text-xl rounded font-semibold shadow mx-auto disabled:opacity-80">
                        <LoaderIcon v-if="followForm.processing" />
                        <template v-if="talent.isFollow">
                            {{ Helper.translate('Unfollow') }}
                        </template>
                        <template v-else>
                            {{ Helper.translate('Follow') }}
                        </template>
                    </button>
                </div>
                <div class="calendar flex flex-col">
                    <div class="bg-red-100 h-full relative">
                        <CalendarPreview :calendars="talent.calendars" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Modal v-if="tipsModal" />
</template>

<script setup>
import Video from '@/Components/Global/Video.vue';
import { Link, useForm, Head } from '@inertiajs/inertia-vue3';
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import { get } from 'lodash';
import Helper from '@/Helper';
import CalendarPreview from '@/Components/Backend/TalentDashboard/CalendarPreview.vue'
import LoaderIcon from '@/Components/Global/Icons/LoaderIcon.vue'
import Modal from '@/Components/Library/Modal.vue'
import { onMounted, ref } from 'vue';

const props = defineProps({
    talent: Object,
});

const tipsModal = ref(false);
const followForm = useForm({})

const handleFollow = (id) => {
    followForm.post(route('item.followTalents', id));
}

const back = () => {
    history.back()
}


</script>