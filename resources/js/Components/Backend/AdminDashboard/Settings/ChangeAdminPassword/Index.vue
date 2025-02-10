<template>
    <h1 class="text-lg my-2 text-center mb-4">{{ Helper.translate('Change password') }}</h1>
    <div class="max-w-[500px] mt-9 rounded mx-auto border border-blue-500/30 p-5">
        <ChangePassword :user="$page.props.auth.user" />
    </div>
</template>

<script setup>
import ChangePassword from '@/Components/Backend/AdminDashboard/Talents/Details/ChangePassword.vue'
import Helper from '@/Helper'

</script>