<script setup>
definePageMeta({
    layout: 'default',
    middleware: 'auth'
})

import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const imageFile = ref(null)
const imagePreview = ref(null)
const isSubmitting = ref(false)

const categories = ref([])
const conditions = ref([])

onMounted(async () => {
    categories.value = await $fetch('http://localhost:8000/api/categories')
    conditions.value = await $fetch('http://localhost:8000/api/conditions')
})

const onSelectImage = (e) => {
    const file = e.target.files[0]
    if (!file) return

    imageFile.value = file
    imagePreview.value = URL.createObjectURL(file)
}

const schema = yup.object({
    category_ids: yup
        .array()
        .required('カテゴリーを1つ以上選択してください')
        .min(1, 'カテゴリーを1つ以上選択してください'),

    condition_id: yup
        .string()
        .required('商品の状態を選択してください'),

    name: yup
        .string()
        .required('商品名を入力してください'),

    description: yup
        .string()
        .required('商品の説明を入力してください'),

    price: yup
        .number()
        .typeError('数値を入力してください')
        .required('販売価格を入力してください'),
})

const onSubmit = async (values) => {
    if (!imageFile.value) {
        alert('画像を選択してください')
        return
    }

    isSubmitting.value = true

    try {
        const token = localStorage.getItem('token')

        const form = new FormData()

        Object.entries(values).forEach(([k, v]) => {
            if (Array.isArray(v)) {
                v.forEach(val => form.append(`${k}[]`, val))
            } else {
                form.append(k, v)
            }
        })

        form.append('image', imageFile.value)

        await $fetch('http://localhost:8000/api/products', {
            method: 'POST',
            body: form,
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json'
            },
        })

        await router.push('/profile')

    } catch (e) {
        console.error(e)
        alert('出品に失敗しました')
    } finally {
        isSubmitting.value = false
    }
}
</script>

<template>
<div class="sell">
    <h1>商品の出品</h1>

    <section class="box image-section">
        <h2>商品画像</h2>

        <div class="image-box">
            <img v-if="imagePreview" :src="imagePreview" />
            <label class="image-select">
                <span v-if="!imagePreview">画像を選択する</span>
                <input type="file" hidden accept="image/*" @change="onSelectImage">
            </label>
        </div>
    </section>

    <Form :validation-schema="schema" @submit="onSubmit">
        <section class="box">
            <h2>商品の詳細</h2>

            <div class="form-item">
                <label>カテゴリー</label>

                <div class="category-buttons">
                    <label v-for="c in categories" :key="c.id" class="category-button">
                        <Field type="checkbox" name="category_ids" :value="c.id" class="hidden-checkbox" />
                        <span>{{ c.name }}</span>
                    </label>
                </div>

                <ErrorMessage name="category_ids" class="error" />
            </div>

            <div class="form-item">
                <label>商品の状態</label>
                <Field as="select" name="condition_id" class="select">
                    <option value="">選択してください</option>
                    <option v-for="c in conditions" :key="c.id" :value="c.id">
                        {{ c.name }}
                    </option>
                </Field>
                <ErrorMessage name="condition_id" class="error" />
            </div>
        </section>

        <section class="box">
            <h2>商品名と説明</h2>

            <div class="form-item">
                <label>商品名</label>
                <Field name="name" class="input" />
                <ErrorMessage name="name" class="error" />
            </div>

            <div class="form-item">
                <label>ブランド名</label>
                <Field name="brand" class="input" />
            </div>

            <div class="form-item">
                <label>商品の説明</label>
                <Field as="textarea" name="description" class="textarea" />
                <ErrorMessage name="description" class="error" />
            </div>

            <div class="form-item">
                <label>販売価格</label>

                <div class="price-input">
                    <span class="yen">¥</span>
                    <Field name="price" type="number" class="input" />
                </div>

                <ErrorMessage name="price" class="error" />
            </div>
        </section>

        <button type="submit" class="submit" :disabled="isSubmitting">
            {{ isSubmitting ? '出品中...' : '出品する' }}
        </button>
    </Form>
</div>
</template>

<style src="@/assets/css/product/sell.css"></style>
