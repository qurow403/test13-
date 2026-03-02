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
            </NuxtLink>
        </div>

        <div class="header-center">
            <input v-model="keyword" type="text" placeholder="なにをお探しですか？" class="search" />
        </div>

        <div class="header-right">
            <button v-if="isLoggedIn" @click="logout">ログアウト</button>
            <NuxtLink v-else to="/login">ログイン</NuxtLink>
            <NuxtLink to="/profile">マイページ</NuxtLink>
            <NuxtLink to="/products/sell" class="sell-button">出品</NuxtLink>
        </div>
    </header>
</template>

<style scoped>
.header {
    background-color: #000;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 24px;
    width: 100%;
}

.header-left {
    display: flex;
    align-items: center;
}

.logo-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #fff;
}

.logo-image {
    height: 32px;
    margin-right: 8px;
}

.header-center {
    flex: 1;
    display: flex;
    justify-content: center;
}

.search {
    width: 70%;
    max-width: 500px;
    padding: 10px 14px;
    border-radius: 4px;
    border: none;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-right a,
.header-right button {
    color: #fff;
    background: none;
    border: none;
    text-decoration: none;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
}

.header-right a:hover,
.header-right button:hover {
    opacity: 0.7;
}

.header-right
.sell-button {
    background-color: #fff;
    color: #000;
    padding: 6px 21px;
    border-radius: 4px;
    font-weight: 500;
}

.header-right
.sell-button:hover {
    opacity: 0.85;
}
</style>
