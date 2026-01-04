<script setup>
defineProps({
    product: Object,
})
</script>

<template>
<div class="card">
    <img :src="product.image" alt="商品画像" />
    <div class="info">
        <p class="name">{{ product.name }}</p>
    </div>
</div>
</template>
