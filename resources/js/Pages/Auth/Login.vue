<script setup lang="ts">
import DefaultLayout from "@/Pages/Layouts/DefaultLayout.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    'password': '',
});

const submit = () => {
    form.post(route('login'))
}
</script>

<template>
    <DefaultLayout title="Login">
        <div class="card bg-base-100 w-96 shadow-sm">
            <form class="card-body" @submit.prevent="submit">
                <h2 class="card-titl">Login!</h2>
                <p>A card component has a figure, a body part, and inside body there are title and actions parts</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-primary">Buy Now</button>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>
