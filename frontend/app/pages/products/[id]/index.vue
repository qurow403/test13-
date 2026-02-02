<script setup>
import { useRoute } from 'vue-router';
import { mockProducts } from '~/mock/products'

import ProductImage from '~/components/product/ProductImage.vue'
import ProductSummary from '~/components/product/ProductSummary.vue'
import ProductDescription from '~/components/product/ProductDescription.vue'
import ProductInfo from '~/components/product/ProductInfo.vue'
import ProductComments from '~/components/product/ProductComments.vue';

const route = useRoute()
const productId = route.params.id

const product = mockProducts.find(p => p.id == productId)
</script>

<template>
<div v-if="product" class="product-detail">
    <div class="top">
        <ProductImage :image="product.image" />
        <ProductSummary :product="product" />
    </div>

    <ProductDescription :description="product.description" />

    <ProductInfo
        :categories="product.categories || []"
        :condition="product.condition || ''"
    />

    <ProductComments :comments="product.comments" />
</div>

<div v-else>
    商品が見つかりません
</div>
</template>
