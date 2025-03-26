<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { PageProps } from '@inertiajs/core';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Send } from 'lucide-vue-next';
import { onMounted, onUnmounted, toRef, useTemplateRef } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Input } from '../components/ui/input';
import { ScrollArea } from '../components/ui/scroll-area';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chat',
        href: '/dashboard',
    },
];

type User = {
    id: number;
    name: string;
    email: string;
};

type Message = {
    id: number;
    user: User;
    text: string;
    time: string;
};

type Messages = Message[];

const props = defineProps<{
    messages: Messages;
}>();

const messages = toRef(props.messages);
const scrollArea = useTemplateRef('scrollArea');

const user = usePage<PageProps & { auth: { user: User | undefined } }>().props.auth?.user as User | undefined;

const form = useForm({
    message: '',
});

const webSocketChannel = 'channel-chat-messages';

function handleSendMessage() {
    if (!user) return;
    if (!form.message || form.message.trim() === '') return;

    router.post(
        route('messages.store'),
        {
            message: form.message,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                document.getElementById('message-input')?.focus();
            },
            onError: (errors) => {
                console.error(errors);
            },
        },
    );
}

function handleScrollToBottom() {
    scrollArea.value?.$el.querySelector('[data-reka-scroll-area-viewport]')?.scrollTo({
        top: Math.abs(scrollArea.value.$el.querySelector('[data-reka-scroll-area-viewport]').scrollHeight),
        behavior: 'smooth',
    });
}

onMounted(() => {
    handleScrollToBottom();

    //  console.log('connecting ' + Echo.channel(webSocketChannel).name);
    Echo.private(webSocketChannel).listen('GotMessage', (data: { message: Message }) => {
        messages.value.push(data.message);
        setTimeout(() => {
            handleScrollToBottom();
        }, 200);
    });
});

onUnmounted(() => {
    Echo.leave(webSocketChannel);
});
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 pl-4 pt-1 dark:border-sidebar-border">
                <template v-if="messages?.length > 0">
                    <ScrollArea
                        type="always"
                        class="relative bottom-5 left-0 right-0 top-0 z-10 flex max-h-[72vh] w-full flex-col items-start justify-end space-y-2 pr-4"
                        ref="scrollArea"
                    >
                        <div class="flex w-full items-center justify-between gap-10" v-for="message in messages" :key="message.id">
                            <span class="inline-flex items-center justify-start">
                                <small class="text-muted">
                                    <span class="mr-4 font-semibold text-white">{{ message.user.name }} </span>
                                </small>
                                <span :class="user && message.user.id === user.id ? 'font-bold text-green-500' : 'font-semibold text-blue-400'">
                                    {{ message.text }}
                                </span>
                            </span>
                            <small class="shrink-0 text-gray-200">
                                {{ message.time }}
                            </small>
                        </div>
                    </ScrollArea>
                </template>
                <PlaceholderPattern />
            </div>
            <div class="flex flex-col gap-4">
                <form @submit.prevent="handleSendMessage">
                    <div class="relative">
                        <Input
                            autocomplete="off"
                            id="message-input"
                            v-model="form.message"
                            @disabled="form.processing"
                            autofocus
                            placeholder="Type your message..."
                            class="pr-10"
                        />
                        <Button type="submit" variant="ghost" class="absolute right-0 top-0 z-10 h-full">
                            <Send class="size-4" />
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
