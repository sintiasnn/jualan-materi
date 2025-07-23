<template>
    <div class="btn-group btn-group-sm rounded-0 me-1">
<!--        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-table"></i>
            </button>
            <div class="dropdown-menu animated&#45;&#45;fade-in" aria-labelledby="dropdownFadeIn">
                <a class="dropdown-item">
                <i>
                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="20px" width="20px" fill="#0061f2" viewBox="0 -960 960 960" >
                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm240-240H200v160h240v-160Zm80 0v160h240v-160H520Zm-80-80v-160H200v160h240Zm80 0h240v-160H520v160ZM200-680h560v-80H200v80Z"/>
                </svg>
            </i>
                </a>
            </div>
        </div>-->

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Insert Table"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().insertTable({
             rows: 3, cols: 3, withHeaderRow: true
            }).run()"
        >

            <i>
                <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 24 24">
                    <path fill="none" stroke="#0061f2" stroke-width="2" d="M8,5 L8,23 M16,5 L16,11 M1,11 L17,11 M1,5 L23,5 M1,17 L11,17 M17,23 L1,23 L1,1 L23,1 L23,17 M17,23 C20.3137085,23 23,20.3137085 23,17 C23,13.6862915 20.3137085,11 17,11 C13.6862915,11 11,13.6862915 11,17 C11,20.3137085 13.6862915,23 17,23 Z M17,14 L17,20 M17,14 L17,20 M14,17 L20,17"/>
                </svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Add Column Before"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().addColumnBefore().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M768-216v-528H540v528h228Zm-600 72v-144H240.49v72H468v-528H240v72h-72v-144h672v672H168Zm300-336Zm72 0h-72 72Zm0 0ZM168-360v-84H84v-72h84v-84h72v84h84v72h-84v84h-72Z"/></svg>
            </i>

        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Add Column After"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().addColumnAfter().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M192-744v528h228v-528H192Zm-72 600v-672h672v144H719.51v-72H492v528h228v-72h72v144H120Zm372-336Zm-72 0h72-72Zm0 0Zm300 120v-84h-84v-72h84v-84h72v84h84v72h-84v84h-72Z"/></svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Delete Column"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().deleteColumn().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 18 18" mirror-in-rtl="true">
                    <path fill="#0061f2" d="M18 1a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h10.02A4.98 4.98 0 0 1 10 15V2h2v9.024a4.953 4.953 0 0 1 2-.923V2h2v8.116a5.077 5.077 0 0 1 2 .9V1zM4 16H2V2h2v14zm4 0H6V2h2v14z"/>
                    <path fill="#0061f2" d="M13 14h4a1 1 0 0 1 1 1 1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1 1 1 0 0 1 1-1z"/>
                </svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Add Row Before"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().addRowBefore().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M216-192h528v-228H216v228Zm600 72H144v-672h144V-719.51h-72V-492h528v-228h-72v-72h144v672ZM480-492Zm0 72v-72 72Zm0 0Zm-36-216v-84h-84v-72h84v-84h72v84h84v72h-84v84h-72Z"/></svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Add Row After"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().addRowAfter().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M216-540h528v-228H216v228Zm-72 372v-672h672v672H672V-240.49h72V-468H216v228h72v72H144Zm336-300Zm0-72v72-72Zm0 0ZM444-84v-84h-84v-72h84v-84h72v84h84v72h-84v84h-72Z"/></svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Delete Row"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().deleteRow().run()"
        >
            <i>
                <svg width="15px" height="15px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" mirror-in-rtl="true">
                    <path fill="#0061f2" d="M17 0H1a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h10.02a5.064 5.064 0 0 1-.9-2H2v-2h8.1a4.944 4.944 0 0 1 .923-2H2v-2h13a4.98 4.98 0 0 1 3 1.02V1a1 1 0 0 0-1-1zm-1 8H2V6h14v2zm0-4H2V2h14v2z"/>
                    <path fill="#0061f2" d="M13 14h4a1 1 0 0 1 1 1 1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1 1 1 0 0 1 1-1z"/>
                </svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Delete Table"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().deleteTable().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 18 18">
                    <path fill="#0061f2" d="M17 0H1C.4 0 0 .5 0 1v16c0 .6.5 1 1 1h8c.6 0 1-.5 1-1v-2c0-2.8 2.2-5 5-5h2c.6 0 1-.5 1-1V1c0-.6-.4-1-1-1zM4 16H2v-2h2v2zm0-4H2v-2h2v2zm0-4H2V6h2v2zm0-4H2V2h2v2zm4 12H6v-2h2v2zm0-4H6v-2h2v2zm0-4H6V6h2v2zm0-4H6V2h2v2zm4 4h-2V6h2v2zm0-4h-2V2h2v2zm4 4h-2V6h2v2zm0-4h-2V2h2v2z"/>
                    <path fill="#0061f2" d="M18 15c0 .6-.5 1-1 1h-4c-.6 0-1-.5-1-1s.5-1 1-1h4c.6 0 1 .4 1 1z"/>
                </svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Merge Cells"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().mergeCells().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M144-144v-240h72v168h168v72H144Zm432 0v-72h168v-168h72v240H576ZM288-336l-51-51 57-57H96v-72h198l-57-57 51-51 144 144-144 144Zm384 0L528-480l144-144 51 51-57 57h198v72H666l57 57-51 51ZM144-576v-240h240v72H216v168h-72Zm600 0v-168H576v-72h240v240h-72Z"/></svg>
            </i>
        </button>

        <button
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="Split Cell"
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().splitCell().run()"
        >
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#0061f2"><path d="M216-192q-29.7 0-50.85-21.16Q144-234.32 144-264.04v-432.24Q144-726 165.15-747T216-768h192v72H216v432h192v72H216Zm264 96v-768h72v96h192q29.7 0 50.85 21.16Q816-725.68 816-695.96v432.24Q816-234 794.85-213T744-192H552v96h-72Zm72-168h192v-432H552v432Zm-336 0v-432 432Zm528 0v-432 432Z"/></svg>
            </i>
        </button>

        <!-- for formatting table -->
