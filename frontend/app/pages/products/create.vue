<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { ref } from 'vue'

definePageMeta({
    layout: 'auth'
})

const imagePreview = ref(null)

const onSelectImage = (e) => {
    const file = e.target.files[0]
    if (!file) return
    imagePreview.value = URL.createObjectURL(file)
}

const categories = [
    'ファッション','家電','インテリア','レディース','メンズ','コスメ','本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー','おもちゃ','ベビー・キッズ'
]

const conditions = [
    '良好',
    '目立った傷や汚れなし',
    'やや傷や汚れあり',
    '状態が悪い',
]

const schema = yup.object({
    category: yup
        .string()
        .required('カテゴリーを選択してください'),

    condition: yup
        .string()
        .required('商品の状態を選択してください'),

    price: yup
        .number()
        .typeError('数値を入力してください')
        .required('販売価格を入力してください'),
})

const onSubmit = (values) => {
    console.log('出品データ:', values)
}
</script>

<template>
<div class="sell">
    <h1>商品の出品</h1>

    <section class="box">
        <h2>商品画像</h2>

        <div class="image-box">
            <img v-if="imagePreview" :src="imagePreview" />
            <label v-else class="image-select">
                画像を選択する
                <input type="file" hidden accept="image/*" @change="onSelectImage">
            </label>
        </div>
    </section>

    <Form validation="schema" @submit="onSubmit">
        <section class="box">
            <h2>商品の詳細</h2>

            <div>
                <label>カテゴリー</label>

                <div class="category-buttons">
                    <label v-for="c in categories" :key="c" class="category-button">
                        <Field type="radio" name="category" :value="c" class="hidden-radio" />
                        <span>{{ c }}</span>
                    </label>
                </div>

                <ErrorMessage name="category" />
            </div>

            <div>
                <label>商品の状態</label>
                <Field as="select" name="condition">
                    <option value="">選択してください</option>
                    <option v-for="c in conditions" :key="c" value="c">{{ c }}</option>
                    <ErrorMessage name="condition" />
                </Field>
            </div>
        </section>

        <section class="box">
            <h2>商品名と説明</h2>

            <div>
                <label>商品名</label>
                <Field name="name" />
                <ErrorMessage name="name" />
            </div>

            <div>
                <label>ブランド名</label>
                <Field name="brand" />
            </div>

            <div>
                <label>商品の説明</label>
                <Field as="textarea" name="description" />
            </div>

            <div>
                <label>販売価格</label>

                <div class="price-input">
                    <span class="yen">¥</span>
                    <Field name="price" class="input" />
                </div>

                <ErrorMessage name="price" />
            </div>
        </section>

        <button type="submit" class="submit">出品する</button>
    </Form>
</div>
</template>
