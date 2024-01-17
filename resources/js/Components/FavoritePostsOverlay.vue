<script setup>
import { ref, defineProps, defineEmits, toRefs } from 'vue';
import Heart from 'vue-material-design-icons/Heart.vue';
import Comment from 'vue-material-design-icons/Comment.vue';

defineEmits(['selectedPost'])
const props = defineProps(['postsByUser', 'favoritePosts']);
const { postsByUser } = toRefs(props);

const hoverStates = ref({});

console.log(postsByUser);

const getLikesCount = (post) => {
  return post ? post.likes.length : 0;
};

const getCommentsCount = (post) => {
  return post ? post.comments.length : 0;
};

const getFavoritePostImage = (post) => {
  return post ? post.file : '';
};


</script>

<template>
  <div
    v-for="post in favoritePosts"
    :key="post.post_id"
    class="flex items-center justify-center cursor-pointer relative"
    @mouseenter="hoverStates[post.post_id] = true"
    @mouseleave="hoverStates[post.post_id] = false"
    @click="$emit('selectedPost', post.post_id)"
  >
    <div
      v-if="hoverStates[post.post_id]"
      :class="hoverStates[post.post_id] ? 'bg-black bg-opacity-40' : ''"
      class="absolute w-full h-full z-50 flex items-center justify-around text-lg font-extrabold text-white"
    >
      <div class="flex items-center justify-around w-[50%]">
        <div v-if="getLikesCount(post)" class="flex items-center justify-center">
          <Heart fillColor="#FFFFFF" :size="30"/>
          <div class="pl-1">{{ getLikesCount(post) }}</div>
        </div>
        <div v-if="getCommentsCount(post)" class="flex items-center justify-center">
          <Comment fillColor="#FFFFFF" :size="30"/>
          <div class="pl-1">{{ getCommentsCount(post) }}</div>
        </div>
      </div>
    </div>
    <img 
      v-if="post"
      class="aspect-square mx-auto z-0 object-cover cursor-pointer"
      :src="getFavoritePostImage(post)" alt="Favorite Post"
    />
  </div>
</template>