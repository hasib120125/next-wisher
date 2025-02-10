<template>
    <div class="px-4 min-h-[87vh]">
        <div class="container mx-auto py-5">
            <div class="grid content relative">
                <button 
                    class="md:hidden inline-block absolute left-0"
                    @click="toggleLeftSidebarInMobile = !toggleLeftSidebarInMobile"
                >
                    <ListIcon class="text-black w-6 h-6" />
                </button>
                
                <!-- for other device -->
                <div 
                    v-if="isSmallDevice && toggleLeftSidebarInMobile" 
                    class="border md:static fixed"
                    @click.self="toggleLeftSidebarInMobile=false"
                    :class="(isSmallDevice && toggleLeftSidebarInMobile) && 'z-[100] bg-black/50 top-0 left-0 h-full shadow w-full'"
                >
                    <div :class="(isSmallDevice && toggleLeftSidebarInMobile) && 'w-[300px] bg-white h-full'">
                        <slot name="left"></slot>
                    </div>
                </div>

                <!-- for desktop -->
                <div 
                    v-else
                    class="border md:block hidden"
                >
                    <div>
                        <slot name="left"></slot>
                    </div>
                </div>


                <div class="border md:border-l-0 p-6 grid gap-2 md:mt-0 mt-8" :class="activeComponent == 'SendBox' ? '_wrapper' : '_inbox_wrapper'">
                    <div class="overflow-y-auto">
                        <slot name="content"></slot>
                    </div>
                    <div v-if="getSelectedMail">
                        <slot name="mail"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import useMail from '@/Pages/Backend/Mail/useMail'
    import { onBeforeUnmount, onMounted, ref } from 'vue'
    import ListIcon from '@/Icons/ListIcon.vue'

    const { activeComponent, isSmallDevice, components, getSelectedMail, toggleLeftSidebarInMobile } = useMail()

    const setDevice = () => {
        if(document.body.offsetWidth <= 768 ){
            isSmallDevice.value = true
            return
        }
        isSmallDevice.value = false
    }

    onBeforeUnmount(() => {
        window.removeEventListener('resize', setDevice)
    })
    onMounted(() => {
        setDevice()
        window.addEventListener('resize', setDevice)
    })

</script>

<style scoped>
@media all and (min-width: 768px){
    .content{
        grid-template-columns: 300px 1fr;
    }
}
._wrapper{
    grid-template-rows: 1fr 235px;
}
._inbox_wrapper{
    grid-template-rows: 1fr;
}
</style>