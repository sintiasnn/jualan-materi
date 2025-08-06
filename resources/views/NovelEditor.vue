<template>
    <button @click="setContent">Set Content</button>
    <Editor ref="editorRef" />
</template>

<script setup lang="ts">
import { Editor as EditorClass } from "@tiptap/core";
import { Editor } from "novel-vue";
import {ref} from 'vue';
import "novel-vue/dist/style.css";

const editorRef = ref<{ editor: EditorClass }>();

function setContent() {
    if (editorRef.value) {
        editorRef.value.editor.commands.setContent({
            type: "doc",
            content: [
                {
                    type: "paragraph",
                    content: [
                        {
                            type: "text",
                            text: "Example Text",
                        },
                    ],
                },
            ],
        });
    }
}
</script>

<style lang="scss">
/* Basic editor styles */
.tiptap {
    :first-child {
        margin-top: 0;
    }

    /* List styles */
    ul,
    ol {
        padding: 0 1rem;
        margin: 1.25rem 1rem 1.25rem 0.4rem;

        li p {
            margin-top: 0.25em;
            margin-bottom: 0.25em;
        }
    }

    /* Heading styles */
    h1, h2, h3, h4, h5, h6 {
        line-height: 1.1rem;
        margin-top: 1.5rem;
        text-wrap: pretty;
    }

    h1, h2 {
        margin-top: 1rem;
        margin-bottom: 1.5rem;
    }

    h1 {
        font-size: 1.4rem;
    }

    h2 {
        font-size: 1.2rem;
    }

    h3 {
        font-size: 1.1rem;
    }

    h4, h5, h6 {
        font-size: 1rem;
    }

    /* Code and preformatted text styles */
    code {
        background-color: var(--bs-gray-400);
        border-radius: 0.4rem;
        color: var(--bs-black);
        font-size: 0.85rem;
        padding: 0.25em 0.3em;
    }

    pre {
        background: var(--bs-gray-600);
        border-radius: 0.5rem;
        color: var(--bs-white);
        font-family: 'JetBrainsMono', monospace;
        margin: 1.5rem 0;
        padding: 0.75rem 1rem;

        code {
            background: none;
            color: inherit;
            font-size: 0.8rem;
            padding: 0;
        }
    }

    blockquote {
        border-left: 3px solid var(--bs-gray-600);
        margin: 1.5rem 0;
        padding-left: 1rem;
    }

    hr {
        border: none;
        border-top: 1px solid var(--bs-gray-600);
        margin: 2rem 0;
    }

    .ProseMirror .is-editor-empty:first-child:before{
        content:attr(data-placeholder);
        float:left;
        color:var(rgb(168 162 158));
        pointer-events:none;
        height:0
    }
    .ProseMirror .is-empty:before{
        content:attr(data-placeholder);
        float:left;
        color:var(rgb(168 162 158));
        pointer-events:none;
        height:0
    }

    .ProseMirror:focus .ProseMirror-focused{
        outline:3px solid #6a6a6a;
    }

    .ProseMirror img{
        transition:filter .1s ease-in-out
    }
    .ProseMirror img:hover{
        cursor:pointer;
        filter:brightness(90%)
    }
    .ProseMirror img.ProseMirror-selectednode{
        outline:3px solid #5abbf7;
        filter:brightness(90%)
    }

    .ProseMirror img.ProseMirror-focused{
        outline:3px solid #6a6a6a;
        filter:brightness(90%)
    }

    /* Details */
    .details {
        display: flex;
        gap: 0.25rem;
        margin: 1.5rem 0;
        border: 1px solid var(--bs-gray-200);
        border-radius: 0.5rem;
        padding: 0.5rem;

        summary {
            font-weight: 700;
        }

        > button {
            align-items: center;
            background: transparent;
            border-radius: 4px;
            display: flex;
            font-size: 1.5rem;
            height: 1.25rem;
            justify-content: center;
            line-height: 1;
            margin-top: 0.1rem;
            padding: 0;
            width: 1.25rem;

            &:hover {
                background-color: var(--bs-gray-200);
            }

            &::before {
                content: '\25B6';
            }

        }

        &.is-open > button::before {
            transform: rotate(90deg);
        }

        & > button::before {
            transition: transform 0.3s ease; /* add this line for smooth rotation */
        }

        > div {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;

            > [data-type="detailsContent"] > :last-child {
                margin-bottom: 0.5rem;
            }
        }

        .details {
            margin: 0.5rem 0;
        }
    }
}
</style>
