<template>
    <div class="dropdown">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-regular fa-square-caret-down"></i>
        </button>
        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownFadeIn">
            <a
                class="dropdown-item"
                @click="editor.chain().focus().setDetails().run()"
            >
               <i class="me-1 fa-regular fa-square-caret-down"></i><span>Button list</span>
            </a>
        </div>
    </div>

</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{editor: Editor}>();
</script>
