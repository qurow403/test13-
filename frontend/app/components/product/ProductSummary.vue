<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
    product: Object,
})

const goPurchase = () => {
    router.push(`/products/${props.product.id}/purchase`)
}
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

    <button class="buy" @click="goPurchase">購入手続きへ</button>
</div>
</template>
