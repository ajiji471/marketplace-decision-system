<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { cn } from '@/lib/utils';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <Card class="w-full max-w-md">
            <CardHeader class="space-y-1">
                <CardTitle class="text-2xl font-bold text-center">Register</CardTitle>
                <CardDescription class="text-center">
                    Buat akun baru untuk mulai menggunakan aplikasi
                </CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            placeholder="John Doe"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            :class="cn(form.errors.name && 'border-destructive focus-visible:ring-destructive')"
                        />
                        <p v-if="form.errors.name" class="text-sm font-medium text-destructive">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            placeholder="nama@email.com"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            :class="cn(form.errors.email && 'border-destructive focus-visible:ring-destructive')"
                        />
                        <p v-if="form.errors.email" class="text-sm font-medium text-destructive">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            placeholder="••••••••"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            :class="cn(form.errors.password && 'border-destructive focus-visible:ring-destructive')"
                        />
                        <p v-if="form.errors.password" class="text-sm font-medium text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            placeholder="••••••••"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            :class="cn(form.errors.password_confirmation && 'border-destructive focus-visible:ring-destructive')"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-sm font-medium text-destructive">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Registering...' : 'Register' }}
                    </Button>

                    <div class="flex items-center justify-center text-sm">
                        <span class="text-muted-foreground">Already registered?</span>
                        <Link
                            :href="route('login')"
                            class="ml-1 text-muted-foreground underline underline-offset-4 hover:text-primary transition-colors"
                        >
                            Log in
                        </Link>
                    </div>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>
</template>