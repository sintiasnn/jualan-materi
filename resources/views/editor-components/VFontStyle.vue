<template>
    <div class="btn-group btn-group-sm rounded-0">
        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Bold"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleBold().run()"
            :disabled="!editor.can().chain().focus().toggleBold().run()"
            :class="editor.isActive('bold') ? 'btn-primary' : 'btn-outline-primary'"
        >
            <i class="fa-solid fa-bold"></i>
        </button>
        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Italic"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleItalic().run()"
            :disabled="!editor.can().chain().focus().toggleItalic().run()"
            :class="editor.isActive('italic') ? 'btn-primary' : 'btn-outline-primary'"
        >
            <i class="fa-solid fa-italic"></i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Underline"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleUnderline().run()"
            :class="editor.isActive('underline') ? 'btn-primary' : 'btn-outline-primary'"
        >
            <i class="fa-solid fa-underline"></i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Striketrough"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleStrike().run()"
            :disabled="!editor.can().chain().focus().toggleStrike().run()"
            :class="editor.isActive('strike') ? 'btn-primary' : 'btn-outline-primary'"
        >
            <i class="fa-solid fa-strikethrough"></i>
        </button>
<!--        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Link"
            type="button"
            class="btn btn-sm"
            @click="setLink()"
            :disabled="!editor.can().chain().focus().toggleStrike().run()"
            :class="editor.isActive('strike') ? 'btn-primary' : 'btn-outline-primary'"
        >

        </button>-->
    </div>
</template>
<script setup lang="ts">

import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{editor: Editor}>();
</script>
