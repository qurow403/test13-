<script setup>
defineProps({
    product: Object,
})
</script>

<template>
<div class="summary">
    <h1>{{ product.name }}</h1>
    <p class="brand">{{ product.brand }}</p>
    <p class="price">¥{{ product.price }}(税込)</p>

    <div class="meta">
        <span>❤️ {{ product.likes }}</span>
        <span>💬 {{ product.commentsCount }}</span>
    </div>

    <button class="buy">購入手続きへ</button>
</div>
</template>
