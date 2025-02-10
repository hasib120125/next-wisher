<template>
    <header class="bg-[var(--header-bg-color)] text-white px-4 py-1.5 max-lg:py-2 sticky top-0 z-50">
        <!-- <div class="navWrapper max-w-[1200px] mx-auto text-white items-center relative"> -->
        <div class="flex justify-between max-w-[1200px] mx-auto text-white items-center relative">
            <!-- <Navigation /> -->

            <!-- <div class="grid text-center gap-2"> -->
            <!-- <div class="flex md:pr-[50px] text-center gap-2"> -->
            <div></div>
            <div class="flex text-center gap-2">
                <Logo :light="true"/>
            </div>
            <div class="flex md:gap-4 gap-2 items-center justify-end">
                <Profile />
            </div>
            <!-- 

            <div v-if="showSearchBox" ref="target" class="searchbox absolute top-0 left-0 right-0 bg-white text-black h-full flex items-center">
                <label class="container mx-auto block relative">
                    <input
                        v-model="searchQuery"
                        @input="handleSearch"
                        v-autoFocus
                        type="search" :placeholder="`${Helper.translate('Search')}...`" 
                        class="w-full block border-0" 
                    />
                    <button @click="showSearchBox=false" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                        <CloseIcon class="w-4 h-4" />
                    </button>
                </label>
                <div v-if="size(searchResult)" class="w-full left-0 absolute top-[calc(100%+15px)] z-20">
                    <div class="bg-white divide-y divide-slate-300 shadow-xl overflow-hidden">
                        <Link 
                            v-for="(talent, index) in searchResult"
                            :key="index"
                            :href="route('item.details', {
                                id: talent.id,
                                username: `${String(talent.username).replaceAll(' ', '-')}`
                            })"
                            @click="cleanSearchQuery"
                            class="block py-1 px-2 hover:text-slate-800 hover:bg-slate-100 text-slate-700 capitalize"
                        >
                            {{ getName(talent) }}
                        </Link>
                    </div>
                </div>
            </div> -->
        </div>
    </header>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import Navigation from '@/Components/Backend/Global/Components/Navigation.vue'
import Notification from '@/Components/Backend/Global/Components/Notification.vue'
import Profile from '@/Components/Backend/Global/Components/Profile.vue'
import CInput from '@/Components/Global/CInput.vue'
import Logo from '@/Components/Global/Logo.vue'
import CloseIcon from '@/Icons/CloseIcon.vue'
import SearchIcon from '@/Icons/SearchIcon.vue'
import { size } from 'lodash'
import { handleSearch, searchQuery, searchResult, getName, cancelSearch } from '@/Components/useSearch'
import Helper from '@/Helper'
import { Link } from '@inertiajs/inertia-vue3'
import { onClickOutside } from '@vueuse/core'
import { get } from 'lodash'

const showSearchBox = ref(false)
const target = ref(null)

const showResult = ref(false)

const vAutoFocus = {
  mounted: (el) => {
    if (el) {
        el.focus()
    }
  }
}

onClickOutside(target, (event) => {
    showResult.value = false
    showSearchBox.value = false;
    cleanSearchQuery();
    cancelSearch()
})

const cleanSearchQuery = () => {
    searchResult.value = []
    searchQuery.value = ''
}
</script>

<style scoped>
    .navWrapper{
        display: grid;
        grid-template-columns: 150px 1fr 100px;
    }

    @media all and (max-width: 480px){
        .navWrapper{
            display: grid;
            grid-template-columns: 30px 1fr 120px;
        }
        .logo{
            font-size: 20px;
        }
    }
</style>