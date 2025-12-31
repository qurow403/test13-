<script setup>
const logout = () => {
    console.log('logout')
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
            <NuxtLink to="/mypage">マイページ</NuxtLink>
            <NuxtLink to="/sell">出品</NuxtLink>
        </div>
    </header>
</template>

