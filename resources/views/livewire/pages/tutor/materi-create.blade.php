<x-app-layout>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-3">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="book"></i></div>
                            {{isset($editMode) && $editMode ? 'Edit Materi' : 'Buat Materi'}}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <livewire:pages.tutor.components.materi-form :editMode="(isset($editMode) && $editMode == true) ? $editMode : false" />

</x-app-layout>
