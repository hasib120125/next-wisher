<template>
    <div class="bg-white py-2.5 px-2.5 md:py-10 md:px-10">
        <!-- <h3 class="text-center font-bold text-3xl text-[#6260d3] mb-4">{{ Helper.translate('Discover our categories') }}</h3>
        <p class="text-center text-white">
            {{ Helper.translate('Offer your family and friends a memorable gift for a special occasion.') }}
        </p> -->

        <div class="max-w-[1000px] mx-auto">
            <div class="grid grid-cols-3 min-[560px]:flex flex-wrap justify-center sm:gap-5 gap-4 mt-8">
                <template v-for="item in $page.props.categories" :key="item.id">
                    <Link 
                        v-if="!item.parent_id"
                        :href="route('category.items', item.slug)" 
                        class="md:w-[90px] md:h-[90px] place-self-center w-[75px] h-[75px] sm:text-base text-sm flex-shrink-0 flex-grow-0 flex items-center justify-center bg-[#268AFF] text-white border border-slate-800 rounded-full"
                    >
                        {{ Helper.translate(item.name, true) }}
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
    import { Link } from '@inertiajs/inertia-vue3';

</script>