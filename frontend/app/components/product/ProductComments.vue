<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const props = defineProps({
    comments: Array,
})

const emit = defineEmits(['new-comment'])

const newComment = ref('')
const errors = ref(null)

const baseURL = 'http://localhost:8000'

const submitComment = async () => {
    try {
        errors.value = null

        const token = process.client
            ? localStorage.getItem('token')
            : null

        if (!token) {
            return router.push({
                path: '/login',
                query: { redirect: route.fullPath }
            })
        }

        if (!newComment.value.trim()) {
            errors.value = { body: ['コメントは必須です'] }
            return
        }

        const res = await $fetch(
            `http://localhost:8000/api/products/${route.params.id}/comments`,
            {
                method: 'POST',
                body: { body: newComment.value },
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            }
        )

        emit('new-comment', res)
        newComment.value = ''

    } catch (e) {
        if (e.response?._data?.errors) {
            errors.value = e.response._data.errors
        }
    }
}
</script>

<template>
<div class="comments">
    <h3>コメント ({{ comments.length }})</h3>

    <div v-for="comment in comments" :key="comment.id" class="comment-item">
        <div class="comment-header">
            <img
                :src="comment.avatar ? baseURL + comment.avatar : null"
                class="avatar"
            />
            <p class="user">{{ comment.user }}</p>
        </div>

        <p class="body">{{ comment.body }}</p>
    </div>

    <div class="comment-form">
        <h3>商品へのコメント</h3>

        <textarea v-model="newComment"></textarea>

        <p v-if="errors?.body" class="error">{{ errors.body[0] }}</p>

        <button @click="submitComment">コメントを送信する</button>
    </div>
</div>
</template>

<style src="@/assets/css/product/comment.css"></style>
