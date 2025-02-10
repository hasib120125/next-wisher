<template>
    <component :is="components[activeComponent]"></component>
</template>

<script setup>
import useLeftSidebar from '@/Components/Backend/TalentDashboard/Calendar/LeftSidebar/useLeftSidebar.js'

const { components, activeComponent } = useLeftSidebar()
</script>