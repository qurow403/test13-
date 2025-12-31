<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

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

const onSubmit = (values) => {
    console.log('送信された値:', values)
}

definePageMeta({
    layout: 'auth'
})
</script>

<template>
    <div class="register">
        <h1>会員登録</h1>

        <Form :validation-schema="schema" @submit="onSubmit">
            <div>
                <label>ユーザー名</label>
                <Field name="name" type="text" />
                <ErrorMessage name="name" />
            </div>

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

            <div>
                <label>確認用パスワード</label>
                <Field name="password_confirmation" type="password" />
                <ErrorMessage name="password_confirmation" />
            </div>

            <button type="submit">登録する</button>

            <NuxtLink to="/login" class="login-link">
                ログインはこちら
            </NuxtLink>

        </Form>
    </div>
</template>
