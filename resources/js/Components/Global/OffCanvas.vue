<template>
    <div 
        @click.self="$emit('update:modelValue', false)" 
        class="fixed w-full h-full top-0 left-0 z-10"
        :class="modelValue ? 'bg-gray-600 bg-opacity-25 backdrop-blur-md' : 'bg-transparent pointer-events-none'"
    >
        <div class="fixed top-0  w-[300px] overflow-y-auto bg-white z-20 transition-all shadow" :class="modelValue ? '-left-0' : '-left-full'">
            <div class="flex justify-between flex-col h-full">
                <div class="w-full max-h-[calc(100vh-44px)] overflow-y-auto">
                    <div class="flex justify-between border-b py-3  px-6 bg-[var(--header-bg-color)] text-white sticky top-0">
                        <h1 class="uppercase font-bold md:text-[16px] text-[14px]">
                            {{ Helper.translate('Menu') }}
                        </h1>
                        <button @click="$emit('update:modelValue', false)">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="none" stroke="currentColor" stroke-width="2" d="m7 7 10 10M7 17 17 7" />
                            </svg>
                        </button>
                    </div>

                    <div class="nav grid py-6 text-black md:text-[16px] text-[14px]">
                        <template v-for="(category, index) in parentCategories" :key="index">
                            <CategoryDropdown :label="Helper.translate(category.name, true)" :href="route('category.items', category.slug)" v-if="!isEmpty(getCategoryChild(category.id))">
                                <template v-for="(sub_category, index) in getCategoryChild(category.id)" :key="index">
                                    <Link :href="route('category.items', sub_category.slug)" @click="$emit('update:modelValue', false)" class="px-6 py-2 border-b border-opacity-5 font-semibold text-black text-opacity-80 hover:text-opacity-100 hover:text-[orangered]">
                                        {{ Helper.translate(sub_category.name, true) }}
                                    </Link>
                                </template>
                            </CategoryDropdown>
                            <template v-else>
                                <Link :href="route('category.items', category.slug)" @click="$emit('update:modelValue', false)" class="px-6 py-2 border-b border-opacity-5 font-semibold text-black text-opacity-80 hover:text-opacity-100 hover:text-[orangered]">
                                    {{ Helper.translate(category.name, true) }}
                                </Link>
                            </template>
                        </template>
                    </div>
                </div>

                <footer class="flex flex-wrap justify-center bg-[var(--footer-bg-color)] text-white py-2">
                    <button @click="languagePopup = true" class="capitalize px-2 py-1 text-sm">{{ Helper.translate('Languages') }}</button>
                    <template v-if="isEmpty($page.props.auth.user)">
                        <!-- <a href="#" class="capitalize px-2 py-1 text-sm">{{ Helper.translate('Sign up') }}</a>
                        <button @click="() => {
                            showAuthModal = true;
                            templateName = 'TalentSignup'
                        }" class="capitalize px-2 py-1 text-sm">{{ Helper.translate('Become a talent') }}</button> -->
                    </template>
                    <template v-else>
                        <Link :href="route('logout')" method="post" type="button" class="capitalize px-2 py-1 text-sm">{{ Helper.translate('Logout') }}</Link>
                    </template>
                </footer>
            </div>
        </div>
    </div>
    <Modal v-model="languagePopup" class="flex items-center justify-center p-4">
        <LanguageChange @close="languagePopup = false" />
    </Modal>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import Helper, { languagePopup, showAuthModal } from '@/Helper';
import CategoryDropdown from './CategoryDropdown.vue';
import { isEmpty, get } from 'lodash';
import Modal from '@/Components/Library/Modal.vue'
import LanguageChange from './LanguageChange.vue';
import { templateName } from '@/Components/Frontend/CustomAuth/useCustomAuth';

defineProps({
    modelValue: {
        type: Boolean,
        default: true
    }
})
</script>