<template>
    <div class="grid items-center w-full text-white">
        <div class="mx-auto mb-4">
            <Logo :light="true" class="text-4xl"/>
        </div>
        <template v-if="get($page.props, 'auth.user')">
            <div class="max-w-[600px] w-full mx-auto px-[5vw] relative">
                <div>
                    <input
                        ref="target"
                        type="search"
                        v-model="searchQuery"
                        @input="handleSearch"
                        @focusin="()=>{
                            shwResult=true;
                            cleanSearchQuery()
                        }"
                        class="bg-white bg-opacity-10 block w-full border-2 border-[#fff] border-opacity-20 rounded-full px-6 py-3"
                        placeholder="Search here..."
                        autocomplete="off"
                    />
                    <div v-if="size(searchResult) && shwResult" class="w-full max-w-[600px] px-[5vw] left-0 absolute top-[calc(100%+10px)] z-20">
                        <div class="bg-white divide-y divide-slate-300 rounded-xl overflow-hidden">
                            <Link 
                                v-for="(talent, index) in searchResult"
                                :key="index"
                                :href="route('item.details', {
                                id: talent.id,
                                username: `${String(talent.username).replaceAll(' ', '-')}`
                            })"
                                class="block py-1 px-2 hover:text-slate-800 hover:bg-slate-100 text-slate-700 capitalize"
                                @click="cleanSearchQuery"
                            >
                                {{ getName(talent) }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div class="">
            <button @click="languagePopup = true" class="mx-auto block mt-10">
                <GlobIcon class="w-10 h-10 opacity-50 hover:opacity-100" />
            </button>            
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/Components/Library/Modal.vue'
import GlobIcon from '@/Icons/GlobIcon.vue'
import Logo from '@/Components/Global/Logo.vue'
import { languagePopup } from '@/Helper'
import { get, size } from 'lodash'
import { handleSearch, searchQuery, searchResult, getName, cancelSearch } from '@/Components/useSearch'
import { Link } from '@inertiajs/inertia-vue3'
import { onClickOutside } from '@vueuse/core' 

const target = ref(null)

const shwResult = ref(false)

onClickOutside(target, (event) => {
    console.log('clicked outsize');
    shwResult.value = false
    cleanSearchQuery()
    cancelSearch()
})

const cleanSearchQuery = () => {
    searchResult.value = []
    searchQuery.value = ''
}
</script>