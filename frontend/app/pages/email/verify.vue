<script setup>
definePageMeta({
    layout: 'auth'
})

const resend = async () => {
    await $fetch('/api/email/resend', { method: 'POST' })
    alert('認証メールを再送しました')
}
</script>

<template>
<div class="verify">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>

    <a href="https://mail.google.com" target="_blank" class="primary">
        認証はこちらから
    </a>

    <button class="link" @click="resend">認証メールを再送する</button>
</div>
</template>
