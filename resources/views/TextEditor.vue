<template>
    <div v-if="editor" class="container">
        <div class="row mb-1">
            <div class="col-sm-2 d-flex">
                <v-heading class="me-1" :editor="editor" />
                <v-font-style class="me-1" :editor="editor"/>
                <v-list class="me-1" :editor="editor"/>
                <v-paragraph class="me-1" :editor="editor" />
                <v-undo-redo class="me-1" :editor="editor" />
                <v-details :editor="editor"/>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-sm-12">
                <div class="btn-group btn-group-sm rounded-0">
                    <button
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Code Block"
                        type="button"
                        class="btn btn-sm"
                        @click="editor.chain().focus().toggleCodeBlock().run()"
                        :class="editor.isActive('codeBlock') ? 'btn-primary' : 'btn-outline-primary'">
                        <i class="fa-solid fa-code"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="editor.chain().focus().setHorizontalRule().run()"
                    >
                        Horizontal rule
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="editor.chain().focus().setHardBreak().run()">
                        Hard break
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="editor.chain().focus().unsetAllMarks().run()">
                        Clear marks
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        @click="editor.chain().focus().clearNodes().run()">
                        Clear nodes
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-sm-12">
                <v-table :editor="editor" />
            </div>
        </div>

        <bubble-menu
            class="bubble-menu"
            :tippy-options="{ duration: 100 }"
            :editor="editor"
        >
        </bubble-menu>

        <floating-menu
            :tippy-options="{
            placement:'left',
            animation: 'fade',
            duration: [300, 300],
        }"
            :editor="editor"
        >
        </floating-menu>

        <drag-handle
            @nodeChange="handleNodeChange"
            plugin-key="drag-only"
            :tippy-options="{
                placement: 'left',
                animation: 'fade',
                duration: [300, 300],
            }"
            :editor="editor">
            <div class="d-flex flex-row align-items-center">
                <div class="dropdown">
                    <button
                        class="btn btn-sm btn-outline-dark p-1 me-1 dropdown-toggle"
                        id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
                    <div class="dropdown-menu animated--fade-in overflow-auto" aria-labelledby="dropdownFadeIn" style="max-height: 15em">
                        <div class="dropdown-header dropdown-notifications-header m-0">
                            Tambahkan
                        </div>
                        <hr class="m-0">
                        <a class="dropdown-item"><i class="me-1"></i><span>Heading 1</span></a>
                        <a class="dropdown-item"><i class="me-1"></i><span>Heading 2</span></a>
                        <a class="dropdown-item"><i class="me-1"></i><span>Heading 3</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-list-ul"></i><span>Bullet List</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-list-ol"></i><span>Ordered List</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-regular fa-square-check"></i><span>Checkbox List</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-regular fa-square-caret-down"></i><span>Dropdown List</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-quote-left"></i><span>Block Quote</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-table"></i><span>Table</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-minus"></i><span>Horizontal Line</span></a>
                        <hr class="m-0">
                        <div class="dropdown-header dropdown-notifications-header m-0">
                            Media
                        </div>
                        <hr class="m-0">
                        <a class="dropdown-item"><i class="me-1 fa-regular fa-image"></i><span>Image</span></a>
                        <a class="dropdown-item"><i class="me-1 fa-solid fa-video"></i><span>Video</span></a>
                    </div>

                </div>

                <div class="custom-drag-handle"></div>
            </div>

        </drag-handle>

        <editor-content class="border rounded-1" :editor="editor" />
    </div>
</template>
<script>

import { Color } from '@tiptap/extension-color'
import ListItem from '@tiptap/extension-list-item'
import TextStyle from '@tiptap/extension-text-style'
import StarterKit from '@tiptap/starter-kit'
import TextAlign from '@tiptap/extension-text-align'
import Placeholder from '@tiptap/extension-placeholder'
import Underline from '@tiptap/extension-underline'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import {Editor, EditorContent, BubbleMenu, FloatingMenu} from "@tiptap/vue-3";
import NodeRange from '@tiptap/extension-node-range'
import { DragHandle } from '@tiptap/extension-drag-handle-vue-3'
import VHeading from "./editor-components/VHeading.vue";
import VParagraph from "./editor-components/VParagraph.vue";
import VUndoRedo from "./editor-components/VUndoRedo.vue";
import VFontStyle from "./editor-components/VFontStyle.vue";
import VDetails from "./editor-components/VDetails.vue";
import VList from "./editor-components/VList.vue";
import VTable from "./editor-components/VTable.vue";
import Details from '@tiptap/extension-details'
import DetailsContent from '@tiptap/extension-details-content'
import DetailsSummary from '@tiptap/extension-details-summary'

import Gapcursor from '@tiptap/extension-gapcursor'
import Table from '@tiptap/extension-table'
import TableCell from '@tiptap/extension-table-cell'
import TableHeader from '@tiptap/extension-table-header'
import TableRow from '@tiptap/extension-table-row'

import Dropcursor from '@tiptap/extension-dropcursor'
import Image from '@tiptap/extension-image'


import {ref} from "vue";

export default {
    components: {
        VParagraph,
        VHeading,
        VUndoRedo,
        VFontStyle,
        VList,
        VDetails,
        VTable,
        Details,
        DetailsContent,
        DetailsSummary,
        EditorContent,
        Document,
        Paragraph,
        Text,
        BubbleMenu,
        FloatingMenu,
        DragHandle,
        Underline,

        Gapcursor,
        Table,
        TableRow,
        TableHeader,
        TableCell,

        Image,
        Dropcursor,

    },

    data() {
        return {
            editor: null
        }
    },

    methods: {
        addImage() {
            const url = window.prompt('URL')
            if (url) {
                this.editor.chain().focus().setImage({ src: url }).run()
            }
        },
    },

    setup(){
        const selectedNode = ref(null)
        const handleNodeChange = ({ node, editor, pos }) => {
            selectedNode.value = node
        }
        return {
            selectedNode,
            handleNodeChange,
        }
    },

    mounted(){
        this.editor = new Editor({
            extensions: [
                  Document,
                  Paragraph,
                  Text,
                  Underline,
                  TextAlign.configure({
                      types: ['heading', 'paragraph'],
                  }),
                  NodeRange.configure({
                      key: null,
                  }),
                  Color.configure({ types: [TextStyle.name, ListItem.name] }),
                  TextStyle.configure({ types: [ListItem.name] }),
                  StarterKit,

                  Details.configure({
                      persist: true,
                      HTMLAttributes: {
                          class: 'details',
                      },
                  }),
                  DetailsSummary,
                  DetailsContent,

                  Gapcursor,
                  Table.configure({
                      resizable: true,
                  }),
                  TableRow,
                  TableHeader,
                  TableCell,

                  Placeholder.configure({
                      placeholder: ({ node }) => {
                          if (node.type.name === 'heading') {
                              return 'What’s the title?'
                          }
                          return 'Can you add some further context?'
                      },
                  }),
              ],
            content: ` <h2>Hi there,</h2>`
        })
    },

    beforeUnmount() {
        this.editor.destroy()
    },


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
