<script setup>
import { ref, toRefs } from "vue";

import Heart from "vue-material-design-icons/Heart.vue";
import Comment from "vue-material-design-icons/Comment.vue";

defineEmits(['selectedPost']);
const props = defineProps(['postByUser']);
const { postByUser } = toRefs(props);

let isHover = ref(false);


</script>

<template>
 
</template>
