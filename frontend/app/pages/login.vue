<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import * as yup from 'yup'

const route = useRoute()
const router = useRouter()

const authError = ref('')

const schema = yup.object({
    email: yup
        .string()
        .required('メールアドレスを入力してください')
        .email('メールアドレスの形式で入力してください'),

    password: yup
        .string()
        .required('パスワードを入力してください')
        .min(8, 'パスワードは8文字以上で入力してください'),
})

const onSubmit = async (values) => {
    authError.value = ''

    try {
        const res = await $fetch('http://localhost:8000/api/login', {
            method: 'POST',
            body: values,
            headers: {
                Accept: 'application/json',
            },
        })

        localStorage.setItem('token', res.token)

        const redirectPath = route.query.redirect

        if (!res.user.profile_completed) {
            return router.push('/profile/setup')
        }

        router.push(redirectPath || '/')

    } catch (error) {
        if (error?.data?.message) {
            authError.value = error.data.message
        } else {
            authError.value = 'ログインに失敗しました'
        }
    }
}

definePageMeta({
    layout: 'auth'
})
</script>

<template>
    <div class="auth-page">
        <h1>ログイン</h1>

        <Form :validation-schema="schema" @submit="onSubmit">

            <p v-if="authError" class="error">
                {{ authError }}
            </p>

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

            <button type="submit">ログインする</button>

            <NuxtLink to="/register" class="register-link">
                会員登録はこちら
            </NuxtLink>

        </Form>
    </div>
</template>

<style src="@/assets/css/login.css"></style>
