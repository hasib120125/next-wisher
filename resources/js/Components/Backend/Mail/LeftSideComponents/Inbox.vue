<template>
    <template v-if="!isEmpty(mailList)">
        <Item 
            v-for="(item, index) in getMailList($page.props.auth?.user?.role)" 
            :key="index"
            :item="item"
            :seen="Boolean(+item.seen)"
        >
            <div v-if="get(item, 'talent_earning.settings.time')" class="absolute bottom-0 right-0 text-xs font-semibold text-green-500">
                {{ get(item, 'talent_earning.settings.time') }}
            </div>
            <Timer
                v-else
                :expirationDate="item.expirationDate"
                :dateDifferenceInMillisecond="item.duration_millisecond"
                class="absolute bottom-0 right-0"
            />
        </Item>
    </template>
    <div v-else-if="loadingMail" class="flex justify-center items-center py-10">
        <Preloader class="w-6 h-6" />
    </div>
    <Alert v-else title="No messages" />
</template>

<script setup>
import Item from '@/Components/Backend/Mail/LeftSideComponents/Item.vue'
import Timer from '@/Components/Backend/Mail/LeftSideComponents/Timer.vue'
import useMail from '@/Pages/Backend/Mail/useMail'
import { isEmpty, get } from 'lodash'
import Alert from '@/Components/Global/Alert.vue'
import Helper from '@/Helper'
import Preloader from '@/Components/Global/Preloader.vue'

const { mailList, loadingMail } = useMail()

const getMailList = (role) => {
    if(role == 'talent'){
        return mailList.value.filter(item => item.role == 'user')
    }
    if(role == 'user'){
        return mailList.value.filter(item => item.role == 'talent')
    }
}

</script>