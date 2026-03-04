address.vue <script setup>
definePageMeta({
    middleware: 'auth'
})

import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

const address = ref({
    zip: '',
    address: '',
    building: '',
})

const schema = yup.object({
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

const onSubmit = async (values) => {
    console.log('submit fired', values)

    const token = localStorage.getItem('token')
    if (!token) return alert('ログインしてください')

    await $fetch('http://localhost:8000/api/profile', {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${token}`,
        },
        body: values,
    })

    address.value = values

    const router = useRouter()
    router.back()
}
</script>

<template>
<div class="address-edit">
    <h1>住所変更</h1>

    <Form :validation-schema="schema" :initial-values="address" @submit="onSubmit">

        <div>
            <label>郵便番号</label>
            <Field name="zip" type="text" />
            <ErrorMessage name="zip" class="field-error" />
        </div>

        <div>
            <label>住所</label>
            <Field name="address" type="text" />
            <ErrorMessage name="address" class="field-error" />
        </div>

        <div>
            <label>建物名</label>
            <Field name="building" type="text" />
        </div>

        <button type="submit">更新する</button>
    </Form>
</div>
</template>

<style src="@/assets/css/profile/address.css"></style>
