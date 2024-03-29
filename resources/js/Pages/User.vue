<script setup>
import { reactive, toRefs, ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import MainLayout from "@/Layouts/MainLayout.vue";
import ShowPostOverlay from "@/Components/ShowPostOverlay.vue";
import ContentOverlay from "@/Components/ContentOverlay.vue";
import FavoritePostsOverlay from "@/Components/FavoritePostsOverlay.vue";

import Cog from "vue-material-design-icons/Cog.vue";
import Grid from "vue-material-design-icons/Grid.vue";
import PlayBoxOutline from "vue-material-design-icons/PlayBoxOutline.vue";
import BookmarkOutline from "vue-material-design-icons/BookmarkOutline.vue";
import AccountBoxOutline from "vue-material-design-icons/AccountBoxOutline.vue";

let data = reactive({ post: null });
const form = reactive({ file: null });


const props = defineProps({ postsByUser: Object, user: Object });
const { postsByUser, user } = toRefs(props);
const favoritePosts = ref(props.user.favoritePosts || []);
const isGridIconBlue = ref(true);
const isBookmarkIconBlue = ref(false);
const showFavoritePostsOverlay = ref(false);


const getRandomNumber = (min, max) => {
    return Math.floor(Math.random() * (max - min + 1) + min);
};

const generateRandomCounts = () => {
    const followersCount = getRandomNumber(100, 500);
    const followingCount = getRandomNumber(200, 600);
    return { followersCount, followingCount };
};

const { followersCount, followingCount } = generateRandomCounts();

const toggleGridIconColor = () => {
    isGridIconBlue.value = !isGridIconBlue.value;
    isBookmarkIconBlue.value = false; 
    showFavoritePostsOverlay.value = false; 
};

const toggleBookmarkIconColor = () => {
    isBookmarkIconBlue.value = !isBookmarkIconBlue.value;
    isGridIconBlue.value = false; 
    showFavoritePostsOverlay.value = true; 
};

function shouldDisplayFollowButton(currentUser, profileUser) {
  const isCurrentUserProfile = profileUser.id === currentUser.id;
  const isAlreadyFollowing = profileUser.isFollowing; 

  return !isCurrentUserProfile && !isAlreadyFollowing;
}

const addComment = (object) => {
    router.post(
        "/comments",
        {
            post_id: object.post.id,
            user_id: object.user.id,
            comment: object.comment,
        },
        {
            onFinish: () => updatedPost(object),
        }
    );
};

const deleteFunc = (object) => {
    let url = "";
    if (object.deleteType === "Post") {
        url = "/posts/" + object.id;
        setTimeout(() => (data.post = null), 100);
    } else {
        url = "/comments/" + object.id;
    }

    router.delete(url, {
        onFinish: () => updatedPost(object),
    });
};

const updateLike = (object) => {
    let deleteLike = false;
    let id = null;

    for (let i = 0; i < object.post.Likes.length; i++) {
        const like = object.post.Likes[i];
        if (
            like.user_id === object.user.id &&
            like.post_id === object.post.id
        ) {
            deleteLike = true;
            id = like.id;
        }
    }

    if (deleteLike) {
        router.delete("/likes/" + id, {
            onFinish: () => updatedPost(object),
        });
    } else {
        router.post(
            "/likes",
            {
                post_id: object.post.id,
            },
            {
                onFinish: () => updatedPost(object),
            }
        );
    }
};

const updatedPost = (object) => {
    for (let i = 0; i < postsByUser.value.data.length; i++) {
        const post = postsByUser.value.data[i];
        if (post.id === object.post.id) {
            data.post = post;
        }
    }
};

const getUploadedImage = (e) => {
    form.file = e.target.files[0];
    router.post(`/users`, form, {
        preserveState: false,
    });
};
</script>

<template>
    <Head title="Instagram" />

    <MainLayout>
        <div class="pt-2 md:pt-6"></div>
        <div class="max-w-[880px] xl:ml-[80px] lg:ml-0 md:pl-20 px-4 w-[100vw]">
            <div class="flex items-center md:justify-between md:ml-[60px]">
                <label for="fileUser">
                    <img
                        class="rounded-full object-fit md:w-[200px] w-[100px] cursor-pointer"
                        :src="user.file"
                    />
                </label>
                <input
                    v-if="user.id === $page.props.auth.user.id"
                    id="fileUser"
                    class="hidden"
                    type="file"
                    @input="getUploadedImage($event)"
                />

                <div class="ml-6 w-full">
                    <div class="flex items-center md:mb-8 mb-5">
                        <div class="md:mr-6 mr-3 rounded-lg text-[22px]">
                            {{ user.name }}
                        </div>
                        <Cog :size="28" class="cursor-pointer" />
                    </div>

                    <div class="text-[16px] mb-5">
                        {{ user.bio }}
                    </div>
                    <div class="mb-5">
                        <Link
                            :href="route('profile.edit', { userId: user.id })"
                        >
                            <button
                                v-if="user.id === $page.props.auth.user.id"
                                class="md:block hidden md:mr-6 p-1 px-4 w-64 rounded-lg text-[17px] font-extrabold bg-gray-100 hover:bg-gray-200 cursor-pointer"
                            >
                                Edit Profile
                            </button>
                        </Link>
                    </div>
                    <Link :href="route('profile.edit', { userId: user.id })">
                        <button
                            v-if="user.id === $page.props.auth.user.id"
                            class="md:hidden mr-6 p-1 px-4 max-w-[260px] w-full rounded-lg text-[17px] font-extrabold bg-gray-100 hover:bg-gray-200 cursor-pointer"
                        >
                            Edit Profile
                        </button>
                    </Link>
                    
                    <div 
                    v-if="shouldDisplayFollowButton($page.props.auth.user, user)"
                    class="flex mb-5">
                      
                            <button
                                class="md:block hidden md:mr-3 p-1 px-4 w-40 rounded-lg text-[17px] font-extrabold text-white bg-blue-500 hover:bg-blue-300 cursor-pointer"
                            >
                              Follow
                            </button>
                            <button
                            class="md:block hidden md:mr-6 p-1 px-4 w-40 rounded-lg text-[17px] font-extrabold text-white bg-gray-400 hover:bg-gray-300 cursor-pointer"
                            >
                              Message
                            </button>
                    </div>
                    <div
                    v-if="shouldDisplayFollowButton($page.props.auth.user, user)"
                    class="flex justify-center"
                    >
                        <button
                            class="md:hidden mr-2 p-1 px-4 max-w-[160px] w-full rounded-lg text-[17px] font-extrabold text-white bg-blue-500 hover:bg-blue-300 cursor-pointer "
                        >
                            Follow
                        </button>
                        <button
                        class="md:hidden mr-4 p-1 px-4 max-w-[160px] w-full rounded-lg text-[17px] font-extrabold text-white bg-gray-400 hover:bg-gray-300 cursor-pointer  "
                        >
                         Message
                        </button>
                      </div>

                    <div class="md:block hidden">
                        <div class="flex items-center text-[18px]">
                            <div class="mr-6">
                                <span class="font-extrabold">{{
                                    postsByUser.data.length
                                }}</span>
                                posts
                            </div>
                            <div class="text-center p-3">
                        <div class="font-extrabold">{{ followersCount }}</div>
                       <div class="text-gray-400 font-semibold -mt-1.5">followers</div>
                     </div>
                           <div class="text-center p-3">
                     <div class="font-extrabold">{{ followingCount }}</div>
                      <div class="text-gray-400 font-semibold -mt-1.5">following</div>
                         </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:hidden">
            <div
                class="w-full flex items-center justify-around border-t border-t-gray-300 mt-8"
            >
                <div class="text-center p-3">
                    <div class="font-extrabold">
                        {{ postsByUser.data.length }}
                    </div>
                    <div class="text-gray-400 font-semibold -mt-1.5">posts</div>
                </div>
                <div class="text-center p-3">
                  <div class="font-extrabold">{{ followersCount }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">
                        followers
                    </div>
                </div>
                <div class="text-center p-3">
                  <div class="font-extrabold">{{ followingCount }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">
                        following
                    </div>
                </div>
            </div>

            <div
                class="w-full flex items-center justify-between border-t border-t-gray-300"
            >
                <div
                    class="p-3 w-1/4 flex justify-center  "
                    :class="{ 'border-t': isGridIconBlue, 'border-t-black': isGridIconBlue, 'border-t-gray-900': !isGridIconBlue }"
                    @click="toggleGridIconColor"
                    >
                    <Grid
                       :size="30"
                       :fillColor="isGridIconBlue ? 'blue' : '#8E8E8E'"
                       
                     />
                </div>
                <div class="p-3 w-1/4 flex justify-center">
                    <PlayBoxOutline
                        :size="28"
                        fillColor="#8E8E8E"
                        class="cursor-pointer"
                    />
                </div>
                <div
                  class="p-3 w-1/4 flex justify-center cursor-pointer"
                  :class="{ 'border-t': isBookmarkIconBlue, 'border-t-black': isBookmarkIconBlue, 'border-t-gray-900': !isBookmarkIconBlue }"
                  @click="toggleBookmarkIconColor"
                   >
                  <BookmarkOutline
                  :size="30"
                  :fillColor="isBookmarkIconBlue ? 'blue' : '#8E8E8E'"
                 />
               </div>

                <div class="p-3 w-1/4 flex justify-center">
                    <AccountBoxOutline
                        :size="28"
                        fillColor="#8E8E8E"
                        class="cursor-pointer"
                    />
                </div>
            </div>
        </div>

        <div
            id="ContentSection"
            class="md:pr-1.5 xl:ml-[180px] lg:pl-0 md:pl-[90px] p-1"
        >
            <div class="md:block mt-10 hidden border-t border-t-gray-300">
                <div
                    class="flex items-center justify-between max-w-[600px] mx-auto font-extrabold text-gray-400 text-[15px]"
                >
                    <div
                        class="p-[17px] w-1/4 flex justify-center items-center border-t"
                        :class="{ 'border-t': !isGridIconBlue, 'border-t-black': isGridIconBlue }"
                        @click="toggleGridIconColor"
                    >
                    <Grid
                       :size="30"
                       :fillColor="isGridIconBlue ? 'blue' : '#8E8E8E'"
                       
                     />
                        <div class="ml-2 -mb-[1px] text-gray-900">POSTS</div>
                    </div>
                    <div
                        class="p-[17px] w-1/4 flex justify-center items-center"
                    >
                        <PlayBoxOutline
                            :size="15"
                            fillColor="#8E8E8E"
                            class="cursor-pointer"
                        />
                        <div class="ml-2 -mb-[1px] text-gray-900">REELS</div>
                    </div>
                    <div
                        class="p-[17px] w-1/4 flex justify-center items-center cursor-pointer"
                        :class="{ 'border-t': isBookmarkIconBlue, 'border-t-black': isBookmarkIconBlue }"
                        @click="toggleBookmarkIconColor"
                        >
                        <BookmarkOutline
                         :size="30"
                         :fillColor="isBookmarkIconBlue ? 'blue' : '#8E8E8E'"
                        />                        
                      <span class="ml-2 -mb-[1px]">SAVED</span>
                    </div>
                    <div
                        class="p-[17px] w-1/4 flex justify-center items-center"
                    >
                        <AccountBoxOutline
                            :size="15"
                            fillColor="#8E8E8E"
                            class="cursor-pointer"
                        />
                        <span class="ml-2 -mb-[1px]">TAGGED</span>
                    </div>
                </div>
            </div>
                 <div v-if="!showFavoritePostsOverlay">
                  <div class="grid md:gap-4 gap-1 grid-cols-3 relative">
                    <div v-for="postByUser in postsByUser.data" :key="postByUser">
                      <ContentOverlay
                          :postByUser="postByUser"
                          @selectedPost="data.post = $event"
                       />
              </div>
            </div>
          </div>

                 <div v-if="showFavoritePostsOverlay">
                    <div class="grid md:gap-4 gap-1 grid-cols-3 relative ">
                        <FavoritePostsOverlay
                           :user="user"
                           :favoritePosts="favoritePosts"                         
                           @selectedPost="post = $event"
                         />
                      </div>
                 </div>
            <div class="pb-20"></div>
        </div>
    </MainLayout>

    <ShowPostOverlay
        v-if="data.post"
        :post="data.post"
        @addComment="addComment($event)"
        @updateLike="updateLike($event)"
        @deleteSelected="deleteFunc($event)"
        @closeOverlay="data.post = null"
    />
</template>
