<template>
    <div 
        class="fixed top-0  w-[300px] h-[100vh] bg-[var(--offCanvas-bg-color)] z-20 transition-all -left-full"
        :class="modelValue && '-left-0'"

    >
        <button @click="$emit('update:modelValue', false)" class="absolute right-4 top-4">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke="currentColor" stroke-width="2" d="m7 7 10 10M7 17 17 7"/></svg>
        </button>

        <div class="nav grid mt-16">
            <a  @click="$emit('update:modelValue', false)" class="border-t border-b border-black border-opacity-5 font-black text-center py-3 text-white text-opacity-80 hover:text-opacity-100" href="#">{{ Helper.translate('About') }}</a>
            <a  @click="$emit('update:modelValue', false)" class="border-b border-black border-opacity-5 font-black text-center py-3 text-white text-opacity-80 hover:text-opacity-100" href="#">{{ Helper.translate('Blog') }}</a>
            <a  @click="$emit('update:modelValue', false)" class="border-b border-black border-opacity-5 font-black text-center py-3 text-white text-opacity-80 hover:text-opacity-100" href="#">{{ Helper.translate('Help') }}</a>
            <a  @click="$emit('update:modelValue', false)" class="border-b border-black border-opacity-5 font-black text-center py-3 text-white text-opacity-80 hover:text-opacity-100" href="#">{{ Helper.translate('Contact') }}</a>
        </div>
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
    
    defineProps({
        modelValue: {
            type: Boolean,
            default: true
        }
    })
</script>

<style lang="scss" scoped>

</style>