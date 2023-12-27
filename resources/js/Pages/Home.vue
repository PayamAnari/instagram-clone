<script setup>
import { ref, onMounted, toRefs } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";

import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Navigation } from 'vue3-carousel';

import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';

let width = ref(window.innerWidth);
let currentSlide = ref(0);
let currentPost = ref(null);
let openOverlay = ref(false);


const props = defineProps({ posts: Object, allUsers: Object });
const { Posts, allUsers } = toRefs(props);

onMounted(() => {
  window.addEventListener("resize", () => {
     width.value = window.innerwidth
  })
})

</script>

<template>
  <Head title="HOME" />
   <MainLayout>
     
   </MainLayout>
</template>
