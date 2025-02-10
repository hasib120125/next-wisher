<template>
    <div class="border rounded p-4 py-6 grid gap-2 bg-white shadow-lg">
        <div class="flex gap-4 mb-1">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="green" xmlns="http://www.w3.org/2000/svg"><g data-name="Layer 2"><g data-name="checkmark-square"><path d="M20 11.83a1 1 0 0 0-1 1v5.57a.6.6 0 0 1-.6.6H5.6a.6.6 0 0 1-.6-.6V5.6a.6.6 0 0 1 .6-.6h9.57a1 1 0 1 0 0-2H5.6A2.61 2.61 0 0 0 3 5.6v12.8A2.61 2.61 0 0 0 5.6 21h12.8a2.61 2.61 0 0 0 2.6-2.6v-5.57a1 1 0 0 0-1-1z"/><path d="M10.72 11a1 1 0 0 0-1.44 1.38l2.22 2.33a1 1 0 0 0 .72.31 1 1 0 0 0 .72-.3l6.78-7a1 1 0 1 0-1.44-1.4l-6.05 6.26z"/></g></g></svg>
            <h2 class="font-black text-md">{{ Helper.translate('Money Back Guarantee') }}</h2>
        </div>
        <p class="text-center text-sm">
            {{ Helper.translate('If your request for a personalized video could not be completed or expires, we will refund your money within 3-5 working days.') }}
        </p>
        <p class="text-center text-sm">
            {{ Helper.translate('Refunds can only be sent back to the original payment method used in a charge.') }}
        </p>

        <img 
            class="w-[140px] block mx-auto mt-2"
            src="/paymentIcons.png" 
        />
    </div>
</template>

<script setup>
    import Helper from '@/Helper'
</script>