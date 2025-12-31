<script>
import Logo from '@/components/Logo.vue'
</script>

<template>
    <div class="auth-layout">
        <header class="auth-header">
            <Logo />
        </header>

        <main class="auth-main">
            <slot />
        </main>
    </div>
</template>

<style scoped>

</style>
