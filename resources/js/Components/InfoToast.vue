<script setup>
    defineProps({
        mensaje: {
            type: String,
            default: 'Cargando...',
            required: false,
        },
        hidden: {
            type: Boolean,
            default: false,
        }
    })

</script>

<template>
    
   <div v-show="hidden" class="fixed flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow-xl top-5 right-5">
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-icon lucide-loader"><path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/></svg>
        <span class="sr-only">Load</span>
    </div>
    <div class="ms-3 text-sm font-normal">{{ mensaje }}</div>
</div>
    
</template>