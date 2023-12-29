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

const textareaInput = (e) => {
  textarea.value.style.height = "auto";
  textarea.value.style.width = `${e.target.scrollWidth}px`;
}

</script>

<template>
<div id="OverlaySection" class="fixed z-50 top-0 left-0 w-full h-screen bg-[#000000] bg-opacity-60 p-3">
  
  <button class="absolute right-3" @click="$event => $emit('closeOverlay')">
     <Close :size="27" fillColor="#FFFFFF" />
  </button>

  <div class="max-w-6xl h-[calc(100%-100px)] mx-auto mt-10 bg-white rounded-xl">
    <div class="w-full md:flex h-full overflow-auto rounded-xl">
       <div class="flex items-center bg-black w-full">
          <img class="rounded-xl min-w-[400px] p-4 mx-auto" 
           src="https://picsum.photos/id/54/800/820">
       </div>

       <div class="md:max-w-[500px] w-full relative">
          <div class="flex items-center justify-between p-3 border-b">
            <div class="flex items-center">
              <img class="rounded-full w-[30px] h-[30px]"
              src="https://picsum.photos/id/54/300/320">
              <div class="ml-4 font-extrabold text-[15px]">NAME HERE</div>
            </div>
          </div>
       </div>
    </div>
  </div>
</div>
</template>
