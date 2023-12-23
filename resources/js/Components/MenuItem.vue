<script setup>
import { toRefs } from 'vue';
import { usePage } from '@inertiajs/vue3';

import HomeOutline from 'vue-material-design-icons/HomeOutline.vue';
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Compass from 'vue-material-design-icons/Compass.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import Plus from 'vue-material-design-icons/Plus.vue';
import AccountOutline from 'vue-material-design-icons/AccountOutline.vue';
import Menu from 'vue-material-design-icons/Menu.vue';

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
