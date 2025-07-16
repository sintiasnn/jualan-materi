<template>
    <div class="btn-group btn-group-sm rounded-0">
        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Undo"
            type="button"
            class="btn btn-sm btn-primary"
            @click="editor.chain().focus().undo().run()"
            :disabled="!editor.can().chain().focus().undo().run()"
        >
            <i class="fa-solid fa-rotate-left"></i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Redo"
            type="button"
            class="btn btn-sm btn-primary"
            @click="editor.chain().focus().redo().run()"
            :disabled="!editor.can().chain().focus().redo().run()"
        >
            <i class="fa-solid fa-rotate-right"></i>
        </button>
    </div>
</template>
<script setup lang="ts">
import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{editor: Editor}>();
</script>
