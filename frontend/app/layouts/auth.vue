<script>
import Logo from '@/components/Logo.vue'
</script>

<template>
    <div class="auth-layout">
        <header class="auth-header">
            <Logo />
        </header>

        <main class="main">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.auth-header {
    background-color: #000;
    padding: 12px 24px;
}

.main {
    padding: 40px 24px;
}
</style>
