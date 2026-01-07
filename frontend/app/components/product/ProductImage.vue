<script setup>
defineProps({
    image:String,
})
</script>

<template>
<div class="image">
    <img :src="image" alt="商品画像" />
</div>
</template>
