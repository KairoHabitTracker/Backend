<script setup lang="ts">
import DefaultLayout from "@/Pages/Layouts/DefaultLayout.vue";

defineProps<{
    title?: string;
}>();
</script>

<template>
    <DefaultLayout :title="title">
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex py-6">
                <label for="my-drawer-3" class="h-full btn btn-circle size-10! swap swap-rotate drawer-button lg:hidden place-self-start -translate-y-1/2">
                    <!-- this hidden checkbox controls the state -->
                    <input type="checkbox" />

                    <!-- hamburger icon -->
                    <svg
                        class="swap-off fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        width="32"
                        height="32"
                        viewBox="0 0 512 512">
                        <path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z" />
                    </svg>

                    <!-- close icon -->
                    <svg
                        class="swap-on fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        width="32"
                        height="32"
                        viewBox="0 0 512 512">
                        <polygon
                            points="400 145.49 366.51 112 256 222.51 145.49 112 112 145.49 222.51 256 112 366.51 145.49 400 256 289.49 366.51 400 400 366.51 289.49 256 400 145.49" />
                    </svg>
                </label>
                <div class="px-10 h-full w-full">
                    <div class="container mx-auto">
                        afweff
                    </div>
                </div>
            </div>
            <div class="drawer-side">
                <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
                <ul class="menu bg-base-200 min-h-full w-80 p-4">
                    <li>
                        <h1 class="text-lg font-bold">
                            Kairo Habit Tracker
                        </h1>
                        <h2 class="text-accent">
                            Admin Dashboard
                        </h2>
                    </li>
                </ul>
            </div>
        </div>
    </DefaultLayout>
</template>
