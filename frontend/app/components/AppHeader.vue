<script setup>
import { ref, watch } from 'vue'
import { useRouter,useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const keyword = ref(route.query.keyword || '')

watch(keyword, (newVal) => {
    router.push({
        query: {
            ...route.query,
            keyword: newVal || undefined,
        }
    })
})

const logout = async () => {
    if (!ProcessingInstruction.client) return

    try {
        await $fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        })
    } catch (e) {
        console.warn('logout api failed')
    }

    localStorage.removeItem('token')
    router.push('/login')
}
</script>

<template>
    <header class="header">
        <div class="header-left">
            <NuxtLink to="/" class="logo-link">
                <img src="/logo.png" alt="COACHTECH" class="logo-image" />
                <span class="logo-text">COACHTECH</span>
            </NuxtLink>
        </div>

        <div class="header-center">
            <input type="text" placeholder="なにをお探しですか？" class="search" />
        </div>

        <div class="header-right">
            <button @click="logout">ログアウト</button>
            <NuxtLink to="/profile">マイページ</NuxtLink>
            <NuxtLink to="/products/sell">出品</NuxtLink>
        </div>
    </header>
</template>
