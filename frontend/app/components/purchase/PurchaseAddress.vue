<script setup>
defineProps({
    address: Object,
})
</script>

<template>
<div class="address">
    <div class="header">
        <h3>配送先</h3>
        <NuxtLink to="/address">変更する</NuxtLink>
    </div>

    <p>〒{{ address.zip }}</p>
    <p>{{ address.address }}</p>
    <p>{{ address.building }}</p>
</div>
</template>
