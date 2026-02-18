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
    router.push({
        query: {...route.query, tab,}
    })
})

const fetchProducts = async () => {
    let token = null

    if  (process.client) {
        token = localStorage.getItem('token')
    }

    const endpoint =
        currentTab.value === 'マイリスト'
            ? '/api/products/mylike'
            : '/api/products'

    console.log('======================')
    console.log('currentTab:', currentTab.value)
    console.log('token:', token)
    console.log('endpoint:', endpoint)
    console.log('keyword:', route.query.keyword)
    console.log('======================')


    try {
        const res = await $fetch(`http://localhost:8000${endpoint}`,{
                params: { keyword: route.query.keyword },
                headers: token
                    ? { Authorization: `Bearer ${token}` }
                    : {},
            })

        console.log('API success:', res)
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
