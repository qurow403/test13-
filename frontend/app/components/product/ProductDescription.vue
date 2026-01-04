<script setup>
defineProps({
    description: String,
})
</script>

<template>
    <div class="description">
        <h3>商品説明</h3>
        <p>{{ description }}</p>
    </div>
</template>
