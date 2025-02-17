<template>
    <label 
            class="relative"
        >
            <input @input="$emit('update', sim.mno)" :value="sim.mno" :checked="sim.mno == selected_sim" type="radio" name="country_name" class="hidden peer" />
            <div class="px-4 py-2 border-gray-400 peer-checked:ring-4 ring-blue-500/50 border rounded-md overflow-hidden flex items-center justify-center">
                <!-- <img :src="cameroon_mtn" alt="" class="block w-full h-full object-cover" /> -->
                {{ sim.mno }}
            </div>
        </label>
</template>

<script setup>

const props = defineProps({
    sim: Array,
    selected_sim: String,
    selected_country: Object,
})

</script>