<script setup>
defineProps({
  price: Number,
  paymentMethod: String,
})
</script>

<template>
  <div class="summary">
    <p>商品代金 ¥{{ price }}</p>
    <p>支払い方法 {{ paymentMethod || '未選択' }}</p>

    <button :disabled="!paymentMethod">
      購入する
    </button>
  </div>
</template>
