<script setup>
defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    condition: {
        type: String,
        default: ''
    }
})
</script>

<template>
<div class="info">
    <h3>商品の情報</h3>

    <p>
        カテゴリ-
        <span v-for="c in categories" :key="c" class="category-tag">
            {{ c }}
        </span>
    </p>

    <p>商品の状態
        <span>{{ condition }}</span>
    </p>
</div>
</template>
