<script setup lang="ts">
const router = useRouter();
const auth = useAuthStore();

const open = ref(false);

useSeoMeta({
  title: 'Home',
})
</script>

<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-3">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">Contacts</div>
      </UCard>
    </div>
    <div class="col-span-12 lg:col-span-9">
      <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">
          chat
        </div>
      </UCard>
    </div>
  </div>
</template>
