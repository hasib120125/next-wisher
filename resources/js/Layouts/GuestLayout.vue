<script setup>
import ApplicationLogo from '@/Components/Global/Logo.vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    light: {
        type: Boolean,
        default: false,
    }
})

</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" :class="{'bg-[#2b2c3b]': !light}">
        <div>
            <Link href="/">
                <!-- <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" /> -->
                <ApplicationLogo
                    :light="true"
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-10 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
