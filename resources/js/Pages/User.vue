<script setup>
import { reactive, toRefs } from 'vue';
import { Head, Link, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

import Cog from "vue-material-design-icons/Cog.vue";
import Grid from "vue-material-design-icons/Grid.vue";
import PlayBoxOutline from "vue-material-design-icons/PlayBoxOutline.vue";
import BookmarkOutline from "vue-material-design-icons/BookmarkOutline.vue";
import AccountBoxOutline from "vue-material-design-icons/AccountBoxOutline.vue";


let data = reactive({ post: null });
const form = reactive({ file: null });

const props = defineProps({ postsByUser: Object, user: Object });
const { postByUser, user } = toRefs(props);

const getUploadedImage = (e) => {
      form.file = e.target.files[0];
      router.post(`/users`, form, {
        preserveState: false
      })
}
    


</script>

<template>
  <Head title="Instagram" />

  <MainLayout>
       <div class="pt-2 md:pt-6"></div>
       <div class="max-w[800px] lg:ml-0 md:ml-[80px] px-4 w-[100vw]">
          <div class="flex items-center md:justify-between">
             
            <label for="fileUser">
                <img class="rounded-full object-fit md:w-[200px] w-[100px] cursor-pointer" 
                src="https://picsum.photos/id/50/300/320"
                >
            </label>
            <input id="fileUser"
            class="hidden"
            type="file"
            @input="$event => getUploadImage($event)">

            <div class="ml-6 w-full">
               <div class="flex items-center md:mb-8 mb-5">
                 <div class="md:mr-6 mr-3 rounded-lg text-[22px]">NAME HERE</div>
                 <button class="md:block hidden md:mr-6 px-4 rounded-lg text-[16px] font-extrabold bg-gray-100 hover:bg-gray-200">
                    Edit Profile
                 </button>
                 <Cog :size="28" class="cursor-pointer" />
                 <button class="md:hidden mr-6 p-1 px-4 max-w-[260px] w-full rounded-lg text-[17px] font-extrabold bg-gray-100 hover:bg-gray-200">
                  Edit Profile
                 </button>
               </div>
            </div>
          </div>
       </div>
  </MainLayout>
</template>

