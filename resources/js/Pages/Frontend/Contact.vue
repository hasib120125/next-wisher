<template>
    <Head :title="Helper.translate('Contact Us')" />
    <Master>
        <div class="px-4">
            <div class="wrapper border max-w-screen-xl mt-24 px-8 grid gap-8 grid-cols-1 md:grid-cols-2 md:px-12 lg:px-16 xl:px-32 py-16 mx-auto bg-gray-100 text-gray-900 rounded-lg shadow-lg">
                <div class="flex flex-col justify-between">
                    <div>
                        <h2 class="lg:text-5xl md:text-3xl text-xl font-bold leading-tight">
                            {{ Helper.translate('Contact us') }}
                        </h2>
                    </div>
                    <div class="mt-8 text-center lg:block hidden">
                        <SVGImage />
                    </div>
                </div>
                <div class="grid items-center">
                    <Form />
                </div>
            </div>
        </div>
    </Master>
</template>

<script setup>
import Master from "../Backend/Master.vue"
import { Head } from "@inertiajs/inertia-vue3"
import SVGImage from "@/Components/Frontend/Contact/SVGImage.vue"
import Form from "@/Components/Frontend/Contact/Form.vue"
import Helper from "@/Helper"


</script>


<style scoped>
.wrapper {
    background: white;
    margin-bottom: 25px;
}
@media all and (min-width: 1024px){
    .wrapper {
        background: linear-gradient(90deg, #0008 0%, transparent 50%);
    }
}
</style>