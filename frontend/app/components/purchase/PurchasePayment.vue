<script setup>
const props = ({
    modelValue: String,
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (e) => {
    emit('update:modelValue', e.target.value)
}
</script>

<template>
<div class="payment">
    <h3>支払い方法</h3>

    <select :value="modelValue" @change="updateValue">
        <option value="">選択してください</option>
        <option value="コンビニ支払い">コンビニ支払い</option>
        <option value="カード払い">カード払い</option>
    </select>
</div>
</template>
