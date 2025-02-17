<template>
    <CInput v-if="data.length" type="search" v-model="searchString" @input="search" placeholder="Search" class="mx-auto max-w-[400px] w-full" />
</template>

<script setup>
import CInput from '@/Components/Global/CInput.vue'
import useTable from '@/Components/Backend/Global/Table/useTable.js'

const { searchString, search, data } = useTable()
</script>