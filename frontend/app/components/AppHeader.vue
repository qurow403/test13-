<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { useRouter,useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const keyword = ref(route.query.keyword || '')
const token = ref(null)

onMounted(() => {
    if (process.client) {
        token.value = localStorage.getItem('token')
    }
})

const isLoggedIn = computed(() => !!token.value)

watch(keyword, (newVal) => {
    router.push({
        query: {
            ...route.query,
            keyword: newVal || undefined,
        }
    })
})

const logout = async () => {
    if (!process.client) return

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
    token.value = null
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
            <input v-model="keyword" type="text" placeholder="なにをお探しですか？" class="search" />
        </div>

        <div class="header-right">
            <button v-if="isLoggedIn" @click="logout">ログアウト</button>
            <NuxtLink v-else to="/login">ログイン</NuxtLink>
            <NuxtLink to="/profile">マイページ</NuxtLink>
            <NuxtLink to="/products/sell">出品</NuxtLink>
        </div>
    </header>
</template>
