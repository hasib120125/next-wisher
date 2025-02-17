<template>
    <Master>
        <MyLife
            :user="user"
            :posts="posts"
            :earning="earning"
        />
    </Master>
</template>

<script setup>
    import Master from './Master.vue'
    import MyLife from '@/Components/Backend/MyLife.vue'

    defineProps({
        user: {
            type: Object,
            default: {},
        },
        earning: {
            type: Object,
            default: {},
        },
        posts: {
            type: Array,
            default: [],
        },
    })
</script>