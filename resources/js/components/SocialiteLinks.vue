<script setup lang="ts">
const providers = [
    'facebook',
    'twitter',
    'linkedin',
    'google',
    'github',
    'gitlab',
    'bitbucket',
    'slack',
];
</script>

<template>
    <div class="grid grid-cols-2 gap-4">
        <a v-for="provider in providers" :key="provider" :href="route('auth.socialite.redirect', provider)" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 mt-4 capitalize">
            <component :is="`${provider}-icon`" />
            <span>{{ provider }}</span>
        </a>
    </div>
</template>
