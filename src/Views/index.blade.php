@extends('adminui-installer::layout')

@section('title')
    AdminUI Installer
@stop

@section('content')
    <div class="flex max-w-xl gap-8">
        <div>
            <x-adminui-installer::logo></x-adminui-installer::logo>
        </div>
        <div class="text-white/90">
            <h1 class="mb-4 text-3xl font-bold">Welcome to AdminUI</h1>
            <p class="mb-2 text-white/70">Your new Laravel-based CMS and Ecommerce framework</p>
            <p class="text-white/70">To begin our one-click install, simply enter your licence key in the input below
                and then click the install button.</p>
        </div>
    </div>
@stop

@push('scripts')
    <script type="module">
        const jsonHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
        const genericError = {
            status: 'error',
            error: 'Server error'
        };

        const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

        import {
            createApp
        } from 'https://unpkg.com/petite-vue?module'
        createApp({
            key: "{{ config('adminui-installer.test_key') ?? '' }}",
            version: "",
            error: "",
            log: [],
            installMessage: "",
            installError: "",
            isInstalling: false,
            onError(errorMessage = null) {
                this.isInstalling = false;
                this.installError = errorMessage ?? "An error occurred!";
                return false;
            },
            async onSubmit() {

            }
        }).mount()
    </script>
@endpush
