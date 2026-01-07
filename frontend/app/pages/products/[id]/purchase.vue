<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router';

import PurchaseProduct from '~/components/purchase/PurchaseProduct.vue'
import PurchasePayment from '~/components/purchase/PurchasePayment.vue'
import PurchaseAddress from '~/components/purchase/PurchaseAddress.vue'
import PurchaseSummary from '~/components/purchase/PurchaseSummary.vue'

const route = useRoute()
const productId = route.params.id

const product = {
    id: productId,
    name: '腕時計',
    price: 15000,
    image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
}

const paymentMethod = ref('')

const address = useState('address', () => ({
    zip: '123-4567',
    address: '東京都渋谷区〇〇',
    building: '〇〇マンション101',
}))
</script>

<template>
<div class="purchase">
    <div class="left">
        <PurchaseProduct :product="product" />
        <PurchasePayment v-model="paymentMethod" />
        <PurchaseAddress :address="address" />
    </div>

    <div class="right">
        <PurchaseSummary :price="product.price" :paymentMethod="paymentMethod" />
    </div>
</div>
</template>
