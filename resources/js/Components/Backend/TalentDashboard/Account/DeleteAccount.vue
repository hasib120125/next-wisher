<template>
    <h1 class="font-semibold text-xl mb-4 text-center">{{ Helper.translate('Delete Your Account') }}</h1>
    <div class="flex justify-center gap-6">
        <button class="bg-gray-500 text-white text-bold rounded shadow px-4 py-2 whitespace-nowrap block w-fit mt-4">
            {{ Helper.translate('No') }}
        </button>
        <button @click="handleDelete" class="bg-red-500 text-white text-bold rounded shadow px-4 py-2 whitespace-nowrap block w-fit mt-4">
            {{ Helper.translate('Yes') }}
        </button>
    </div>
</template>

<script setup>
import Helper from '@/Helper'
import { isEmpty } from 'lodash'
import { useForm } from '@inertiajs/inertia-vue3'


const form = useForm({
    deleteUser: null
})

const handleDelete = () => {
    Helper.confirm(Helper.translate('Do you really want to delete your account?')+'<br>'+Helper.translate('This process cannot be undone.'), () => {
        form.deleteUser = true;
        handleSave();
    })
}

const handleSave = () => {
    form.delete(route('admin.fource_delete_account'), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
            }
        }
    })
}
</script>

<style lang="scss" scoped>

</style>