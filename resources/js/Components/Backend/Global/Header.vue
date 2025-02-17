<template>
    <header class="bg-white text-black border-b border-slate-300 px-4 py-1.5 max-lg:py-2 sticky top-0 z-50">
        <div class="flex justify-between max-w-[1200px] mx-auto items-center relative">
            <div class="flex">
                <Navigation />
                <Logo :light="false"/>
            </div>

            <div class="flex md:gap-4 gap-2 items-center justify-end">
                <Notification />
                <Profile />
            </div>
            
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