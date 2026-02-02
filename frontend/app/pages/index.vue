<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ProductTabs from '@/components/ProductTabs.vue'
import ProductCard from '@/components/ProductCard.vue'
import { mockProducts } from '@/mock/products'

const route = useRoute()
const router = useRouter()

const currentTab = ref(route.query.tab || 'おすすめ')
const products = ref([])

const USE_DUMMY = true

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

    try {
        products.value = await $fetch(`http://localhost:8000${endpoint}`, {
            headers: token
                ? { Authorization: `bearer ${token}` }
                : {},
        })
    } catch {
        products.value = []
    }
}

watch(
    () => [currentTab.value, route.query.keyword],
    () => {
        if (USE_DUMMY) {
            products.value = mockProducts
            return
        }

        fetchProducts()
    },
    { immediate: true }
)

const filteredProducts = computed(() => {
    const keyword = route.query.keyword

    if (!keyword) return products.value

    return products.value.filter(p => p.name.includes(keyword))
})
</script>

<template>
<div>
    <ProductTabs :current="currentTab" @change="currentTab = $event" />

    <div class="grid">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" />
    </div>
</div>
</template>
