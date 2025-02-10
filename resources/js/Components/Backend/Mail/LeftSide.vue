<template>
    <div class="grid gap-2">
        <div class="flex justify-between font-semibold px-4 py-2 bg-red-500 text-white border-b">
            <button @click="changeTab('Inbox')" :class="activeComponent == 'Inbox' ? '' : 'opacity-70'">{{ Helper.translate('Inbox') }}</button>
            <button @click="changeTab('SendBox')" :class="activeComponent == 'SendBox' ? '' : 'opacity-70'">{{ Helper.translate('Sent') }}</button>
        </div>
        <div class="h-[80vh] overflow-y-auto">
            <component :is="components[activeComponent]"></component>
        </div>
    </div>
</template>

<script setup>
import useMail from '@/Pages/Backend/Mail/useMail'
import Helper from '@/Helper'
import { onMounted }  from 'vue'
import { get } from 'lodash'

const { activeComponent, isSmallDevice, components, makeMailDeselected, getMail, toggleLeftSidebarInMobile } = useMail()

const changeTab = (indexType) => {
    if(toggleLeftSidebarInMobile.value){
        getMail()
    }
    
    activeComponent.value = indexType
    let origin = String(location.href).split('?')
    let url = get(origin, '[0]');
    if (url) {
        history.replaceState({}, '', `${url}?${indexType}=1`)
    }
    if (!isSmallDevice.value) {
        makeMailDeselected()
    }
}

onMounted(()=>{
    console.log('tab Changed');
    if (String(location.search).toLocaleLowerCase().indexOf('sendbox') != -1) {
        changeTab('SendBox')
    } else {
        changeTab('Inbox')
    }
})

</script>