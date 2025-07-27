<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <!-- Page Header -->
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                            List Paket Belajar
                        </h1>
                        <div class="page-header-subtitle">List Paket yang telah dibeli</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <livewire:pages.tutor.components.paket-materi/>
</x-app-layout>
