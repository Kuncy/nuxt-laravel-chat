<script setup lang="ts">
const { data: contacts, refresh: fetchContacts } = useHttp<any[]>("contacts", {
    method: "GET",
    immediate: true,
    watch: false,
    onFetchResponse({ response }) {
        console.log(response);
    },
    onFetchError({ error }) {
        console.log(error.message);
    }
});

onMounted(() => {
    fetchContacts();
})
</script>

<template>
    <UCard>
        <div class="font-bold text-lg leading-tight tracking-tighter mb-4">Contacts</div>
        {{contacts}}
    </UCard>
</template>

