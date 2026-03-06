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
        <span class="label">カテゴリー</span>
        <span v-for="c in categories" :key="c" class="category-tag">
            {{ c }}
        </span>
    </p>

    <p>
        <span class="label">商品の状態</span>
        <span>{{ condition }}</span>
    </p>
</div>
</template>

<style scoped>
.info h3 {
    font-size: 28px;
}

.info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.label {
    font-weight: bold;
    margin-right: 50px;
}

.category-tag {
    background-color: #e0e0e0;
    border-radius: 20px;
    padding: 4px 12px;
    margin-right: 17px;
}
</style>
