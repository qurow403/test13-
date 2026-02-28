<script setup>
const props = defineProps({
  price: Number,
  paymentMethod: String,
})

const emit = defineEmits(['buy'])

const paymentLabels = {
  konbini: 'コンビニ支払い',
  card: 'カード払い'
}
</script>

<template>
  <div class="summary">
    <p>商品代金 ¥{{ price }}</p>

    <p>
      支払い方法
      {{ paymentLabels[paymentMethod] || '未選択' }}
    </p>

    <button :disabled="!paymentMethod" @click="emit('buy')">
      購入する
    </button>
  </div>
</template>
