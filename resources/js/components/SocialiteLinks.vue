<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const providers = computed(() => page.props.oauth_providers);
</script>

<template>
    <div
        class="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
        <span class="relative z-10 bg-background px-2 text-muted-foreground">
            Or continue with
        </span>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <a v-for="provider in providers" :key="provider" :href="route('auth.socialite.redirect', provider.name)"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 capitalize">
            <span v-html="provider.icon"></span>
            <span>{{ provider.name }}</span>
        </a>
    </div>
</template>
