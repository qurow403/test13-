<script setup>
const resend = async () => {
    try {
        await $fetch('http://localhost:8000/api/email/verification-notification', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        })
        alert('認証メールを再送しました')
    } catch (e) {
        alert('認証状態の確認に失敗しました')
    }
}

const openMail = () => {
    window.open('http://0.0.0.0:8025', '_blank')
}

definePageMeta({
    layout: 'auth'
})
</script>

<template>
<div class="verify">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>

    <button class="primary" @click="openMail">
        認証はこちらから(認証メールを確認する)
    </button>

    <button class="link" @click="resend">
        認証メールを再送する
    </button>
</div>
</template>
