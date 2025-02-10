<template>
    <Head title="Subscription" />
    <DashboardLayout>
        <template v-slot:leftSidebar>
            <LeftSide />
        </template>
        <template v-slot:content>
            <div class="container mx-auto py-5">
                <div class="flex gap-2 mb-4">
                    <button 
                        class="font-semibold border rounded px-4 py-1"
                        @click="handleTab('all', subscribed)"
                        :class="activeTab=='all' ? 'bg-blue-500 text-white' : ''"
                    >
                        {{ Helper.translate('All') }}
                    </button>
                    <button 
                        class="font-semibold border rounded px-4 py-1"
                        @click="handleTab('active', activeSubscribed)"
                        :class="activeTab=='active' ? 'bg-blue-500 text-white' : ''"
                    >
                        {{ Helper.translate('Active') }} ({{ Helper.translate(size(activeSubscribed), true) }})
                    </button>
                    <button 
                        class="font-semibold border rounded px-4 py-1"
                        @click="handleTab('expire', expiredSubscribed)"
                        :class="activeTab=='expire' ? 'bg-blue-500 text-white' : ''"
                    >
                        {{ Helper.translate('Expired') }} ({{ Helper.translate(size(expiredSubscribed), true) }})
                    </button>
                </div>

                <div class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-6">
                    <template v-for="(talent, index) in allSubscribed" :key="index">
                        <Link :href="route('payment.subscribe', talent.id)" class="relative hover:shadow-xl transition-all">
                            <img class="customRatio" :src="`${$page.props.asset}${talent.profile_image}`" alt="">
                            <div class="absolute bottom-0 p-4 bg-white w-full bg-opacity-50 backdrop-blur-md border-t font-semibold text-center">
                                <h2 class="truncate">{{ talent.name }}</h2>
                                <h2 class="text-sm truncate">
                                    {{ get(talent, 'category.name') }}
                                </h2>
                                <template v-if="Helper.isExpired(talent.earnings)">
                                    <h2
                                        class="bg-red-500 text-white px-4 mt-2 text-sm"
                                    >
                                        {{ Helper.translate('Expired') }}
                                    </h2>
                                    <h2 class="text-sm text-red-500">
                                        {{
                                            Helper.dateFormate(
                                                get(
                                                    first(Helper.isExpired(talent.earnings, true)),
                                                    'expire_date'
                                                )
                                            )
                                        }}
                                    </h2>
                                    <button 
                                        class="bg-blue-500 text-white px-4 mt-2 rounded-xl text-sm"
                                    >
                                        {{ Helper.translate('Renew') }}
                                    </button>
                                </template>
                            </div>
                        </Link>
                    </template>
                </div>
            </div>
        </template>
    </DashboardLayout>
</template>

<script setup>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import DashboardLayout from '../DashboardLayout.vue'
    import LeftSide from '@/Components/Backend/UserDashboard/LeftSide.vue';
    import { filter, isEmpty, get, first, each, size } from 'lodash';
    import Helper from '@/Helper';
    import { computed, ref } from 'vue';

    const props = defineProps({
        subscribed: {
            type: Array,
            default: []
        }
    })

    const allSubscribed = ref(props.subscribed)
    const activeTab = ref('all')

    const activeSubscribed = computed(()=> {
        return filter(props.subscribed, talent => !Helper.isExpired(talent.earnings))
    })

    const expiredSubscribed = computed(()=> {
        return filter(props.subscribed, talent => Helper.isExpired(talent.earnings))
    })

    const handleTab = (arg, toShow) => {
        activeTab.value = arg;
        allSubscribed.value = toShow
    }
</script>


<style scoped>
    .content{
        grid-template-columns: 250px 1fr;
    }
    .customRatio{
        aspect-ratio: 9/16;
        object-fit: cover;
        object-position: center;
    }
</style>