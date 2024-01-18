<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';


defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});


</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
          <div class="flex justify-between">
            <Link href="/" class="font-semibold text-blue-500 cursor-pointer mt-1">Cancel</Link>
            <h2 class="font-semibold text-2xl text-center text-gray-800 leading-tight">Edit Profile</h2>
            <Link :href="route('users.show', { id: $page.props.auth.user.id })" class="font-semibold text-blue-500 cursor-pointer mt-1">Done</Link>
          </div>
          </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
