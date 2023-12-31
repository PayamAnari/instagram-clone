<script setup>
import { toRefs } from "vue";

defineEmits(["close", "deleteSelected"]);
const props = defineProps({ deleteType: String, id:Number});

const { deleteType, id } = toRefs(props);

</script>

<template>

</template>
