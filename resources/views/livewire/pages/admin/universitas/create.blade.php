<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="home"></i></div>
                            Tambah Universitas
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('admin.universitas') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Kembali ke Daftar Universitas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <livewire:pages.admin.components.universitas-form />
</x-app-layout>