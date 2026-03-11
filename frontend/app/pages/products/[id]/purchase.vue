<script setup>
definePageMeta({
    middleware: 'auth'
})

import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router';

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
    product.value = await $fetch(`http://localhost:8000/api/products/${productId}`)

    const token = localStorage.getItem('token')
    if (token) {
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
}

const buy = async () => {
    if (!paymentMethod.value) {
        return alert('支払い方法を選択してください')
    }

    if (!process.client) return

    try {
        const token = localStorage.getItem('token')
        if (!token) return alert('ログインしてください')

        const res = await $fetch(
            `http://localhost:8000/api/products/${productId}/purchase`,
            {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: `application/json`
                },
                body: {
                    zip: address.value?.zip,
                    address: address.value?.address,
                    building: address.value?.building,
                    payment_method: paymentMethod.value,
                }
            }
        )

        window.location.href = res.url

    } catch (e) {
        console.error(e)

        if (e.response?._data) {
            alert(JSON.stringify(e.response._data))
        } else {
            alert(e.message)
        }
    }
}

const fetchProfile = async () => {
    if (!process.client) return

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

<style src="@/assets/css/purchase/purchase.css"></style>
