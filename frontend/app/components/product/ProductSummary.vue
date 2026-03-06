<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const props = defineProps({
    product: Object,
})

const goPurchase = () => {
    router.push(`/products/${props.product.id}/purchase`)
}

const emit = defineEmits(['like'])
</script>

<template>
<div class="summary">
    <h1>{{ product.name }}</h1>
    <p class="brand">{{ product.brand }}</p>

    <p class="price">
        <span class="yen">¥</span>
        {{ product.price }}
        <span class="tax">(税込)</span>
    </p>

    <div class="meta">
        <span class="like" :class="{ active: product.liked_by_me }" @click="$emit('like')">
            <span class="icon">
                {{ product.liked_by_me ? '❤' : '♡' }}
            </span>
            <span class="count">{{ product.likes }}</span>
        </span>

        <span class="comment">
            <img src="/comment.png" class="icon-img">
            <span class="count">{{ product.commentsCount }}</span>
        </span>
    </div>

    <button class="buy" @click="goPurchase">購入手続きへ</button>
</div>
</template>

<style src="@/assets/css/product/summary.css"></style>
