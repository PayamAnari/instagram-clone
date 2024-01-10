<script setup>
import { computed, toRefs, ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

import ShowPostOverlay from '@/Components/ShowPostOverlay.vue';

import Heart from 'vue-material-design-icons/Heart.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import CommentOutline from 'vue-material-design-icons/CommentOutline.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import BookmarkOutline from 'vue-material-design-icons/BookmarkOutline.vue';


const props = defineProps(['post', 'class', 'Likes', 'user', 'comment', 'user' ]);
const { post } = toRefs(props);

const bookmarks = ref(post.value.bookmark || []);
let openOverlay = ref(false);

const emit = defineEmits(['like', 'closeOverlay', 'selectedPost', 'addComment' ,
  'updatedPost' ]);

const user = usePage().props.auth.user;

const isHeartActiveComputed = computed(() => {
    let isTrue = false;

    if (!post.value || !post.value.Likes) {
        return false;
    }

    for (let i = 0; i < post.value.Likes.length; i++) {
        const like = post.value.Likes[i];
        if (like.user_id === user.id && like.post_id === post.value.id) {
            isTrue = true;
        }
    }

    return isTrue;
});



</script>

<template>
    <div class="flex z-20 items-center justify-between">
        <div class="flex items-center">
            <button @click="$emit('like', { post, user })" class="-mt-[14px]">
                <HeartOutline v-if="!isHeartActiveComputed" class="pl-3 cursor-pointer" :size="30" />
                <Heart v-else class="pl-3 cursor-pointer" fillColor="#FF0000" :size="30" />
            </button>

                <CommentOutline @click="openOverlay = true" class="pl-3 pt-[10px] cursor-pointer" :size="30" />
            <SendOutline class="pl-3 pt-[10px]" :size="30" />
        </div>
             <BookmarkOutline  class="pr-3 cursor-pointer" :size="30" />
      </div>


    <ShowPostOverlay
    v-if="openOverlay"
    :post="post"
    @closeOverlay="openOverlay = false"
  />

</template>
