<script setup>
import { ref } from 'vue'
import ProductTabs from '@/components/ProductTabs.vue'
import ProductCard from '@/components/ProductCard.vue'

const currentTab = ref('おすすめ')

const products = [
    {
        id: 1,
        name: '腕時計',
        price: 15000,
        discription: 'スタイリッシュなデザインのメンズ腕時計',
        image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
    },
    {
        id: 2,
        name: 'HDD',
        price: 5000,
        discription: '高速で信頼性の高いハードディスク',
        image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
    },
    {
        id: 3,
        name: '玉ねぎ3束',
        price: 300,
        discription: '新鮮な玉ねぎ3束のセット',
        image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
    },
    {
        id: 4,
        name: '革靴',
        price: 4000,
        discription: 'クラシックなデザインの革靴',
        image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
    },
    {
        id: 5,
        name: 'ノートPC',
        price: 45000,
        discription: '高性能なノートパソコン',
        image: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
    },
]
</script>

<template>
<div>
    <ProductTabs :current="currentTab" @change="currentTab = $event" />

    <div class="grid">
        <ProductCard v-for="product in products" :key="product.id" :product="product" />
    </div>
</div>
</template>
