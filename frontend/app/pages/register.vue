<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { ref } from 'vue'

const router = useRouter()
const apiError = ref('')

const schema = yup.object({
    name: yup
        .string()
        .required('お名前を入力してください'),

    email: yup
        .string()
        .required('メールアドレスを入力してください')
        .email('メールアドレスの形式で入力してください'),

    password: yup
        .string()
        .required('パスワードを入力してください')
        .min(8, 'パスワードは8文字以上で入力してください'),

    password_confirmation: yup
        .string()
        .required('確認用パスワードを入力してください')
        .oneOf([yup.ref('password')], 'パスワードと一致しません')
})

const onSubmit = async (values) => {
    apiError.value = ''

    try {
        const res = await $fetch('http://localhost:8000/api/register', {
            method: 'POST',
            body: values,
            headers: {
                Accept: 'application/json',
            },
        })

        localStorage.setItem('token', res.token)

        router.push('/email/verify')

    } catch (error) {
        if (error?.data?.errors) {
            apiError.value = Object.values(error.data.errors)[0][0]
        } else {
            apiError.value = '会員登録に失敗しました'
        }
    }
}

definePageMeta({
    layout: 'auth'
})
</script>

<template>
    <div class="register">
        <h1>会員登録</h1>

        <Form :validation-schema="schema" @submit="onSubmit">

            <p v-if="apiError" class="error">{{ apiError }}</p>

            <div>
                <label>ユーザー名</label>
                <Field name="name" type="text" />
                <ErrorMessage name="name" class="field-error" />
            </div>

            <div>
                <label>メールアドレス</label>
                <Field name="email" type="email" />
                <ErrorMessage name="email" class="field-error" />
            </div>

            <div>
                <label>パスワード</label>
                <Field name="password" type="password" />
                <ErrorMessage name="password" class="field-error" />
            </div>

            <div>
                <label>確認用パスワード</label>
                <Field name="password_confirmation" type="password" />
                <ErrorMessage name="password_confirmation" class="field-error" />
            </div>

            <button type="submit">登録する</button>

            <NuxtLink to="/login" class="login-link">
                ログインはこちら
            </NuxtLink>

        </Form>
    </div>
</template>

<style src="@/assets/css/register.css"></style>
