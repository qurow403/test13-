<script setup>
definePageMeta({
    middleware: 'auth'
})

import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ProductCard from '~/components/ProductCard.vue'

const user = ref('null')
const currentTab = ref('出品')

const route = useRoute()
const router = useRouter()

const sellingProducts = ref([])
const purchaseProducts = ref([])

const displayProducts = computed(() => {
    return currentTab.value === '出品'
        ? sellingProducts.value
        : purchaseProducts.value
})

const getToken = () => {
    if (!process.client) return null
    return localStorage.getItem('token')
}

const fetchProfile = async () => {
    const token = getToken()
    if (!token) return

    const res = await $fetch('http://localhost:8000/api/me', {
        headers: {
            Authorization: `Bearer ${token}`
        }
    })

    user.value = res
}

const fetchProducts = async () => {
    const token = getToken()
    if (!token) return

    const res = await $fetch('http://localhost:8000/api/my-products', {
        headers: { Authorization: `Bearer ${token}` }
    })

    sellingProducts.value = res
}

const fetchPurchases = async () => {
    const token = getToken()
    if (!token) return

    const res = await $fetch('http://localhost:8000/api/purchases', {
        headers: { Authorization: `Bearer ${token}` }
    })

    purchaseProducts.value = res.map(p => p.product)
}

const handleStripeSuccess = async () => {
    const sessionId = route.query.session_id
    if (!sessionId) return

    const token = getToken()
    if (!token) return

    try {
        await $fetch('http://localhost:8000/api/purchase/success', {
            method: 'POST',
            headers: { Authorization: `Bearer ${token}` },
            body: { session_id: sessionId }
        })

        await fetchPurchases()
        await fetchProducts()

        currentTab.value = '購入'
        router.replace({ query: {} })

    } catch (e) {
        console.error(e)
    }
}

onMounted(async () => {
    await handleStripeSuccess()
    await fetchProfile()
    await fetchPurchases()
    await fetchProducts()
})
</script>

<template>
<div>
    <div class="profile">

        <div v-if="user" class="user">
            <img :src="user.avatar
                ? 'http://localhost:8000' + user.avatar
                : 'https://placehold.jp/100×100.png'"
                class="icon">
            <h2>{{ user.name }}</h2>

            <NuxtLink to="/profile/setup" class="edit">
                プロフィールを編集
            </NuxtLink>
        </div>

        <div class="tabs">
            <button :class="{ active: currentTab === '出品' }" @click="currentTab = '出品'">
                出品した商品
            </button>

            <button :class="{ active: currentTab === '購入' }" @click="currentTab = '購入'">
                購入した商品
            </button>
        </div>

        <div class="grid">
            <ProductCard v-for="p in displayProducts" :key="p.id" :product="p"/>
        </div>
    </div>

</div>
</template>
