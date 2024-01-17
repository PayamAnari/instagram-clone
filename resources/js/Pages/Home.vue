<script setup>
import { ref, onMounted, toRefs } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3';

import MainLayout from '@/Layouts/MainLayout.vue';

import LikesSection from '@/Components/LikesSection.vue'
import ShowPostOverlay from '@/Components/ShowPostOverlay.vue'
import ShowPostOptionsOverlay from '@/Components/ShowPostOptionsOverlay.vue'


import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Navigation } from 'vue3-carousel'

import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';

let wWidth = ref(window.innerWidth)
let currentSlide = ref(0)
let currentPost = ref(null)
let openOverlay = ref(false)

let deleteType = ref(null)
let id = ref(null)

const user = usePage().props.auth.user

const props = defineProps({ posts: Object, allUsers: Object })
const { posts, allUsers } = toRefs(props)

defineEmits(['closeOverlay','deleteSelected'])

onMounted(() => {
    window.addEventListener('resize', () => {
        wWidth.value = window.innerWidth
    })
})


const updateFavorite = (object) => {

    const postId = object.post && 'id' in object.post ? object.post.id : null;
    if (!postId) {
        return;
    }

    const favorite = object.user && object.user.favoritePosts && object.user.favoritePosts.find(f => f.post_id === postId);

    if (favorite) {
         router.delete(`/favorite-posts/${favorite.post_id}`, {
            onFinish: () => {
              console.log('Deleted')
              if (object.user && object.user.favoritePosts) {
                 object.user.favoritePosts = 
                 object.user.favoritePosts.filter(f => f.post_id !== postId);
                 updatedPost(object);
                }
            },
        });
    } else {
        router.post('/favorite-posts', {
            post_id: postId,
            user_id: object.user.id,
        }, {
            onFinish: () => {
              console.log('Added')
                if (object.user && object.user.favoritePosts) {
                object.user.favoritePosts.push({ post_id: postId });
                updatedPost(object);
                 }
            },
        });
       }
 };

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


    const onFinishCallback = () => {
        updatedPost(object);

        if (object.deleteType === 'Post') {
            openOverlay.value = false;
        }
    };


    router.delete(url, {
        onFinish: onFinishCallback,
    });
};


const updateLike = (object) => {
    let deleteLike = false;
    let id = null;

    
    if (!object.post.likes) {
        object.post.likes = [];
    }
    for (let i = 0; i < object.post.Likes.length; i++) {
        const like = object.post.Likes[i];
        if (like.user_id === object.user.id && like.post_id === object.post.id) {
            deleteLike = true;
            id = like.id;
        }
    }
    if (deleteLike) {
        router.delete('/likes/' + id, {
            onFinish: () => {
                updatedPost(object);
            },
        });
    } else {
        router.post('/likes', {
            post_id: object.post.id,
        }, {
            onFinish: () => {
                updatedPost(object);
            },
        });
    }
}


const updatedPost = (object) => {
    for (let i = 0; i < posts.value.data.length; i++) {
        const post = posts.value.data[i];
        if (post.id === object.post.id) {
            currentPost.value = post
        }
    }
}
console.log(posts)

</script>

<template>
    <Head title="Instagram" />

    <MainLayout>
        <div class="mx-auto xl:pl-[180px] lg:pl-0 md:pl-[80px]">
            <Carousel
                v-model="currentSlide"
                class="max-w-[700px] pl-[50px] xl:pl-[45px] mx-auto"
                :items-to-show="wWidth >= 768 ? 8 : 6"
                :items-to-scroll="4"
                :wrap-around="true"
                :transition="500"
                snapAlign="start"
            >
                <Slide v-for="slide in allUsers" :key="slide">
                    <Link :href="route('users.show', { id: slide.id })" class="relative mx-auto text-center mt-4 px-2 cursor-pointer">
                        <div class="absolute z-[-1] -top-[5px] left-[4px] rounded-full rotate-45 w-[64px] h-[64px] contrast-[1.3]  bg-gradient-to-t from-yellow-300 to-purple-500 via-red-500">
                            <div class="rounded-full ml-[3px] mt-[3px] w-[58px] h-[58px] bg-white" />
                        </div>
                        <img class="rounded-full w-[56px] h-[56px] -mt-[1px]" :src="slide.file">
                        <div class="text-xs mt-2 w-[60px] truncate text-ellipsis overflow-hidden">{{ slide.name }}</div>
                    </Link>
                </Slide>

                <template #addons>
                    <Navigation />
                </template>
            </Carousel>

            <div id="Posts" class="px-4 max-w-[600px] xl:ml-[60px] mx-auto mt-10" v-for="post in posts.data" :key="post">
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center">
                        <Link :href="route('users.show', { id: post.user.id })" class="flex items-center">
                            <img class="rounded-full w-[38px] h-[38px]" :src="post.user.file">
                            <div class="ml-4 font-extrabold text-[15px]">{{ post.user.name }}</div>
                        </Link>
                        <div class="flex items-center text-[15px] text-gray-500">
                            <span class="-mt-5 ml-2 mr-[5px] text-[35px]">.</span>
                            <div>{{ post.created_at }}</div>                        
                        </div>                                         
                    </div>                   
                    <button
                       v-if="user.id === post.user.id"
                       @click=" deleteType = 'Post'; id = post.id"
                     >
                       <DotsHorizontal class="cursor-pointer" :size="27" />
                    </button>
                </div>
                <div class="flex text-[15px] text-gray-600 ml-[53px] -mt-4 mb-1">
                  <div v-if="post.location">{{ post.location }}</div>
                </div>
                
                <div class="bg-black rounded-lg w-full min-h-[400px] flex items-center">
                    <img class="mx-auto w-full" :src="post.file" />
                </div>

                <LikesSection
                    :post="post"
                    :user="user"
                    :addComment="addComment"
                    :deleteFunc="deleteFunc"
                    @like="updateLike($event)"
                    @favorite="updateFavorite($event)"
                    @deleteSelected="deleteFunc($event)"
                    @selectedPost="currentPost = $event.post; openOverlay = true"
                    @updatePost="updatedPost($event)"
                  />


                <div class="text-black font-extrabold py-1">
                  {{ post.Likes?.length }}
                  {{ post.Likes?.length === 1 ? ' like' : ' likes' }}
               </div>
                <div>
                    <span class="text-black font-extrabold">{{ post.user.name }}</span>
                    {{ post.text }}
                </div>
                <button
                    @click="currentPost = post; openOverlay = true"
                    class="text-gray-500 font-extrabold py-1"
                >
                    View all {{ post.comments.length }} comments
                </button>
                <div class="text-gray-500 py-1 text-sm">{{ post.created_at }}</div>
            </div>
            
            <div class="pb-20"></div>
        </div>
        
    </MainLayout>

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

    <ShowPostOptionsOverlay
        v-if="deleteType"
        :deleteType="deleteType"
        :id="id"
        @deleteSelected="
            deleteFunc($event);
            deleteType = null;
            id = null;
        "
        @close="deleteType = null; id = null"
    />


</template>

<style>
    .carousel__prev,
    .carousel__next,
    .carousel__prev:hover,
    .carousel__next:hover {
        color: rgb(49, 49, 49);
        background-color: rgb(255, 255, 255);
        border-radius: 100%;
        margin: 0 20px;
    }
</style>
