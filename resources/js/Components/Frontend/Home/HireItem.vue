<template>
    <div v-if="item" class="bg-slate-900/80 border border-slate-500 relative customRatio overflow-hidden">
        <div
            class="w-full h-full block relative cursor-pointer"
        >
            <!-- <span v-if="isLoadingImage" class="w-8 h-8 absolute -ml-4 -mt-4 top-1/2 left-1/2 z-20 animate-spin">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span> -->
            <img 
                @load="isLoadingImage=false"
                onerror="this.src='/no-image-found.webp'"
                :src="`${$page.props.asset}${get(item, 'profile_image')}`" 
                class="w-full h-full object-cover object-center"
            />
            <!-- <img
                src="no-image-found.webp" 
                class="w-full h-full object-cover object-center"
            /> -->
            <div class="absolute bottom-0 w-full">
                <div class="text-[#efefef] bg-[#000547]">
                    <div class="px-4 py-0.5 truncate w-full text-center">
                        {{ item.username }}
                    </div>
                    <div class="px-4 truncate w-full font-light text-sm text-center">
                        {{ Helper.translate(get(item, 'category.name')) }}
                        {{ get(item, 'subcategory.name') ? `/${Helper.translate(get(item, 'subcategory.name'))}` : '' }}
                    </div>
                    <slot name="action">
                        <button @click="$emit('onRequest')" class="px-4 py-2 pb-3 font-semibold text-center block w-full">
                            Request
                        </button>
                    </slot>
                </div>
            </div>
        </div>
        <!-- <div class="w-full h-full block relative cursor-pointer">
            <img class="w-full h-full object-cover object-center" src="/no-image-found.webp" alt="">
        </div> -->
    </div>
</template>
<script setup>
import { ref } from "vue";
import { get } from 'lodash'
import Helper from "@/Helper";
const props = defineProps({
    item: {
        type: Object,
        default: null
    }
})

const isLoadingImage = ref(true)


</script>