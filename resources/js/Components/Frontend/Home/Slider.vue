<template>
    <div class="relative">
        <carousel 
            :items-to-show="1" 
            ref="myCarousel" 
            :autoplay="15000"
            :wrap-around="true"
            :mouseDrag="false"
        >
            <slide v-for="(item, index) in carouselData" :key="index" class="w-full">
                <div 
                    class="xl:h-[600px] sm:h-[400px] h-[300px] w-full flex flex-col relative xl:justify-start justify-center"
                    :style="{background: item.bg}"
                >
                    <div class="lg:ml-[80px] md:ml-[100px] sm:ml-[60px] ml-0 lg:mt-[100px] sm:text-left text-center space-y-8">
                        <h1 
                            class="xl:text-[40px] lg:text-[35px] sm:text-[25px] text-[20px] font-black text-white z-20"
                            v-html="item.title"
                        ></h1>
                        <a 
                            href="#exploreSection"
                            class="bg-blue-800 text-white md:font-black font-semibold sm:mx-0 mx-auto sm:text-5 text-4 text-xl sm:py-2 py-1 w-fit px-5 block rounded-lg shadow"
                        >
                            {{ Helper.translate('Explore') }}
                        </a>
                    </div>

                    <div class="xl:h-[550px] lg:h-[400px] sm:h-[370px] sm:block hidden absolute bottom-0 xl:right-0 right-5 z-20">
                        <h3 class="absolute right-full whitespace-nowrap font-semibold text-3xl bottom-20 text-blue-400 shadow">{{ item.name }}</h3>
                        <img 
                            :src="item.image"
                            class="w-full h-full block object-contain"
                        />
                    </div>
                </div>
            </slide>
        </carousel>
        <div class="flex w-full gap-2 sm:justify-between justify-end absolute sm:top-[80%] sm:bottom-auto bottom-[20px] sm:px-10 pr-5">
            <button 
                @click="myCarousel.prev()"
                class="w-7 h-7 flex justify-center items-center rounded-full bg-white/30 hover:bg-white duration-300"
            >
                <AngleLeftIcon class="md:w-5 md:h-5 w-4 h-4 text-white hover:text-black" />
            </button>
            <button 
                @click="myCarousel.next()"
                class="w-7 h-7 flex justify-center items-center rounded-full bg-white/30 hover:bg-white duration-300"
            >
                <AngleRightIcon class="md:w-5 md:h-5 w-4 h-4 text-white hover:text-black" />
            </button>
        </div>
    </div>
</template>

<script setup>
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide } from 'vue3-carousel'
import imageOne from '@/assets/slider/1.webp'
import imageTwo from '@/assets/slider/2.webp'
import imageThree from '@/assets/slider/3.webp'
import { ref, computed } from 'vue'
import AngleLeftIcon from '@/Icons/AngleLeftIcon.vue'
import AngleRightIcon from '@/Icons/AngleRightIcon.vue'
import Helper from '@/Helper'

const myCarousel = ref(null)

let getTitle = () => `${Helper.translate('Get a')} ${Helper.translate('personalized video')} <br> ${Helper.translate('for any occasion')} <br>${Helper.translate('from your favorite personality')}`
const carouselData = computed(() => [
    {
        title: getTitle(),
        image: imageOne,
        bg: '#000026',
        name: 'Lorem ipsum dolor'
    },
    {
        title: getTitle(),
        image: imageTwo,
        bg: '#240f34',
        name: 'Lorem ipsum dolor'
    },
    {
        title: getTitle(),
        image: imageThree,
        bg: '#08040b',
        name: 'Lorem ipsum dolor'
    }
])
</script>