<!--        <button
            class="btn btn-sm btn-outline-primary"
            type="button"
            @click="editor.chain().focus().setCellAttribute('colspan', 2).run()"
        >
            Set cell attribute
        </button>-->


    </div>

    <div class="dropdown me-2 d-flex">
        <button class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdownFadeIn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="me-2 fa-solid fa-table"></i><span>Other</span>
        </button>
        <div class="dropdown-menu animated&#45;&#45;fade-in" aria-labelledby="dropdownFadeIn">
            <a
                class="dropdown-item"
                @click="editor.chain().focus().toggleHeaderColumn().run()"
            >
                Toggle header column
            </a>
            <a
                class="dropdown-item"
                @click="editor.chain().focus().toggleHeaderRow().run()"
            >
                Toggle header Row
            </a>
            <a
                class="dropdown-item"
                @click="editor.chain().focus().toggleHeaderCell().run()"
            >
                Toggle header cell
            </a>
        </div>
    </div>

</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import { Editor } from '@tiptap/vue-3';
const props = defineProps<{
    editor: Editor
}>();



</script>

<style lang="scss">
/* Basic editor styles */
button:hover svg path {
    fill: var(--bs-btn-hover-color);
}

.tiptap {
    :first-child {
        margin-top: 0;
    }

    /* Table-specific styling */
    table {
        border-collapse: collapse;
        margin: 0;
        overflow: hidden;
        table-layout: fixed;
        width: 100%;

        td,
        th {
            border: 1px solid var(--bs-gray-300);
            box-sizing: border-box;
            min-width: 1em;
            padding: 6px 8px;
            position: relative;
            vertical-align: top;

            > * {
                margin-bottom: 0;
            }
        }

        th {
            background-color: var(--bs-gray-100);
            font-weight: bold;
            text-align: left;
        }

        .selectedCell:after {
            /*background: var(rgba(236, 236, 236, 0.86));*/
            background: var(--bs-gray-200);
            content: "";
            left: 0; right: 0; top: 0; bottom: 0;
            pointer-events: none;
            position: absolute;
            z-index: 2;
        }

        .column-resize-handle {
            background-color: var(--bs-primary);
            bottom: -2px;
            pointer-events: none;
            position: absolute;
            right: -2px;
            top: 0;
            width: 4px;
        }
    }

    .tableWrapper {
        margin: 1.5rem 0;
        overflow-x: auto;
    }

    &.resize-cursor {
        cursor: ew-resize;
    }
}
</style>
