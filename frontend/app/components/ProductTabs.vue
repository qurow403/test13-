<script setup>
defineProps({
    current: String,
})

const emit = defineEmits(['change'])

const tabs = ['おすすめ', 'マイリスト']
</script>

<template>
<div class="tabs">
    <button v-for="tab in tabs" :key="tab" :class="{ active: current === tab }" @click="emit('change', tab)">
        {{ tab }}
    </button>
</div>
</template>
