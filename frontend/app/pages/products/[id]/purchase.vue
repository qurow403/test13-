<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router';
import { onMounted } from 'vue'

import PurchaseProduct from '~/components/purchase/PurchaseProduct.vue'
import PurchasePayment from '~/components/purchase/PurchasePayment.vue'
import PurchaseAddress from '~/components/purchase/PurchaseAddress.vue'
import PurchaseSummary from '~/components/purchase/PurchaseSummary.vue'

const route = useRoute()
const productId = route.params.id

const product = ref(null)
const paymentMethod = ref('')
const address = useState('address', () => null)

const fetchProduct = async () => {
    try {
        product.value = await $fetch(`http://localhost:8000/api/products/${productId}`)
    } catch (e) {
        console.error(e)
        alert('商品の取得に失敗')
    }
}

const buy = async () => {
    try {
        const token = localStorage.getItem('token')
        if (!token) return alert('ログインしてください')

        if (!address.value?.zip || !address.value?.address) {
            return alert('住所が未設定です')
        }

        if (!paymentMethod.value) {
            return alert('支払い方法を選択してください')
        }

        console.log('送信:', address.value, paymentMethod.value)

        const res = await $fetch(
            `http://localhost:8000/api/products/${productId}/purchase`,
            {
                method: 'POST',
                headers: { Authorization: `Bearer ${token}` },
                body: {
                    zip: address.value?.zip,
                    address: address.value?.address,
                    building: address.value?.building,
                    payment_method: paymentMethod.value,
                }
            }
        )

        alert('購入完了!')
        navigateTo('/')

    } catch (e) {
        console.error('購入エラー:', e.response?._data)
        alert(JSON.stringify(e.response?._data))
    }
}

const fetchProfile = async () => {
    const token = localStorage.getItem('token')
    if (!token) return

    const user = await $fetch('http://localhost:8000/api/me', {
        headers: {
            Authorization: `Bearer ${token}`,
        }
    })

    address.value = {
        zip: user.zip,
        address: user.address,
        building: user.building,
    }
}

onMounted(() => {
    fetchProduct()
    fetchProfile()
})
</script>

<template>
<div v-if="product" class="purchase">
    <div class="left">
        <PurchaseProduct :product="product" />
        <PurchasePayment v-model="paymentMethod" />
        <PurchaseAddress v-if="address" :address="address" />
    </div>

    <div class="right">
        <PurchaseSummary :price="product.price" :paymentMethod="paymentMethod" @buy="buy" />
    </div>
</div>

<div v-else>
    商品を読み込み中...
</div>
</template>
