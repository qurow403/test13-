<script setup>
defineProps({
    product: Object,
})
</script>

<template>
    <div class="product">
        <img :src="product.image" alt="" />

        <div class="product-info">
            <h2>{{ product.name }}</h2>

            <p class="price">
            <span class="yen">¥</span>
                {{ product.price }}
            </p>
        </div>
    </div>
</template>
