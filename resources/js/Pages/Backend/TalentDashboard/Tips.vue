<template>
  <Head title="Tips" />
  <DashboardLayout>
    <template v-slot:leftSidebar>
        <LeftSide />
    </template>
    <template v-slot:content>
      <h1 class="font-semibold text-xl mb-4">{{ Helper.translate('Tips') }}</h1>
      <h2 class="text-lg">
          {{ Helper.translate('Please activate the Tips option to receive tips from your fans or followers') }}
      </h2>
      <div class="font-bold mt-10 grid">
        <h2 class="text-lg mb-2">{{ Helper.translate('Activate') }}</h2>
        <Switch @change="(val) => handleSave(val)" :loading="loading" v-model="form.status" />
      </div>

      <Link :href="route('item.preview', $page.props.auth.user.id)" class="px-4 mt-10 py-2 rounded text-xl bg-black text-white font-bold text-center w-fit">
        {{ Helper.translate('Preview Profile') }}
      </Link>
    </template>
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '../DashboardLayout.vue'
import LeftSide from '@/Components/Backend/TalentDashboard/LeftSide.vue';
import Switch from '@/Components/Global/Switch.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/inertia-vue3';
import { onMounted, ref } from 'vue';
import { isEmpty } from 'lodash';
import Helper from '@/Helper';

// talent.tips.saveAmount


const form = useForm({
    status: 0,
    type: 'tips',
});

const loading = ref(false)

onMounted(()=> {
    let tips = usePage().props.value.tips;
    if (!isEmpty(tips)) {
        form.status = tips.status;
    }
})

const handleSave = (val) => { 
    form.status = val;
    loading.value = true
    form.post(route('talent.tips.saveAmount'), {
      onSuccess(e) {
        if (!isEmpty(e.props.errors)) {
          form.status = usePage().props.value.tips.status;
        }
        loading.value = false
      },
      onError(err){
        if (!isEmpty(err)) {
          form.status = usePage().props.value.tips.status
        }
        loading.value = false
      }
    })
}
</script>


<style scoped>
  .content{
    grid-template-columns: 250px 1fr;
  }      
</style>