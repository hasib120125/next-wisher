<template>
    <div class="grid gap-2">
        <Link 
            class="px-4 py-2 border font-semibold hover:bg-gray-100"
            :class="route().current('user.dashboard') ? 'bg-gray-100' : ''"
            :href="route('user.dashboard')"
        >
            {{ Helper.translate('Guide') }}
        </Link>
        <Link 
            class="px-4 py-2 border font-semibold hover:bg-gray-100"
            :class="route().current('user.account') ? 'bg-gray-100' : ''"
            :href="route('user.account')"
        >
            {{ Helper.translate('Account') }}
        </Link>
        <!-- <Link 
            class="px-4 py-2 border font-semibold hover:bg-gray-100"
            :class="route().current('user.following') ? 'bg-gray-100' : ''"
            :href="route('user.following')"
        >
            {{ Helper.translate('Following') }}
        </Link> -->
        <!-- <Link 
            class="px-4 py-2 border font-semibold hover:bg-gray-100"
            :class="route().current('user.subscription') ? 'bg-gray-100' : ''"
            :href="route('user.subscription')"
        >
            {{ Helper.translate('Subscription') }}
        </Link> -->
        <!-- <Link 
            class="px-4 py-2 border font-semibold hover:bg-gray-100"
            :class="route().current('user.history') ? 'bg-gray-100' : ''"
            :href="route('user.history')"
        >
            {{ Helper.translate('History') }}
        </Link> -->
    </div>
</template>

<script setup>
    import { Link } from '@inertiajs/inertia-vue3'
    import Helper from '@/Helper'
</script>

<style lang="scss" scoped>

</style>