<script setup>
import { ref, toRefs } from "vue";
import { usePage } from "@inertiajs/vue3";

import LikesSection from "@/Components/LikesSection.vue";

import Close from 'vue-material-design-icons/Close.vue';
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';
import EmoticonHappyOutline from 'vue-material-design-icons/EmoticonHappyOutline.vue';

let comment = ref("")
let deleteType = ref(null)
let id = ref(null)

const user = usePage().props.auth.user

const props = defineProps(['post'])
const { post } = toRefs(props)

defineEmits(['closeOverlay', 'addComment', 'updateLike', 'deleteSelected'])

</script>

<template>

</template>
