<template>
    <div class="bg-white w-[calc(100vw-20px)] sm:w-[400px] md:w-[500px] p-5">
        <label class="relative flex">
            <input
                v-model="countryFilterKey"
                type="search"
                :placeholder="Helper.translate('Search country')"
                class="w-full pr-6"
            />
            <div class="absolute right-0 top-0 w-10 h-[42px] pointer-events-none grid place-content-center">
                <SearchIcon 
                    class="text-current"
                />
            </div>
        </label>
        <div class="divide-y mt-5">
            <div
                v-for="(item, index) in filteredCountries"
                :key="index"
                class="py-1 px-4 hover:bg-slate-100"
            >
                {{ item }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import countries from '@/data/country.js'
import SearchIcon from '@/Icons/SearchIcon.vue';
import Helper from '@/Helper';

const countryFilterKey = ref('')
const filteredCountries = computed(() => {
    if(countryFilterKey.value) {
        return countries.filter(item => {
            return item.toLocaleLowerCase().indexOf(countryFilterKey.value.toLowerCase()) > -1;
        }) || []
    }
    return countries
})
</script>