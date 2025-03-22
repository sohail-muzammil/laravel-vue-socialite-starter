<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

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
                <HeadingSmall title="Social Accounts information" description="Connect or disconnect your social accounts" />

                <div class="overflow-x-auto">
                    <Table>
                        <TableCaption>A list of your recent invoices.</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px]"> Provider </TableHead>
                                <TableHead class="text-right"> Action </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(provider, index) in providers" :key="index">
                                <TableCell class="flex items-center gap-2 font-medium capitalize">
                                    <span v-html="provider.icon" class="mr-2"></span>
                                    <span>{{ provider.name }}</span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Link
                                        v-if="socialAccounts && socialAccounts.includes(provider.id)"
                                        :href="route('auth.socialite.disconnect', { driver: provider.id })"
                                        method="DELETE"
                                        as="button"
                                        :tabindex="6"
                                        class="inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-md bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground shadow-sm ring-offset-background transition-colors hover:bg-destructive/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                                    >
                                        Disconnect
                                    </Link>
                                    <a
                                        v-else
                                        :href="route('auth.socialite.redirect', provider.name)"
                                        :tabindex="6"
                                        class="inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                                    >
                                        Connect
                                    </a>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
