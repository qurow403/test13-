<script setup>
const props = defineProps ({
    modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (e) => {
    emit('update:modelValue', e.target.value)
}

const paymentOptions = [
    { value: 'konbini', label: 'コンビニ払い' },
    { value: 'card', label: 'カード払い'  }
]
</script>

<template>
<div class="payment">
    <h3>支払い方法</h3>

    <select :value="modelValue" @change="updateValue">
        <option value="">選択してください</option>

        <option v-for="option in paymentOptions" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</div>
</template>
