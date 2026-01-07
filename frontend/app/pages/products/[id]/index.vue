<script setup>
import { useRoute } from 'vue-router';

import ProductImage from '~/components/product/ProductImage.vue'
import ProductSummary from '~/components/product/ProductSummary.vue'
import ProductDescription from '~/components/product/ProductDescription.vue'
import ProductInfo from '~/components/product/ProductInfo.vue'
import ProductComments from '~/components/product/ProductComments.vue';

const route = useRoute()
const productId = route.params.id

const product = {
    id: productId,
    name: '腕時計',
    brand: 'ブランド名',
    price: 15000,
    likes: 12,
    commentsCount: 3,
    image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
    description: 'スタイリッシュなデザインのメンズ腕時計',
    categories: ['メンズ', '小物'],
    condition: '良好',
    comments: [
        {
            id: 1,
            user: '山田太郎',
            body: 'とても綺麗な商品ですね！',
        },
    ],
}
</script>

<template>
<div class="product-detail">
    <div class="top">
        <ProductImage :image="product.image" />
        <ProductSummary :product="product" />
    </div>

    <ProductDescription :description="product.description" />
    <ProductInfo :categories="product.categories"  :condition="product.condition" />

    <ProductComments :comments="product.comments" />
</div>
</template>
