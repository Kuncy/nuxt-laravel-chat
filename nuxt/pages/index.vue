<script setup lang="ts">
import Contacts from "~/components/chat/Contacts.vue";
import Chat from "~/components/chat/Chat.vue";

useSeoMeta({
  title: 'Home',
})
</script>

<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-3">
      <Contacts/>
    </div>
    <div class="col-span-12 lg:col-span-9">
        <Chat/>
    </div>
  </div>
</template>
