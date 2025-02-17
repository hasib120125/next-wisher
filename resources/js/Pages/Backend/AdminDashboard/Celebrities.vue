<template>
    <Head :title="Helper.translate('Our Celebrities')" />
    <DashboardLayout :header="false" :footer="false" title="Talent Detail">
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="bg-[#06061e] mb-4 h-36 text-center text-white font-bold text-4xl grid items-center">
                {{ Helper.translate('Hire Celibrities') }}
            </div>

            <div class="text-xl relative max-w-[1200px] sm:px-10 px-5 mb-8 mx-auto">
                <p class="text-[#080808]">
                    <strong class="font-bold text-[#131313]">{{ Helper.translate('Book your favorite celebrity') }}</strong>
                    {{ Helper.translate('for appearances, speaking engagements, products or advertising endorsements, private parties, corporate events and more, anywhere in the world.') }}
                </p>
                <p class="text-[#080808]">
                    {{ Helper.translate('Select the celebrity, fill the request form and one of our agent will get back to you shortly.') }}
                </p>
            </div>

            <div class="relative max-w-[1200px] sm:px-10 px-5 mx-auto">
                <div 
                        class="p-1 border grid grid-cols-[var(--col5)] md:grid-cols-[var(--col10)] lg:grid-cols-[var(--col20)] xl:grid-cols-[var(--col27)] gap-1 border-gray-700"
                        :style="`
                            --col27: repeat(27, minmax(0, 1fr));
                            --col20: repeat(20, minmax(0, 1fr));
                            --col10: repeat(10, minmax(0, 1fr));
                            --col5: repeat(5, minmax(0, 1fr));
                        `"
                    >
                        <div
                            v-for="(filterItem, index) in filterData"
                            :key="index"
                            class="py-2 text-center hover:bg-gray-800 hover:text-white font-semibold capitalize cursor-pointer"
                            :class="{
                                'bg-gray-800 text-white': selectedKey == filterItem
                            }"
                            @click="() => {
                                selectedKey = filterItem
                                getData()
                            }"
                        >
                            {{
                                item == 'all'
                                ?  Helper.translate(filterItem)
                                : filterItem
                            }}
                        </div>
                    </div>
                <div class="flex justify-between mt-5">
                    <div class="flex gap-3 items-center">
                        <input
                            type="search"
                            v-model="searchQuery"
                            class="w-full px-4 py-1 flex-1 border border-gray-700 focus:outline-none focus:border-black focus:ring-0"
                            :placeholder="Helper.translate('Search')"
                        />
                        <button 
                            class="w-8 h-8 rounded-full grid place-content-center bg-[#000547] text-white"
                            @click="getData()"
                        >
                            <SearchIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div class="my-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    <HireItem 
                        v-for="(item, index) in talents"
                        :key="index"
                        :item="item"
                        @click="selectedItem=item"
                    >
                        <template #action>
                            <button 
                                class="px-4 py-2 pb-3 font-semibold text-center block w-full"
                            >
                                View Contact
                            </button>
                        </template>
                    </HireItem>
                </div>
            </div>

            <Modal v-model="selectedItem">
                <div v-if="selectedItem" class="bg-white w-full md:min-w-[500px] rounded pb-5 pt-5">
                    <div class="text-xl font-bold py-2 px-5">{{ Helper.translate('Contact information') }}</div>

                    <div v-if="selectedItem?.contact" class="py-2 px-5 grid gap-5">
                        <div>
                            <span class="font-semibold">{{ Helper.translate('Name') }}: </span>
                            <span>{{ get(selectedItem, 'contact.name') }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">{{ Helper.translate('Phone') }}: </span>
                            <span>{{ get(selectedItem, 'contact.phone') }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">{{ Helper.translate('Email') }}: </span>
                            <span>{{ get(selectedItem, 'contact.email') }}</span>
                        </div>
                    </div>

                    <div v-else class="py-2 px-5">
                        {{ Helper.translate('Contact information not set') }}
                    </div>
                </div>
            </Modal>
        </template>

    </DashboardLayout>
</template>

<script setup>
import DashboardLayout from './DashboardLayout.vue'
import { Head } from "@inertiajs/inertia-vue3"
import LeftSide from "@/Components/Backend/AdminDashboard/LeftSide.vue";
import SearchIcon from '@/Icons/SearchIcon.vue'
import HireItem from '@/Components/Frontend/Home/HireItem.vue'
import HirePopup from '@/Components/Frontend/Home/HirePopup.vue'
import axios from 'axios'
import { ref, onMounted } from 'vue'
import { get } from 'lodash'
import Helper from "@/Helper"
import Modal from '@/Components/Library/Modal.vue'

const filterData = ['all', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k','l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't','u', 'v', 'w', 'x', 'y', 'z']
const searchQuery = ref('')
const selectedKey = ref('all')
let talents = ref([])

const selectedItem = ref(false)

const getData = async () => {
  const response = await axios.post(route('admin.getOurCelebrities'), {
    q: searchQuery.value,
    startWith: selectedKey.value,
  });

  talents.value = response.data;
};

onMounted(() => {
  getData();
});

</script>

<style scoped></style>
