<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { Copy, Check } from 'lucide-vue-next';

const props = defineProps<{
    scriptUrl: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Instructions',
        href: '/instructions',
    },
];

const copied = ref(false);

const copyToClipboard = async (text: string) => {
    try {
        await navigator.clipboard.writeText(text);
        copied.value = true;
        setTimeout(() => copied.value = false, 2000);
    } catch (err) {
        console.error('Failed to copy!', err);
    }
};

const scriptCode = `<script src="${props.scriptUrl}"><\/script>`;
</script>

<template>

    <Head title="Instructions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">

            <div
                class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:bg-sidebar-accent/10 dark:border-sidebar-border">
                <h2 class="text-xl font-semibold mb-4">1. Embed the Script</h2>
                <p class="text-sm text-muted-foreground mb-4">
                    Copy and paste this script tag into the <code
                        class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded">&lt;head&gt;</code> or just before the
                    closing <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded">&lt;/body&gt;</code> tag of
                    your website.
                </p>

                <div class="relative group">
                    <pre
                        class="overflow-x-auto rounded-lg bg-gray-950 px-4 py-4 text-sm text-gray-50"><code class="language-html">{{ scriptCode }}</code></pre>
                    <button @click="copyToClipboard(scriptCode)"
                        class="absolute right-2 top-2 p-2 rounded-md bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white opacity-0 group-hover:opacity-100 transition-all font-mono text-xs flex items-center gap-1">
                        <Check v-if="copied" class="size-3" />
                        <Copy v-else class="size-3" />
                        {{ copied ? 'Copied!' : 'Copy' }}
                    </button>
                </div>
            </div>

            <div
                class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:bg-sidebar-accent/10 dark:border-sidebar-border">
                <h2 class="text-xl font-semibold mb-4">2. Add Ad Placements</h2>
                <p class="text-sm text-muted-foreground mb-4">
                    Place empty <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded">&lt;div&gt;</code>
                    containers where you want ads to appear.
                    Use the <code class="text-orange-600 dark:text-orange-400">data-placement</code> attribute to
                    specify the placement identifier.
                </p>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium mb-2">Example: Homepage Banner</h3>
                        <div class="bg-gray-950 rounded-lg p-4 text-gray-50 text-sm overflow-x-auto">
                            <code>&lt;div class="sponsor-ad" data-placement="homepage-top"&gt;&lt;/div&gt;</code>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium mb-2">Example: Sidebar Ad</h3>
                        <div class="bg-gray-950 rounded-lg p-4 text-gray-50 text-sm overflow-x-auto">
                            <code>&lt;div class="sponsor-ad" data-placement="sidebar-right"&gt;&lt;/div&gt;</code>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:bg-sidebar-accent/10 dark:border-sidebar-border">
                <h2 class="text-xl font-semibold mb-4">3. Verify Installation</h2>
                <p class="text-sm text-muted-foreground">
                    Once installed, ads will automatically load. You can check the browser console for logs prefixed
                    with <code class="text-blue-500">[SponsorAds]</code> to verify operation.
                </p>
            </div>

        </div>
    </AppLayout>
</template>
