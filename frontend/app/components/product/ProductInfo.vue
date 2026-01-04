<script setup>
defineProps({
    categories: Array,
    condition: String,
})
</script>

<template>
<div class="info">
    <h3>商品の情報</h3>

    <p>
        カテゴリ
        <span v-for="category in categories" :key="category">
            {{ category }}
        </span>
    </p>

    <p>商品の状態 {{ condition }}</p>
</div>
</template>
