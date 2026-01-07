<script setup>
import { ref } from 'vue';

defineProps({
    comments: Array,
})

const newComment = ref('')

const submitComment = () => {
    if (!newComment.value.trim()) return
    console.log('送信コメント:', newComment.value)
    newComment.value = ''
}
</script>

<template>
<div class="comments">
    <h3>コメント ({{ comments.length }})</h3>

    <div v-for="comment in comments" :key="comment.id">
        <div class="comment-header">
            <img :src="comment.avatar"  alt="プロフィール画像" class="avatar" />
            <p class="user">{{ comment.user }}</p>
        </div>

        <p class="body">{{ comment.body }}</p>
    </div>

    <div class="comment-form">
        <h3>商品へのコメント</h3>

        <textarea v-model="newComment"></textarea>

        <button @click="submitComment">コメントを送信する</button>
    </div>
</div>
</template>
