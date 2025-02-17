<template>
    <div v-if="data.length > 0" class="flex gap-2 items-center md:justify-start justify-center">
        {{ Helper.translate('Show') }} 
        <select v-model="pageSize" @change="search" class="border-[#0001] rounded-lg">
            <option v-for="(item, index) in pageSizeArray" :key="index" :value="item">{{ Helper.translate(item) }}</option>
        </select>
        {{ Helper.translate('Entries') }}
    </div>
</template>

<script setup>
    import { onMounted } from 'vue'
    import useTable from '@/Components/Backend/Global/Table/useTable.js'
    import Helper from '@/Helper'
    
    const { pageSize, pageSizeArray, search, hasPagination, data } = useTable()
    defineProps({
        defaultPageSize: [Number, String],
    })

    onMounted(() => {
        hasPagination.value = true
    })
</script>