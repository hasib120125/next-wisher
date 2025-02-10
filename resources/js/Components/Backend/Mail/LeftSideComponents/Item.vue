<template>
    <div 
        @click="makeMailSelected(item)"
        class="flex gap-2 items-center px-4 py-2 border-b cursor-pointer transition-all select-none hover:bg-gray-50" 
        :class="[!seen ? 'bg-red-50' : '', item.isSelected ? 'text-red-500' : '']"
    >
        <div 
            class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white relative overflow-hidden"
            :class="item.role == 'talent' ? 'bg-blue-500' : item.role == 'user' ? 'bg-red-500' : ''"
            style="min-width: 40px;"
        >
            {{ getTwoLatterOfName(item, item.role) }}
        </div>
        <div class="relative w-full pt-2 pb-5">
            <div class="text-right text-sm text-gray-700">
                {{ moment(item.created_at).format('DD-MM-YYYY') }}
                <!-- {{ item.created_at }} -->
            </div>
            <h2 class="font-semibold truncate flex justify-between flex-1 w-full">
                {{ getSenderName(item, item.role) }}
            </h2>
            <h3 class="text-sm">{{ item.instructions?.replace(/<\/?p>/g, "").slice(0, 32) }}...</h3>
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import Helper from '@/Helper'
import useMail from '@/Pages/Backend/Mail/useMail'
import { get } from 'lodash'
import moment from 'moment'

const { makeMailSelected } = useMail()

defineProps({
    item: {
        type: Object,
        default: () => {}
    },
    seen: {
        type: Boolean
    }
})

const getTwoLatterOfName = (item, role) => {
    if(!item) return
    let name = null;
    if (role=='talent') {
        name = get(item, 'talent.username') || get(item, 'talent.name')
    } else {
        name = get(item, 'user.username') || get(item, 'user.name')
    }
    if(!name) return
    name = name.split(' ')
    if(name.length>1){
        return name[0][0]+name[1][0]
    }
    return name[0][0]+name[0][1]
}
const getSenderName = (item, role) => {
    if(!item) return
    let name = null;
    if (role=='talent') {
        name = get(item, 'talent.username') || get(item, 'talent.name')
    } else {
        name = get(item, 'user.username') || get(item, 'user.name')
    }
    return name
}
</script>

<style lang="scss" scoped>

</style>