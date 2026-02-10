<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { index, edit, update } from '@/routes/banners';
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Banner } from '@/types';

const props = defineProps<{
    banner: Banner;
}>();

const form = useForm({
    title: props.banner.title,
    placement: props.banner.placement || '',
    target_url: props.banner.target_url,
    link_text: props.banner.link_text || '',
    status: props.banner.status,
    image: null as File | null,
    _method: 'PUT',
});

const submit = () => {
    form.post(update.url(props.banner.id), {
        forceFormData: true,
    });
};

const handleImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
    }
};
</script>

<template>

    <Head title="Edit Banner" />

    <AppLayout :breadcrumbs="[
        { title: 'Banners', href: index.url() },
        { title: 'Edit', href: banner ? edit.url(banner.id) : '#' },
    ]">
        <div class="flex h-full flex-col p-4 md:p-6 space-y-6 max-w-2xl mx-auto w-full">
            <div class="flex items-center space-x-4">
                <Button variant="ghost" size="icon" :as="Link" :href="index.url()">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <Heading title="Edit Banner" :description="`Update banner: ${banner.title}`" />
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Banner Details</CardTitle>
                    <CardDescription>
                        Update the information below for this banner.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <Label for="title">Title <span class="text-destructive">*</span></Label>
                            <Input id="title" v-model="form.title" placeholder="e.g. Summer Sale 2024" required />
                            <InputError :message="form.errors.title" />
                        </div>

                        <!-- Placement -->
                        <div class="space-y-2">
                            <Label for="placement">Placement</Label>
                            <Input id="placement" v-model="form.placement" placeholder="e.g. homepage-top, sidebar" />
                            <p class="text-[0.8rem] text-muted-foreground">
                                Use this key in your HTML: data-placement="homepage-top"
                            </p>
                            <InputError :message="form.errors.placement" />
                        </div>

                        <!-- Target URL -->
                        <div class="space-y-2">
                            <Label for="target_url">Target URL <span class="text-destructive">*</span></Label>
                            <Input id="target_url" v-model="form.target_url" type="url"
                                placeholder="https://example.com/promo" required />
                            <InputError :message="form.errors.target_url" />
                        </div>

                        <!-- Link Text -->
                        <div class="space-y-2">
                            <Label for="link_text">Link Text</Label>
                            <Input id="link_text" v-model="form.link_text" placeholder="e.g. Click Here" />
                            <InputError :message="form.errors.link_text" />
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <Label for="status">Status <span class="text-destructive">*</span></Label>
                            <Select v-model="form.status">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="active">Active</SelectItem>
                                    <SelectItem value="inactive">Inactive</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.status" />
                        </div>

                        <!-- Image -->
                        <div class="space-y-2">
                            <Label for="image">Banner Image</Label>
                            <div v-if="banner.image_url" class="mb-2">
                                <img :src="banner.image_url" alt="Current Banner"
                                    class="h-24 w-auto rounded-md border" />
                            </div>
                            <Input id="image" type="file" accept="image/*" @change="handleImageChange" />
                            <p class="text-[0.8rem] text-muted-foreground">
                                Leave empty to keep current image. Max 5MB.
                            </p>
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100"
                                class="w-full h-2">
                                {{ form.progress.percentage }}%
                            </progress>
                            <InputError :message="form.errors.image" />
                        </div>

                        <div class="flex justify-end pt-4">
                            <Button type="submit" :disabled="form.processing">
                                Update Banner
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
