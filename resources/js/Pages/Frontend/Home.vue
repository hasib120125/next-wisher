<template>
    <Master>

        <Head :title="Helper.translate('Home')" />
        <div class="wrapper grid h-[100vh] overflow-y-auto">
            <div class="p-4 bg-[#0b021a] relative flex items-center justify-center h-full">
                <CanvasMenu />
                <Search class="absolute top-[250px]" />
            </div>

            <div class="py-10 px-8 bg-[#1c2130] text-white relative">
                <div class="flex justify-between items-center">
                    <div></div>
                    <div>
                        <div v-if="isLogin" class="flex gap-4 items-center justify-end">
                            <Notification />
                            <Profile />
                        </div>
                        <button v-else @click="showAuthModal = true" class="font-black lg:px-6 px-3 py-2 border border-white border-opacity-50 text-white text-opacity-60 hover:text-opacity-100 rounded-full">
                            {{ Helper.translate('Sign up') }}
                        </button>
                    </div>
                </div>
                <div class="flex w-full h-full items-center justify-center">
                    <div class="text-center flex flex-col gap-3">
                        <h1 class="font-black text-5xl">{{ Helper.translate('Calendars & Special videos') }}</h1>
                        <h2 class="text-white text-opacity-30 text-3xl font-semibold">
                            {{ Helper.translate('For any occasion, from your preferred personality') }}
                        </h2>
                    </div>
                </div>
                <!-- <div class="grid mb-8 relative">
                    <div class="absolute w-full top-1/2 left-0 right-0 flex justify-between pointer-events-none">
                        <button @click="handleCategoryPear(-1)" class="-ml-5 opacity-50 hover:opacity-100  pointer-events-auto">
                            <AngleLeftIcon />
                        </button>
                        <button @click="handleCategoryPear(+1)" class="-mr-5 opacity-50 hover:opacity-100  pointer-events-auto">
                            <AngleRightIcon />
                        </button>
                    </div>
                    <div class="grid mt-10 gap-5 px-[30px]">
                        <template v-for="(category, index) in categoryPear" :key="index">
                            <CategoryWiseLatestItem @onClick="showAuthModal = true" :talents="category.talents" :title="category.name" :category="category" />
                        </template>
                    </div>

                </div> -->
                <Footer class="mt-20 absolute bottom-0 left-0 w-full" />
            </div>
        </div>

        <Modal v-model="showAuthModal">
            <CustomAuth @close="showAuthModal = false" />
        </Modal>
    </Master>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, usePage } from '@inertiajs/inertia-vue3'
import Master from '@/Components/Frontend/Master.vue'
import CategoryWiseLatestItem from '@/Components/Frontend/CategoryWiseLatestItem.vue'
import Search from '@/Components/Frontend/Home/Search.vue'
import CanvasMenu from '@/Components/Frontend/Home/CanvasMenu.vue'
import Modal from '@/Components/Library/Modal.vue'
import CustomAuth from '@/Components/Frontend/CustomAuth/Index.vue'
import Notification from '@/Components/Backend/Global/Components/Notification.vue'
import Profile from '@/Components/Backend/Global/Components/Profile.vue'
import Footer from '@/Components/Backend/Global/Footer.vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import Helper, { showAuthModal } from '@/Helper'
import { values, size, get } from 'lodash'

const props = defineProps({
    isLogin: Boolean,
    categories_with_talent: Array
})

const currentPosition = ref(0)

const categoryPear = ref([])
const pearArray = ref([])

onMounted(() => {
    handleCategoryPear(0)
})

const handleCategoryPear = (direction) => {
    currentPosition.value += (direction + (1 * direction));
    if (direction < 0 && currentPosition.value < 0) {
        currentPosition.value = size(props.categories_with_talent) - 1;
    }

    if (currentPosition.value > size(props.categories_with_talent) - 1) {

        currentPosition.value = 0;
    }
    categoryPear.value = values(props.categories_with_talent).slice(currentPosition.value, currentPosition.value + 2);
}

</script>

<style scoped>
.wrapper {
    grid-template-columns: 2fr 3fr;
}
</style>