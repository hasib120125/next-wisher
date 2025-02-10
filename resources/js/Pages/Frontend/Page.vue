<template>
    <Master>
        <div class="px-4">
            <div v-if="$page.props.page" class="container mx-auto py-5 relative">
                <Head :title="Helper.translate($page.props.page.title, true)" />
                <div>
                    <h1 class="text-2xl mb-4 font-bold text-black">{{ Helper.translate($page.props.page.title, true) }}</h1>
                    <div v-html="getDetails($page.props.page)"></div>
                </div>
            </div>
            <Alert v-else title="No Result Found" />
        </div>
    </Master>
</template>

<script setup>
import Master from '../Backend/Master.vue';
import { Head } from '@inertiajs/inertia-vue3'
import Alert from '@/Components/Global/Alert.vue';
import { ref, onMounted } from 'vue'
import Helper from '@/Helper'
import { isEmpty } from 'lodash'

const language = ref('english')

const getDetails = (page) => {
    if(!page[language.value]){
        return page.description
    }
    return page[language.value]
}
onMounted(()=> {
    if (localStorage.language) {
        language.value = localStorage.language
    }
})

</script>