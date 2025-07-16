<template>
    <div class="btn-group btn-group-sm rounded-0">
        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Unordered List"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleBulletList().run()"
            :class="editor.isActive('bulletList') ? 'btn-primary' : 'btn-outline-primary'">
            <i class="fa-solid fa-list-ul"></i>
        </button>
        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Ordered List"
            type="button"
            class="btn btn-sm"
            @click="editor.chain().focus().toggleOrderedList().run()"
            :class="editor.isActive('orderedList') ? 'btn-primary' : 'btn-outline-primary'">
            <i class="fa-solid fa-list-ol"></i>
        </button>
    </div>
</template>
<script setup lang="ts">
import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{
    editor: Editor
}>();




</script>
