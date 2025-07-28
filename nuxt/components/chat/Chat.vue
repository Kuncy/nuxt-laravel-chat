<script setup lang="ts">
import { ref } from 'vue'

const messages = ref([
    { id: 1, text: 'Hello!' },
    { id: 2, text: 'Welcome to the chat!' },
])

const newMessage = ref('')

const sendMessage = () => {
    if (!newMessage.value.trim()) return
    messages.value.push({ id: Date.now(), text: newMessage.value })
    newMessage.value = ''
}
</script>

<template>
    <UCard class="h-[90vh] flex flex-col">
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
            Chat
        </div>

        <div class="flex-1 overflow-y-auto space-y-2 mb-4 px-1">
            <div
                v-for="msg in messages"
                :key="msg.id"
                class="bg-gray-100 dark:bg-gray-800 p-2 rounded"
            >
                {{ msg.text }}
            </div>
        </div>

        <div class="flex items-center gap-2 border-t pt-2">
            <input
                v-model="newMessage"
                type="text"
                placeholder="Type a message"
                class="flex-1 px-3 py-2 rounded border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary"
                @keyup.enter="sendMessage"
            />
            <button
                class="px-4 py-2 bg-primary text-white rounded"
                @click="sendMessage"
            >
                Send
            </button>
        </div>
    </UCard>
</template>
