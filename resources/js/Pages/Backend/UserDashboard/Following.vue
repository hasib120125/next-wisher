<template>
    <Head title="Following" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="container mx-auto">
                <h1 class="font-semibold text-xl mb-2">
                    {{ Helper.translate('Following') }}
                </h1>
                <div v-if="!isEmpty(following)" class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                    <template v-for="follow in following" :key="follow.id">
                        <Link :href="route('item.details', {
                                id: follow.id,
                                username: `${String(follow.username).replaceAll(' ', '-')}`
                            })" class="relative hover:shadow-xl transition-all">
                            <img 
                                class="customRatio w-full h-full object-cover object-center" 
                                :src="`${$page.props.asset}${follow.profile_image}`" 
                                alt=""
                            >
                            <div class="absolute bottom-0 p-4 bg-white w-full bg-opacity-50 backdrop-blur-md border-t font-semibold text-center">
                                <h2 class="truncate">
                                    {{ Helper.translate(follow.name, true) }}
                                </h2>
                                <h2 class="text-sm truncate">
                                    {{ Helper.translate(get(follow, 'category.name'), true) }}
                                </h2>
                                <button @click.prevent="unfollow(follow.id)" class="bg-red-500 text-white px-4 mt-2 rounded-xl text-sm">
                                    {{ Helper.translate('Unfollow') }}
                                </button>
                            </div>
                        </Link>
                    </template>
                </div>
                <div v-else>
                    <Alert />
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import DashboardLayout from '../DashboardLayout.vue'
    import LeftSide from '@/Components/Backend/UserDashboard/LeftSide.vue';
    import { Inertia } from '@inertiajs/inertia';
    import Helper from '@/Helper';
    import { get, isEmpty } from 'lodash';
    import Alert from '@/Components/Global/Alert.vue'
    const props = defineProps({
        following: Array,
    });

    const unfollow = (id) => {
        Helper.confirm(Helper.translate('Do you want to proceed?'), ()=>{
            Inertia.post(route('item.followTalents', id));
        })
        // following
    }
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
</style>