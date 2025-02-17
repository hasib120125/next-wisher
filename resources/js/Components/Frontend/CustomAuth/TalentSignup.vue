<template>
    <form @submit.prevent="submit" class="py-6 block" enctype="multipart/form-data">
        <!-- <div class="flex gap-5 justify-between mb-6">
            <div class="relative w-full">
                <CInput type="text" placeholder="First name" v-model="form.first_name" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.first_name, true) }}
                </span>
            </div>
            <div class="relative w-full">
                <CInput type="text" placeholder="Last name" v-model="form.last_name" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.last_name, true) }}
                </span>
            </div>
        </div> -->

        <div class="flex gap-5 justify-between mb-6">
            <div class="relative w-full">
                <CInput type="text" placeholder="Name (use the name by which you are best known)" v-model="form.username" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.username, true) }}
                </span>
            </div>
        </div>
        <div class="flex gap-5 justify-between mb-6">
            <div class="relative w-full">
                <CInput type="email" placeholder="Email" v-model="form.email" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.email, true) }}
                </span>
            </div>
        </div>

        <div class="flex gap-5 justify-between flex-col mb-6">
            <div class="relative w-full">
                <CSelect v-model="form.country_id" :options="countries" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.country_id, true) }}
                </span>
            </div>
            <div class="relative w-full">
                <CSelect v-model="form.category_id" :options="categories" placeholder="select category" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.category_id, true) }}
                </span>
            </div>
            <div class="relative w-full">
                <CSelect v-model="form.sub_category_id" :options="sub_categories" placeholder="select category" class="w-full" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.sub_category_id, true) }}
                </span>
            </div>
        </div>

        <!-- <div class="flex gap-5 justify-between mb-6">
            <div class="relative w-full">
                <CInput type="password" placeholder="Password" v-model="form.password" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.password, true) }}
                </span>
            </div>
            <div class="relative w-full">
                <CInput type="password" placeholder="Confirm Password" v-model="form.password_confirmation" />
                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">
                    {{ Helper.translate(form.errors.password_confirmation, true) }}
                </span>
            </div>
        </div> -->

        <div class="relative w-full mb-6">
            <CInput 
                type="url" 
                placeholder="Please enter one of your social medias link with more than 1000 followers/subscribers"
                title="Please enter one of your social medias link with more than 1000 followers/subscribers"
                v-model="form.link" 
                class="w-full text-[12px]" 
            />
            <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                {{ Helper.translate(form.errors.link, true) }}
            </span>
            <span class="absolute top-full left-0 text-xs mt-[2px]" v-if="!form.errors.link">
                {{ Helper.translate('https://') }}
            </span>
        </div>


        <p class="mt-5 opacity-60">
            {{ Helper.translate('Verification: Please upload a video of your face. 10 seconds maximum') }}
        </p>
        <div class="mb-2">
            <div class="fileUpload mb-4 relative">
                <FileInput
                    class="!w-full"
                    accept="video/*"
                    @input="(e) => form.video = e.target.files[0]"
                />

                <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px] first-letter:uppercase">
                    {{ Helper.translate(form.errors.video, true) }}
                </span>
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
            </div>
        </div>

        <div class="relative">
            <CInput type="password" placeholder="Password" v-model="form.password" class="mb-6" />
            <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">{{ Helper.translate(form.errors.password, true) }}</span>
        </div>
        <div class="relative">
            <CInput type="password" placeholder="Confirm Password" v-model="form.password_confirmation" class="mb-6" />
            <span class="absolute top-full left-0 text-xs text-red-500 mt-[2px]">{{ Helper.translate(form.errors.password_confirmation, true) }}</span>
        </div>

        <label class="flex items-center gap-2 select-none">
            <input type="checkbox" v-model="form.is_agree" />
            <div>
                {{ Helper.translate('I certify that I am at least 18 years old. I have read and I agree to the') }}
                <Link target="_blank" :href="route('pages', 'terms-of-service')" class="text-[var(--link-color)]">
                    {{ Helper.translate('Terms of service') }}
                </Link> 
                {{ Helper.translate('and') }} 
                <Link target="_blank" :href="route('pages', 'privacy-policy')" class="text-[var(--link-color)]">
                    {{ Helper.translate('Privacy Policy') }}
                </Link>.
            </div>
        </label>
        <!-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ Helper.translate('It will take up to 72 hours to review your application.') }}
        </p> -->

        <button 
            v-if="form.is_agree"
            type="submit"
            class="bg-[var(--btn-bg-color)] text-[var(--btn-text-color)] rounded-full px-5 md:py-2 py-1 shadow ml-auto flex items-center gap-1 mt-6"
            :class="!!form.processing && 'pointer-events-none opacity-70'"
        >
            <Preloader class="w-4 h-4" v-if="!!form.processing" />
            {{ Helper.translate('Go') }}
        </button>
        
        <div v-else class="bg-[var(--btn-bg-color)] text-[var(--btn-text-color)] rounded-full px-5 md:py-2 py-1 shadow ml-auto block w-fit pointer-events-none opacity-60 mt-6">
            {{ Helper.translate('Go') }}
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import CInput from '@/Components/Global/CInput.vue'
import CSelect from '@/Components/Global/CSelect.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import { isEmpty, map, filter } from 'lodash'
import Helper, { showAuthModal } from '@/Helper'
import { Link } from '@inertiajs/inertia-vue3'
import { toast } from 'vue3-toastify'
import Preloader from '@/Components/Global/Preloader.vue'
import FileInput from '@/Components/Global/FileInput.vue'

