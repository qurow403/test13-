<script setup>
import { ref, computed } from 'vue'
import ProductCard from '~/components/ProductCard.vue'
import { mockProducts } from '~/mock/products'

const user = {
    name: '山田太郎',
    icon: 'https://placehold.jp/100×100.png'
}

const currentTab = ref('出品')

const sellingProducts = mockProducts.slice(0, 5)
const purchaseProducts = mockProducts.slice(5)

const displayProducts = computed(() => {
    return currentTab.value === '出品'
        ? sellingProducts
        : purchaseProducts
})
</script>

<template>
<div>
    <div class="profile">
        <div class="user">
            <img :src="user.icon" class="icon">
            <h2>{{ user.name }}</h2>

            <NuxtLink to="/profile/setup" class="edit">
                プロフィールを編集
            </NuxtLink>
        </div>

        <div class="tabs">
            <button :class="{ active: currentTab === '出品' }" @click="currentTab === '出品'">
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