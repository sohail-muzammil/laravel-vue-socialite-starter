<script setup lang="ts">
import { computed } from "vue";
import { Head, usePage, Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    socialAccounts?: number[];
}

defineProps<Props>();

const page = usePage();
const providers = computed(() => page.props.oauth_providers);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Social Accounts settings',
        href: '/settings/socialite',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Social Accounts settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Social Accounts information"
                    description="Connect or disconnect your social accounts" />

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                    Provider
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600 capitalize">
                            <tr v-for="(provider, index) in providers" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                    <span v-html="provider.icon" class="mr-2"></span>
                                    {{ provider.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link v-if="socialAccounts && socialAccounts.includes(provider.id)" :href="route('auth.socialite.disconnect', { driver: provider.id })"
                                        method="DELETE" as="button"
                                        :tabindex="6"
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90 h-9 px-4 py-2">
                                        Disconnect
                                    </Link>
                                    <a v-else :href="route('auth.socialite.redirect', provider.name)"
                                        :tabindex="6"
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                                        Connect
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>

