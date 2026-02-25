export default defineNuxtRouteMiddleware((to, from) =>
{
    if (ProcessingInstruction.server) return

    const token = localStorage.getItem('token')

    if (!token) {
        return navigateTo('/login')
    }
})