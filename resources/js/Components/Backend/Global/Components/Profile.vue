<template>
    <div v-if="user" class="relative group">
        <span 
            class="font-black ml-1 w-8 h-8 md:text-[11px] text-white text-[10px] grid content-center justify-center rounded-full p-2 cursor-pointer uppercase"
            :class="
                user.role && user.role == 'talent' ? 'bg-blue-500' : 
                user.role && user.role == 'admin' ? 'bg-green-500' : 
                user.role && user.role == 'user' ? 'bg-red-500' : ''
            "
        >
            {{ getTwoLatterOfName(user.username??user.name) }}
        </span>
        <div class="opacity-0 invisible group-hover:visible group-hover:opacity-100 dropdown grid absolute right-[-4px] top-[38px] text-[var(--text-color)] rounded shadow-lg z-10">
            <span class="w-2 h-2 bg-white absolute right-4 rotate-45 bottom-full transform translate-y-1/2"></span>
            <Link
                class="px-4 py-2 border-b border-b-[--text-color] border-opacity-20 hover:bg-gray-100 transition-all rounded-t" 
                :href="
                    user.role && user.role == 'talent' ? route('talent.dashboard') : 
                    user.role && user.role == 'admin' ? route('admin.dashboard') : 
                    user.role && user.role == 'user' ? route('user.dashboard') : 
                    route('home')
                "
            >
                {{ Helper.translate('Dashboard') }}
            </Link>
            <Link 
                class="px-4 py-2 hover:bg-gray-100 transition-all rounded-b"
                :href="route('logout')" 
                method="post"
            >
                {{ Helper.translate('Logout') }}
            </Link>
        </div>
    </div>
    <button v-else @click="showAuthModal = true" class="whitespace-nowrap font-semibold max-lg:text-sm lg:px-6 px-2 py-1 text-opacity-60 hover:text-opacity-100 rounded-full">
        {{ Helper.translate('Sign up') }}
    </button>

    <Modal v-model="showAuthModal">
        <CustomAuth @close="showAuthModal = false" />
    </Modal>

</template>

<script setup>
    import { get } from 'lodash'
    import { computed } from 'vue'
    import { Link, usePage } from '@inertiajs/inertia-vue3'
    import Modal from '@/Components/Library/Modal.vue'
    import CustomAuth from '@/Components/Frontend/CustomAuth/Index.vue'
    import Helper, { showAuthModal } from '@/Helper'

    const user = computed(() => {
        if(usePage().props.value.auth.user){
            const { role, name } = get(usePage().props.value, 'auth.user')
            console.log(name);
            return { role, name }
        }

        return null
    })

    const getTwoLatterOfName = (name) => {
        if(!name) return
        name = name.split(' ')
        if(name.length>1){
            return name[0][0]+name[1][0]
        }
        return name[0][0]+name[0][1]
    }
</script>