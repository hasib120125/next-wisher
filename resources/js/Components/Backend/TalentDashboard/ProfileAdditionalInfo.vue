<template>
    <div class="border p-5 mb-4 rounded">
        <div class="flex justify-between items-center mb-5">
            <span class="w-[40px] h-[40px] rounded-full text-white bg-red-500 text-2xl font-bold flex items-center justify-center">!</span>
            <span class="font-bold text-red-500 text-xl -ml-5">
                {{ Helper.translate('Profile Video') }}
            </span>
            <span></span>
        </div>
        <div class="space-y-2 text-[15px]">
            <p>{{ Helper.translate("Present yourself and always mention the platform (Nextwisher).") }}</p>
            <p>{{ Helper.translate('Feel free to personalize your presentation message. The video should not exceed 15 MB') }}</p>
        </div>
    </div>
</template>

<script setup>
    import Helper from "@/Helper"
</script>