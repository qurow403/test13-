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
    <div class="summary-box">
      <p>
        <span>商品代金</span>

        <span class="summary-price">
          <span class="yen">¥</span>
          {{ price }}
        </span>
      </p>

      <p>
        <span>支払い方法</span>
        <span>
        {{ paymentLabels[paymentMethod] || '未選択' }}
        </span>
      </p>
    </div>

    <button :disabled="!paymentMethod" @click="emit('buy')">
      購入する
    </button>
  </div>
</template>
