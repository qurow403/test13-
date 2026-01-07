<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import { ref } from 'vue'
import * as yup from 'yup'

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

const onSubmit = (values) => {
    console.log('送信された値:', values)

    authError.value = 'ログイン情報が登録されていません'
}

definePageMeta({
    layout: 'auth'
})
</script>

<template>
    <div class="login">
        <h1>ログイン</h1>

        <Form :validation-schema="schema" @submit="onSubmit">

            <p v-if="authError" class="error">
                {{ authError }}
            </p>

            <div>
                <label>メールアドレス</label>
                <Field name="email" type="email" />
                <ErrorMessage name="email"/>
            </div>

            <div>
                <label>パスワード</label>
                <Field name="password" type="password" />
                <ErrorMessage name="password" />
            </div>

            <button type="submit">ログインする</button>

            <NuxtLink to="/register" class="register-link">
                会員登録はこちら
            </NuxtLink>

        </Form>
    </div>
</template>
