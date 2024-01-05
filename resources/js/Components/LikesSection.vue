<script setup>
import { computed, toRefs, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

import ShowPostOverlay from '@/Components/ShowPostOverlay.vue';

import Heart from 'vue-material-design-icons/Heart.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import CommentOutline from 'vue-material-design-icons/CommentOutline.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import BookmarkOutline from 'vue-material-design-icons/BookmarkOutline.vue';

const props = defineProps(['post']);
const { post } = toRefs(props);

let currentPost = ref(null);
let openOverlay = ref(false);

const emit = defineEmits(['like']);

const user = usePage().props.auth.user;


const isHeartActiveComputed = computed(() => {
    // Check if post and post.likes are defined before accessing properties
    if (post.value && post.value.likes) {
        for (let i = 0; i < post.value.likes.length; i++) {
            const like = post.value.likes[i];
            if (like.user_id === user.id && like.post_id === post.value.id) {
                return true;
            }
        }
    }

    return false;
});
</script>

<template>
    <div class="flex z-20 items-center justify-between">
        <div class="flex items-center">
            <button @click="$emit('like', { post, user })" class="-mt-[14px]">
                <HeartOutline v-if="!isHeartActiveComputed" class="pl-3 cursor-pointer" :size="30" />
                <Heart v-else class="pl-3 cursor-pointer" fillColor="#FF0000" :size="30" />
            </button>
            <button @click="currentPost = post; openOverlay = true" class="-mt-[12px] ml-1" >
            <CommentOutline class="pl-3 pt-[10px]" :size="30" />
          </button>
            <SendOutline class="pl-3 pt-[10px]" :size="30" />
        </div>

        <BookmarkOutline class="pl-3 pt-[10px]" :size="30" />
    </div>
    <ShowPostOverlay
        v-if="openOverlay"
        :post="currentPost"
        @addComment="addComment($event)"
        @updateLike="updateLike($event)"
        @deleteSelected="
            deleteFunc($event);
        "
        @closeOverlay="openOverlay = false"
    />
</template>