const countries = ref([{
    key: '',
    value: 'Select Country'
}])

const all_categories = ref([]);
const clearPreview = ref(false);

const categories = ref([{
    key: 0,
    value: 'Select Category'
}])

const emit = defineEmits('close')
const form = useForm({
    first_name: '',
    last_name: '',
    name: '',
    username: '',
    email: '',
    country_id: '',
    category_id: 0,
    sub_category_id: 0,
    video: '',
    link: '',
    password: '',
    password_confirmation: '',
    is_agree: false,
    role: 'talent'
})

const sub_categories = computed(() => {
    let sub = map(
        filter(all_categories.value, item => item.parent_id == form.category_id),
        item => {
            return {
                key: item.id,
                value: item.name
            }
        }
    )
    sub.unshift({
        key: 0,
        value: 'Select Subcategory'
    })
    return sub;
});

// const handleFile = (e) => {
//     fileName.value = e.target.files[0].name
//     if(fileName.value.length > 25){
//         fileName.value = fileName.value.substring(0, 13) + "..." + fileName.value.substring(fileName.value.length - 13);
//     }
// }
const submit = () => {
    if(form.country_id == 0){
        form.country_id = ''
    }
    if(form.category_id == 0){
        form.category_id = ''
    }
    if(form.sub_category_id == 0){
        form.sub_category_id = ''
    }

    if (!validateForm()) {
        return;
    }

    if (form.video) {
        Helper.getVideoDuration(form.video, valid => {
            if (valid) {
                form.name = `${form.first_name} ${form.last_name}`
                form.post(route('register'), {
                    onFinish: () => {
                        clearPreview.value = true
                        form.reset('password', 'password_confirmation')
                        form.country_id = ""
                        form.category_id = 0
                        if(isEmpty(form.errors)){
                            emit('close')
                            showAuthModal.value = false;
                        }
                    }
                })
            } else {
                toast.error(Helper.translate('Video duration should not be more than 10 seconds'))
            }
        }, 10)
    }
}

const validateForm = () => {
    let valid = true;
    let arr = [
        'username',
        'email',
        'country_id',
        'category_id',
        'sub_category_id',
        'link',
        'video'
    ]
    arr.forEach(element => {
        if (!form[element]) {
            form.errors[element] = Helper.translate(`${String(element).replace('_id', '').replace('_', ' ')} cannot be empty`)
            valid = false;
        } else {
            form.errors[element] = ''
        }
    });
    return valid;
}

onMounted(async () => {
    let catRes = await axios.get('categories')
    if(!isEmpty(catRes.data)){
        all_categories.value = catRes.data;
        catRes.data.forEach(item => {
            if (!item.parent_id) {
                categories.value.push({
                    key: item.id,
                    value: item.name
                })
            }
        })
    }

    let countryRes = await axios.get('countries')
    if(!isEmpty(countryRes.data)){
        countryRes.data.forEach(item => {
            countries.value.push({
                key: item.id,
                value: item.name
            })
        })
    }
})



</script>

<style scoped>
    progress[value]{
        height: 10px;
        width: 100%;
    }
    progress[value]::-webkit-progress-value {
        background-image:
            -webkit-linear-gradient(-45deg, 
                                    transparent 33%, rgba(0, 0, 0, .1) 33%, 
                                    rgba(0,0, 0, .1) 66%, transparent 66%),
            -webkit-linear-gradient(top, 
                                    rgba(255, 255, 255, .25), 
                                    rgba(0, 0, 0, .25)),
            -webkit-linear-gradient(left, #09c, #f44);

        border-radius: 2px; 
        background-size: 35px 20px, 100% 100%, 100% 100%;
    }
</style>