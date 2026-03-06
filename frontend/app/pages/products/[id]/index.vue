<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router';

import ProductImage from '~/components/product/ProductImage.vue'
import ProductSummary from '~/components/product/ProductSummary.vue'
import ProductDescription from '~/components/product/ProductDescription.vue'
import ProductInfo from '~/components/product/ProductInfo.vue'
import ProductComments from '~/components/product/ProductComments.vue';

const route = useRoute()
const router = useRouter()

const product = ref(null)

const fetchProduct = async () => {
    try {
        product.value = await $fetch(
            `http://localhost:8000/api/products/${route.params.id}`
        )
    } catch (e) {
        console.error(e)
        product.value = null
    }
}

onMounted(fetchProduct)

const toggleLike = async () => {
    try {
        const token = process.client ? localStorage.getItem('token') : null

        if (!token) {
            return router.push({
                path: '/login',
                query: { redirect: route.fullPath }
            })
        }

        const res = await $fetch(
            `http://localhost:8000/api/products/${product.value.id}/like`,
            {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        )

        product.value.likes = res.likes_count
        product.value.liked_by_me = res.liked

    } catch (e) {
        console.error(e)
    }
}

const addComment = (comment) => {
    product.value.comments.push(comment)
    product.value.commentsCount++
}
</script>

<template>
<div v-if="product" class="product-detail">

    <div class="top">
        <ProductImage :image="product.image || ''" />

        <div class="right">
            <ProductSummary :product="product" @like="toggleLike" />
            <ProductDescription :description="product.description" />
            <ProductInfo
                :categories="product.categories || []"
                :condition="product.condition || ''"
            />
            <ProductComments
                :comments="product.comments"
                @new-comment="addComment"
            />
        </div>
    </div>
</div>

<div v-else>
    商品が見つかりません
</div>
</template>

<style src="@/assets/css/product/detail.css"></style>
