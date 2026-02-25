<script setup>
import { ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ProductTabs from '@/components/ProductTabs.vue'
import ProductCard from '@/components/ProductCard.vue'

const route = useRoute()
const router = useRouter()

const currentTab = ref(route.query.tab || 'おすすめ')
const products = ref([])

watch(currentTab, (tab) => {
    router.replace({
        query: { ...route.query, tab }
    })
})

watch(
    () => route.query.tab,
    (tab) => {
        currentTab.value = tab || 'おすすめ'
    }
)

const fetchProducts = async () => {

    let token = null
    if  (process.client) {
        token = localStorage.getItem('token')
    }

    if (currentTab.value === 'マイリスト' && !token) {
        products.value = []
        return
    }

    const endpoint =
        currentTab.value === 'マイリスト'
            ? '/api/products/mylike'
            : '/api/products'

    try {
        const params = {}
        if (route.query.keyword) {
            params.keyword = route.query.keyword
        }

        const options = { params }

        if (token) {
            options.headers = {
                Authorization: `Bearer ${token}`
            }
        }

        const res = await $fetch(
            `http://localhost:8000${endpoint}`,
            options
        )

        products.value = res

    } catch (e) {
        console.error('API error:', e)
        products.value = []
    }
}

watch(
    () => [currentTab.value, route.query.keyword],
    fetchProducts,
    { immediate: true }
)
</script>

<template>
<div>
    <ProductTabs :current="currentTab" @change="currentTab = $event" />

    <div class="grid">
        <ProductCard v-for="product in products" :key="product.id" :product="product" />
    </div>
</div>
</template>
