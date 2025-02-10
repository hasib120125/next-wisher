<template>
    <div class="bg-black px-10">
        <h3 class="text-center font-bold text-3xl text-white mb-4">{{ Helper.translate('How Nextwisher works') }}</h3>
        <div class="py-10 max-w-[1200px] mx-auto flex flex-wrap justify-center gap-x-6 gap-y-14 text-white text-lg font-medium">
            <div
                v-for="(item, index) in data"
                :key="index"
                class="px-6 py-10 rounded-lg bg-[#3e1e9a] relative flex-1 min-w-[300px] max-w-[400px]"
            >
                <span class="text-xl bg-white shadow text-[#3e1e9a] w-14 h-14 rounded-full flex items-center justify-center absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 font-bold">
                    {{ item.index }}
                </span>
                {{ Helper.translate(item.description, true) }}
                <span class="w-10 h-10 block absolute bottom-4 right-4">
                    <img :src="item.icon" class="object-cover object-center" />
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
    import search from '@/assets/search.png'
    import giftBox from '@/assets/gift-box.png'
    import smiley from '@/assets/smiley.png'
    import Helper from '@/Helper.js'
    const data = [
        {
            description: 'Search and select the personality you would like to get a personalized video from.',
            index: '1',
            icon: search
        },
        {
            description: 'Select the occasion and enter your instructions for the video.',
            index: '2',
            icon: giftBox
        },
        {
            description: 'Your request is now fulfilled and you have received the video. It is time to enjoy it and share it with your loved ones.',
            index: '3',
            icon: smiley
        },
    ]
</script>