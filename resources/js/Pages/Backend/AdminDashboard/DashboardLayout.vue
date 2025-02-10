<template>
    <Master :header="header" :footer="footer">
        <div class="px-4">
            <div class="container mx-auto py-5">
                <div class="grid content min-h-[80vh] gap-4">
                    <div v-if="!isForMobile" class="border p-4 md:block hidden">
                        <slot name="leftSidebar"></slot>
                    </div>
                    
                    <div 
                        v-if="isForMobile && toggleLeftSidebar" 
                        @click="toggleLeftSidebar = false" 
                        class="border bg-black/60 fixed top-0 left-0 z-50 h-full w-full backdrop-blur-sm"
                    >
                        <div class="bg-white w-[300px] h-full p-4">
                            <slot name="leftSidebar"></slot>
                        </div>
                    </div>

                    <div>
                        <div class="header">
                            <div class="text-center flex justify-center">
                                <Logo :light="false" />
                            </div>
                            <h2 v-if="title" class="md:text-lg mb-2 uppercase md:text-left text-center font-bold">{{ Helper.translate(title) }}</h2>
                        </div>
                        <div class="border p-6 flex flex-col gap-6 min-h-[90.5%] md:overflow-y-auto md:mt-0 mt-10 relative">
                            <button @click="toggleLeftSidebar = !toggleLeftSidebar" class="absolute -top-10 left-0 md:hidden block">
                                <ThreeDotWithCircle class="w-8 h-8 text-gray-500" />
                            </button>
                            <slot name="content"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import Logo from '@/Components/Global/Logo.vue'
import Master from '../Master.vue'
import Helper from '@/Helper'
import { onBeforeMount, ref } from 'vue'
import ThreeDotWithCircle from '@/Icons/ThreeDotWithCircle.vue'

const toggleLeftSidebar = ref(false)
const isForMobile = ref(false)
const getWindowSize = () => {
    isForMobile.value = document.body.offsetWidth < 768
}
onBeforeMount(() => {
    window.addEventListener('resize', getWindowSize)
    getWindowSize()
})

defineProps({
    header: {
        type: Boolean,
        default: true
    },
    footer: {
        type: Boolean,
        default: true
    },
    title: String
})
</script>


<style scoped>
    @media all and (min-width: 768px){
        .content{
            grid-template-columns: 250px 1fr;
        }
    }
</style>