<script setup>
defineProps({
    product: Object,
})
</script>

<template>
    <div class="product">
        <img :src="product.image" alt="" />
        <h2>{{ product.name }}</h2>
        <p>¥{{ product.price }}</p>
    </div>
</template>
