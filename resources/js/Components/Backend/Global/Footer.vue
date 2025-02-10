<template>
    <footer class="bg-[var(--footer-bg-color)] text-white px-4 text-sm py-1 footerOuterWrapper">
        <div class="footerWrapper container mx-auto flex gap-1 justify-between items-center flex-wrap">
            <div class="links flex gap-[5px] md:gap-2 flex-wrap">
                <!-- <Link :href="route('categories')" class="opacity-60 hover:opacity-100">
                    {{ Helper.translate('Categories') }}
                </Link> -->
                <Link 
                    v-for="page in pages" 
                    :key="page.id" 
                    :href="route('pages', page.slug)"
                    class="opacity-60 hover:opacity-100"
                >
                    {{ Helper.translate(page.title, true) }}
                </Link>

                <Link :href="route('faq')" class="opacity-60 hover:opacity-100">
                    {{ Helper.translate('FAQ') }}
                </Link>

                <Link :href="route('contact')" class="opacity-60 hover:opacity-100">
                    {{ Helper.translate('Contact') }}
                </Link>
            </div>
            <div class="opacity-60 flex gap-1 items-center">
                &copy; {{ new Date().getFullYear() }}
                <strong class="capitalize">{{ Helper.companyName() || 'nextwisher' }}</strong>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import Helper from '@/Helper'
import axios from 'axios'
import { Link } from '@inertiajs/inertia-vue3';

const pages = ref([])

onMounted(async () => {
    let res = await axios.get('pages')
    pages.value = res.data
})
</script>

<style scoped>
    @media all and (max-width: 580px){
        .footerOuterWrapper{
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .footerWrapper{
            justify-content: center;
        }
        .footerWrapper .links{
            justify-content: center;
        }
    }
</style>