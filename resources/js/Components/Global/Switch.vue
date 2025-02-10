<template>
    <div class="relative">
        <input type="radio" v-model="selectedValue" :value='1' id="yes" />
        <input type="radio" v-model="selectedValue" :value='0' id="no"/>
        <div class="switch">
          <label for="yes">{{ Helper.translate('Yes') }}</label>
          <label for="no">{{ Helper.translate('No') }}</label>
          <div v-if="loading" class="top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 absolute">
            <Preloader class="w-5 h-5 text-white" />
          </div>
          <span v-else></span>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import Preloader from '@/Components/Global/Preloader.vue'
import Helper from '@/Helper';

const props = defineProps({
  modelValue: {
    type: [Boolean, Number],
    default: 0
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['updated', 'change'])
const selectedValue = computed({
  get(){
    return props.modelValue
  },
  set(value){
    emit('update:modelValue', value)
    emit('change', value) 
  }
})
</script>

<style scoped>
.switch {
  position: absolute;
  width: 150px;
  height: 50px;
  text-align: center;
  background: #3e9b3c;
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  -o-transition: all 0.2s ease;
  -ms-transition: all 0.2s ease;
  transition: all 0.2s ease;
  border-radius: 25px;
}

.switch span {
  position: absolute;
  width: 20px;
  height: 4px;
  top: 50%;
  left: 50%;
  margin: -2px 0px 0px -4px;
  background: #fff;
  display: block;
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  transform: rotate(-45deg);
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  -o-transition: all 0.2s ease;
  -ms-transition: all 0.2s ease;
  transition: all 0.2s ease;
  border-radius: 2px;
}

.switch span:after {
  content: "";
  display: block;
  position: absolute;
  width: 4px;
  height: 12px;
  margin-top: -8px;
  background: #fff;
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  -o-transition: all 0.2s ease;
  -ms-transition: all 0.2s ease;
  transition: all 0.2s ease;
  border-radius: 2px;
}

input[type=radio] {
  display: none;
}

.switch label {
  cursor: pointer;
  color: rgba(0, 0, 0, 0.2);
  width: 60px;
  line-height: 50px;
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  -o-transition: all 0.2s ease;
  -ms-transition: all 0.2s ease;
  transition: all 0.2s ease;
}

label[for=yes] {
  position: absolute;
  left: 0px;
  height: 20px;
}

label[for=no] {
  position: absolute;
  right: 0px;
}

#no:checked~.switch {
  background: #ff3b30;
}

#no:checked~.switch span {
  background: #fff;
  margin-left: -8px;
}

#no:checked~.switch span:after {
  background: #fff;
  height: 20px;
  margin-top: -8px;
  margin-left: 8px;
}

#yes:checked~.switch label[for=yes] {
  color: #fff;
}

#no:checked~.switch label[for=no] {
  color: #fff;
}</style>