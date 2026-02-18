<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const checkAuth = () => {
    const tokenFromQuery = route.query.token
    const tokenFromStorage = localStorage.getItem('token')

    if (tokenFromQuery) {
        localStorage.setItem('token', tokenFromQuery)
    }

    if ( !tokenFromStorage && !tokenFromQuery) {
        navigateTo('/login')
    }
}

const avatarFile = ref(null)
const avatarPreview = ref(null)

const onSelectImage = (e) => {
    const file = e.target.files[0]
    if (!file) return

    avatarFile.value = file
    avatarPreview.value = URL.createObjectURL(file)
}

const initialValues = ref({
    name: '',
    zip: '',
    address: '',
    building: '',
})

const loaded = ref(false)

const fetchProfile = async () => {
    const token = localStorage.getItem('token')
    if (!token) return
    const user = await $fetch('http://localhost:8000/api/me', {
        headers: {
            Authorization: `Bearer ${token}`,
        }
    })

    initialValues.value = {
        name: user.name || '',
        zip: user.zip || '',
        address: user.address || '',
        building: user.building || '',
    }

    if (user.avatar) {
        avatarPreview.value = `http://localhost:8000${user.avatar}`
    }

    loaded.value = true
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

const onSubmit = async (values) => {
    try {
        const formData = new FormData()

        formData.append('name', values.name)
        formData.append('zip', values.zip)
        formData.append('address', values.address)
        formData.append('building', values.building || '')

        if (avatarFile.value) {
            formData.append('avatar', avatarFile.value)
        }

        await fetch('http://localhost:8000/api/profile', {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
            body: formData,
        })

        navigateTo('/profile')
    } catch (e) {
        console.error(e)
        alert('更新に失敗しました')
    }
}

onMounted(async () => {
    checkAuth()
    await fetchProfile()
})
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

    <Form v-if="loaded" :validation-schema="schema" :initial-values="initialValues" @submit="onSubmit">
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
