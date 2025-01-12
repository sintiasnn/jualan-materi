<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <header class="page-header page-header-compact page-header-light bg-transparent mb-4">
        <div class="container-fluid px-5 py-5">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="display-6 text-gray-700">
                            {{ __("Welcome, " . auth()->user()->name) }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
</x-app-layout>
