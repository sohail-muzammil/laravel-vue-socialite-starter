<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    socialAccounts?: object;
    providers?: string[];
}

defineProps<Props>();

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
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Social Accounts information" description="Connect or disconnect your social accounts" />

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Provider</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(account, index) in socialAccounts" :key="index">
                                <td class="px-6 py-4 capitalize">{{ account.provider }}</td>
                                <td>{{ account.email }}</td>
                                <td class="px-6 py-4">
                                    <Link :href="route('auth.socialite.disconnect', { driver: account.provider })" method="DELETE" as="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90 h-9 px-4 py-2">
                                        Disconnect
                                    </Link>
                                </td>
                            </tr>
                            <tr v-for="(provider, index) in providers" :key="index">
                                <td colspan="2" class="px-6 py-4 capitalize">{{ provider }}</td>
                                <td class="px-6 py-4">
                                    <a :href="route('auth.socialite.redirect', provider)" :tabindex="6" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
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
