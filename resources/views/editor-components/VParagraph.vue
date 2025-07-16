<template>
    <div class="dropdown">
        <button
            data-toggle="tooltip"
            data-placement="top"
            title="Align"
            class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-align-left"></i>
        </button>
        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">
            <a v-for="(align, i) in aligns"
               :key="i"
               class="dropdown-item"
               @click="editor.chain().focus().setTextAlign(align.value).run()"
               :class="{ 'active': editor.isActive({ textAlign: align.value }) }"
            >
                <i class="me-1" :class="align.icon"></i> <span>Align {{align.value}}</span>
            </a>
        </div>
    </div>
</template>

<script setup lang="ts">

import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{editor: Editor}>();

const aligns = [
    { value: 'left', icon: 'fa-solid fa-align-left' },
    { value: 'center', icon: 'fa-solid fa-align-center' },
    { value: 'right', icon: 'fa-solid fa-align-right' },
    { value: 'justify', icon: 'fa-solid fa-align-justify' }
]
</script>
