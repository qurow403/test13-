<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

const address = useState('address', () => ({
    zip: '',
    address: '',
    building: '',
}))

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

const onSubmit = (values) => {
    address.value = values
    navigateBack()
}
</script>

<template>
<div class="address-edit">
    <h1>住所変更</h1>

    <Form :validation-schema="schema" @submit="onSubmit">

        <div>
            <label>郵便番号</label>
            <Field name="zip" />
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
