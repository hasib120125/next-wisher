<template>
    <tr class="border-b" :class="!item.seen && 'font-semibold'">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ Helper.translate(index+1, true) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
            {{ Helper.dateFormate(item.created_at) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
            {{ Helper.translate(item.name, true) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
            {{ Helper.translate(item.reason, true) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
            {{ Helper.translate(item.subject, true) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap">
            {{ Helper.translate(item.email, true) }}
        </td>
        <td class="text-sm text-gray-900 px-6 py-4 whitespace-nowrap flex justify-end gap-2">
            <button @click="showReplyModal = true" class="bg-sky-500 px-2 py-1 rounded text-white text-sm font-bold block">
                <ReplayIcon @click="handleSeen(item)" class="w-4 h-4" />
            </button>
            <button @click="handleDelete(item)" class="bg-red-500 px-2 py-1 rounded text-white text-sm font-bold block">
                <CloseIcon class="w-4 h-4" />
            </button>
        </td>
    </tr>
    <Modal v-model="showReplyModal" :isFull="true">
        <Replay :item="item" @close="showReplyModal = false" />
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Helper from '@/Helper.js'
import CloseIcon from '@/Icons/CloseIcon.vue'
import ReplayIcon from '@/Icons/ReplayIcon.vue'
import Modal from '@/Components/Library/Modal.vue'
import Replay from './Replay.vue'
import { Inertia } from '@inertiajs/inertia'


defineProps({
    item: Object,
    index: [String, Number]
})

const showReplyModal = ref(false)

const handleSeen = (item) => {
    if (Number(item.seen) == 0) {
        Inertia.post(route('admin.seenContact', item.id), {}, {
            onSuccess() {
                Inertia.reload({only: ['contacts']})
            }
        })
    }
}

const handleDelete = (item) => {
    Helper.confirm(Helper.translate('Do you really want to delete this message?'), () => {
        Inertia.post(route('admin.deleteContact', item.id), {}, {
            onSuccess() {
                Inertia.reload({only: ['contacts']})
            }
        })
    })
}

</script>