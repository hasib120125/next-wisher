<template>
    <div class="border p-4 rounded font-medium capitalize">
        <div class="flex justify-between items-center text-green-500">
            <span class="">{{ Helper.translate('New users') }}</span>
            <span class="">{{ Helper.translate(userSummery.new_users, true) }}</span>
        </div>
        <div class="flex justify-between items-center text-amber-500">
            <span class="">{{ Helper.translate('Talents Applications') }}</span>
            <span class="">{{ Helper.translate(userSummery.talent_applications, true) }}</span>
        </div>
        <div class="flex justify-between items-center text-sky-500">
            <span class="">{{ Helper.translate('Talents Applications Approved') }}</span>
            <span class="">{{ Helper.translate(userSummery.talent_applications_approved, true) }}</span>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper'

defineProps({
    userSummery: Object
})
</script>