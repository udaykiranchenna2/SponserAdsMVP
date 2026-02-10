<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { index, create as createBanner, edit, destroy } from '@/routes/banners';
import { Banner } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Plus, Pencil, Trash2, ExternalLink, Code } from 'lucide-vue-next';

defineProps<{
    banners: {
        data: Banner[];
        links: any;
        meta: any;
    };
}>();

const copyEmbedCode = (code: string) => {
    navigator.clipboard.writeText(code);
    alert('Embed code copied to clipboard!');
};
</script>

<template>

    <Head title="Banners" />

    <AppLayout :breadcrumbs="[{ title: 'Banners', href: index.url() }]">
        <div class="flex h-full flex-col p-4 md:p-6 space-y-6 max-w-7xl mx-auto w-full">
            <div class="flex items-center justify-between">
                <Heading title="Banners" description="Manage your advertising banners." />
                <Button :as="Link" :href="createBanner.url()">
                    <Plus class="mr-2 h-4 w-4" />
                    Create Banner
                </Button>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <th
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[120px]">
                                    Image
                                </th>
                                <th
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[150px]">
                                    Placement
                                </th>
                                <th
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[200px]">
                                    Title
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Target
                                    URL</th>
                                <th
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[100px]">
                                    Status
                                </th>
                                <th
                                    class="h-12 px-4 text-right align-middle font-medium text-muted-foreground override:w-[100px]">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            <tr v-if="banners.data.length === 0">
                                <td colspan="6" class="p-4 text-center text-muted-foreground">
                                    No banners found.
                                </td>
                            </tr>
                            <tr v-for="banner in banners.data" :key="banner.id"
                                class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle">
                                    <div class="relative h-16 w-24 overflow-hidden rounded-md border bg-muted">
                                        <img v-if="banner.image_url" :src="banner.image_url" :alt="banner.title"
                                            class="h-full w-full object-cover" />
                                        <div v-else
                                            class="flex h-full w-full items-center justify-center bg-secondary text-xs text-muted-foreground">
                                            No Img</div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle font-medium">
                                    <Badge variant="outline" class="font-mono text-xs">{{ banner.placement || 'default'
                                        }}</Badge>
                                </td>
                                <td class="p-4 align-middle font-medium">
                                    {{ banner.title }}
                                </td>
                                <td class="p-4 align-middle">
                                    <a :href="banner.target_url" target="_blank"
                                        class="flex items-center text-xs text-muted-foreground hover:underline truncate max-w-[300px]">
                                        {{ banner.target_url }}
                                        <ExternalLink class="ml-1 h-3 w-3 flex-shrink-0" />
                                    </a>
                                </td>
                                <td class="p-4 align-middle">
                                    <Badge :variant="banner.status === 'active' ? 'default' : 'secondary'">
                                        {{ banner.status_label }}
                                    </Badge>
                                </td>
                                <td class="p-4 align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button variant="ghost" size="icon" :as="Link" :href="edit.url(banner.id)">
                                            <Pencil class="h-4 w-4" />
                                            <span class="sr-only">Edit</span>
                                        </Button>
                                        <Link :href="destroy.url(banner.id)" method="delete" as="button"
                                            preserve-scroll>
                                            <Button variant="ghost" size="icon"
                                                class="text-destructive hover:text-destructive hover:bg-destructive/10">
                                                <Trash2 class="h-4 w-4" />
                                                <span class="sr-only">Delete</span>
                                            </Button>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="banners.meta.links.length > 3" class="flex items-center justify-center space-x-1 py-4">
                <template v-for="(link, key) in banners.meta.links" :key="key">
                    <div v-if="link.url === null"
                        class="px-4 py-2 text-sm text-gray-500 border rounded-md cursor-not-allowed"
                        v-html="link.label" />
                    <Link v-else :href="link.url" class="px-4 py-2 text-sm border rounded-md transition-colors"
                        :class="{ 'bg-primary text-primary-foreground': link.active, 'bg-background hover:bg-muted': !link.active }"
                        v-html="link.label" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
