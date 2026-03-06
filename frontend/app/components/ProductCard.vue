<script setup>
import { computed } from 'vue'

const props = defineProps({
    product: Object,
})

const imageUrl = computed(() => {
    if (!props.product.image) {
        return `https://placehold.jp/300×300.png`
    }

    if (props.product.image.startsWith('http')) {
        return props.product.image
    }

    if (props.product.image.startsWith('/images')) {
        return props.product.image
    }

    return `http://localhost:8000/storage/${props.product.image}`
})
</script>

<template>
<NuxtLink :to="`/products/${product.id}`" class="card">
    <div class="image-wrapper">
        <img :src="imageUrl" alt="商品画像" />
        <span class="sold-badge" v-if="product.is_sold">Sold</span>
    </div>
    <p class="name">{{ product.name }}</p>
</NuxtLink>
</template>

<style src="@/assets/css/product/card.css"></style>
