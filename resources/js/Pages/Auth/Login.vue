<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Checkbox } from '@/Components/ui/checkbox';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { cn } from '@/lib/utils';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

            <Card class="w-full max-w-md">
                <CardHeader class="space-y-1">
                    <CardTitle class="text-2xl font-bold text-center">Log in</CardTitle>
                    <CardDescription class="text-center">
                        Masukkan email dan password untuk mengakses akun Anda
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-medium text-green-600">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                placeholder="nama@email.com"
                                v-model="form.email"
                                required
                                autofocus
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
                                autocomplete="current-password"
                                :class="cn(form.errors.password && 'border-destructive focus-visible:ring-destructive')"
                            />
                            <p v-if="form.errors.password" class="text-sm font-medium text-destructive">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="remember"
                                v-model:checked="form.remember"
                            />
                            <Label for="remember" class="text-sm font-normal text-muted-foreground cursor-pointer">
                                Remember me
                            </Label>
                        </div>

                        <Button
                            type="submit"
                            class="w-full"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Logging in...' : 'Log in' }}
                        </Button>

                        <div class="flex items-center justify-between text-sm">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-muted-foreground underline underline-offset-4 hover:text-primary transition-colors"
                            >
                                Forgot your password?
                            </Link>
                            <Link
                                :href="route('register')"
                                class="text-muted-foreground underline underline-offset-4 hover:text-primary transition-colors"
                            >
                                Create account
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
    </GuestLayout>
</template>