<script setup>
import { computed, toRefs, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import ShowPostOverlay from '@/Components/ShowPostOverlay.vue';

import Heart from 'vue-material-design-icons/Heart.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import CommentOutline from 'vue-material-design-icons/CommentOutline.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import BookmarkOutline from 'vue-material-design-icons/BookmarkOutline.vue';


const props = defineProps(['post', 'class', 'Likes', 'user', 'addComment', 'deleteFunc', 'updatePost']);
const { post } = toRefs(props);


let openOverlay = ref(false);
let isBookmarked = ref(false);


const emit = defineEmits(['like', 'closeOverlay', 'favorite', 'removeFavorite', 'selectedPost' ]);

const user = usePage().props.auth.user;

const addComment = (object) => {
    router.post('/comments', {
        post_id: object.post.id,
        user_id: object.user.id,
        comment: object.comment
    }, {
        onFinish: () => updatedPost(object),
    }
    )
}
const deleteFunc = (object) => {

let url = '';
if (object.deleteType === 'Post') {
    url = '/posts/' + object.id;
} else {
    url = '/comments/' + object.id;
}


router.delete(url, {
    onFinish: onFinishCallback,
});
};

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


const toggleFavorite = () => {
  
        if (!isBookmarked.value) {
            emit('favorite', { post: post.value, user });
        } else {
            emit('removeFavorite', { post: post.value, user });
        }
        isBookmarked.value = !isBookmarked.value;
   
};
const isBookmarkedComputed = computed(() => isBookmarked.value);


const handleClick = () => {
    emit('selectedPost', {
        post: post.value,
        addComment,
        deleteFunc,
    });
    openOverlay.value = true;
};



</script>

<template>
    <div class="flex z-20 items-center justify-between">
        <div class="flex items-center">
            <button @click="$emit('like', { post, user })" class="-mt-[14px]">
                <HeartOutline v-if="!isHeartActiveComputed" class="pl-3 cursor-pointer" :size="30" />
                <Heart v-else class="pl-3 cursor-pointer" fillColor="#FF0000" :size="30" />
            </button>

                <CommentOutline @click="handleClick" class="pl-3 pt-[10px] cursor-pointer" :size="30" />
            <SendOutline class="pl-3 pt-[10px]" :size="30" />
        </div>
        <button @click="toggleFavorite" class="-mt-[14px]">
      <BookmarkOutline
        class="pl-3 cursor-pointer"
        :fillColor="isBookmarkedComputed ? '#FF0000' : ''"
        :size="30"
      />
    </button>

      </div>


    <ShowPostOverlay
    v-if="openOverlay"
    :post="post"
    @closeOverlay="openOverlay = false"
    @addComment="addComment($event)"
    @deleteSelected="deleteFunc($event)"
  />
 
</template>
