<template>
    <div class="dropdown">
        <button class="btn btn-primary btn-sm dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Style
        </button>
        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">

            <a v-for="i in headingLevels"
               :key="i"
               class="dropdown-item"
               @click="editor.chain().focus().toggleHeading({ level: i }).run()"
               :class="{ 'active': editor.isActive('heading', { level: i })}"
            >
                <component :is="'h' + i">Heading {{ i }}</component>
            </a>
            <a
                class="dropdown-item"
                @click="editor.chain().focus().setParagraph().run()"
                :class="{ 'active': editor.isActive('paragraph') }">
                <p class="m-0">Paragraph</p>
            </a>
            <a
                class="dropdown-item"
                @click="editor.chain().focus().toggleCode().run()"
                :class="{ 'active': editor.isActive('code') }">
                <p class="m-0"><code class="text-black">Code</code></p>
            </a>
            <a
                class="dropdown-item"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ 'active': editor.isActive('blockquote') }">
                <blockquote>Blockquote</blockquote>
            </a>

        </div>
    </div>
</template>
<script setup lang="ts">

import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{editor: Editor}>();
const headingLevels = [1, 2, 3, 4, 5, 6];
</script>
