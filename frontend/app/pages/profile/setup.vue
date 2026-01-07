<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { ref } from 'vue'

const avatarPreview = ref(null)

const onSelectImage = (e) => {
    const file = e.target.files[0]
    if (!file) return

    avatarPreview.value = URL.createObjectURL(file)
}

const schema = yup.object({
    name: yup
        .string()
        .required('ユーザー名を入力してください'),

    zip: yup
        .string()
        .required('郵便番号を入力してください'),

    address: yup
        .string()
        .required('住所を入力してください'),

    building: yup
        .string()
        .nullable(),
})

const onSubmit = (values) => {
    console.log('プロフィール設定:', values)
}
</script>

<template>
<div class="profile-setup">
    <h1>プロフィール設定</h1>

    <div class="avatar-area">
        <div class="avatar">
            <img v-if="avatarPreview" :src="avatarPreview" alt="プロフィール画像" />
            <div v-else class="placeholder">No Image</div>
        </div>

        <label class="image-select">
            画像を選択する
            <input type="file" hidden="hidden" accept="image/*" @change="onSelectImage">
        </label>
    </div>

    <Form :validation-schema="schema" @submit="onSubmit">
        <div>
            <label>ユーザー名</label>
            <Field name="name" />
            <ErrorMessage name="name" />
        </div>

        <div>
            <label>郵便番号</label>
            <Field name="zip"/>
            <ErrorMessage name="zip" />
        </div>

        <div>
            <label>住所</label>
            <Field name="address" />
            <ErrorMessage name="address" />
        </div>

        <div>
            <label>建物名</label>
            <Field name="building" />
        </div>

        <button type="submit">更新する</button>
    </Form>
</div>
</template>
