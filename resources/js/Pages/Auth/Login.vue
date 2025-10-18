<script setup lang="ts">
import DefaultLayout from "@/Pages/Layouts/DefaultLayout.vue";
import {Form} from "@inertiajs/vue3";
import {onMounted, useTemplateRef} from "vue";

const input = useTemplateRef("input");
onMounted(() => {
    input.value?.focus();
})
</script>

<template>
    <DefaultLayout title="Login">
        <div class="h-screen grid place-items-center">
            <div class="card bg-base-100 w-96 shadow-sm">
                <Form
                    class="card-body" :action="route('login')" method="post" #default="{ errors, processing, clearErrors }">
                    <h2 class="card-titl">Login</h2>
                    <input ref="input" type="password" name="password" placeholder="Password" class="input" required />
                    <span class="text-red-400" v-if="errors.password">{{ errors.password }}</span>
                    <div class="card-actions justify-end">
                        <button @click="clearErrors()" type="submit" :disabled="processing" class="btn btn-primary">
                            {{ processing ? 'Logging in...' : 'Login' }}
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </DefaultLayout>
</template>
