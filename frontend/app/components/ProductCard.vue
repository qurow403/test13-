<script setup>
const props = defineProps({
    product: Object,
})
</script>

<template>
<NuxtLink :to="`/products/${product.id}`" class="card">
    <div class="image-wrapper">
        <img :src="product.image" alt="商品画像" />
        <span class="sold-badge" v-if="product.is_sold">Sold</span>
    </div>
    <p class="name">{{ product.name }}</p>
</NuxtLink>
</template>
