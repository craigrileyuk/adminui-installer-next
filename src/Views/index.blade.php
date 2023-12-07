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
    <div class="mt-8">
        <form id="installation-form" @submit.prevent="onSubmit">
            <div class="mb-3">
                <x-adminui-installer::input-text model="key" placeholder="Your AdminUI Licence Key">
                    <x-slot:icon>
                        <svg class="mr-2 h-6 w-6" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M7 14C5.9 14 5 13.1 5 12S5.9 10 7 10 9 10.9 9 12 8.1 14 7 14M12.6 10C11.8 7.7 9.6 6 7 6C3.7 6 1 8.7 1 12S3.7 18 7 18C9.6 18 11.8 16.3 12.6 14H16V18H20V14H23V10H12.6Z" />
                        </svg>
                    </x-slot:icon>
                </x-adminui-installer::input-text>
                <div class="px-2 text-sm text-red-300 opacity-0 transition-opacity" :class="{ 'opacity-100': error }">
                    ${ error }&nbsp;
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div v-if="installError" v-text="installError" class="max-w-sm text-red-300"></div>
                <div v-else v-text="installMessage" class="max-w-sm animate-pulse text-white/80"></div>
                <x-adminui-installer::button loading="isInstalling" type="submit">
                    <x-slot:icon>
                        <svg class="-ml-1 mr-2 h-6 w-6" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                        </svg>
                    </x-slot:icon>
                    Install
                </x-adminui-installer::button>
            </div>
            {{-- @if (true === $isMigrated)
                <div class="mt-8 flex items-center rounded border-l-8 border-l-blue-500 bg-blue-500/40 p-4">
                    <div class="mr-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/80">
                            <svg class="h-6 w-6" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z" />
                            </svg>
                        </div>
                    </div>
                    <div>It seems that a previously attempted install failed. Use the above form to reattempt.</div>
                </div>
            @endif --}}
        </form>
    </div>
@stop

@section('sidebar')
    <div class="flex min-h-full w-full justify-center py-4">
        <div>
            <h1 class="mb-8 text-lg font-black uppercase">Installation Progress</h1>
            <ul>
                <li v-if="status.saveKey" class="flex items-center">
                    <x-adminui-installer::icon-check class="mr-2 w-8 text-green-700" />
                    Saved Licence Key
                </li>
                <li v-if="status.downloadRelease" class="flex items-center">
                    <x-adminui-installer::icon-check class="mr-2 w-8 text-green-700" />
                    Downloaded Latest Release
                </li>
                <li v-if="status.releaseDetails?.version" class="flex items-center">
                    <x-adminui-installer::icon-check class="mr-2 w-8 text-green-700" />
                    Unpacked ${ status.releaseDetails.version }
                </li>
                <li v-if="status.dependencies" class="flex items-center">
                    <x-adminui-installer::icon-check class="mr-2 w-8 text-green-700" />
                    Updated Dependencies
                </li>
                <li>
                    <x-adminui-installer::loader />
                </li>
            </ul>
        </div>
    </div>
@stop

@push('scripts')
    <script type="module" defer>
        import {
            createApp
        } from 'https://unpkg.com/petite-vue@0.4.1/dist/petite-vue.es.js?module';

        const request = async (url, data = {}, config = {}) => {
            const result = await fetch(url, {
                method: config.method ?? "POST",
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            if (result.ok === false) {
                throw new Error(result.statusText);
            }
            return await result.json();
        }

        const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

        createApp({
            $delimiters: ['${', '}'],
            key: "{{ config('adminui-installer.test_key') ?? '' }}",
            version: "",
            error: "",
            status: @json($status),
            installMessage: "",
            installError: "",
            isInstalling: false,
            get installStarted() {
                return Object.values(this.status).some(line => !!line);
            },
            onError(errorMessage = null) {
                this.isInstalling = false;
                this.installError = errorMessage ?? "An error occurred!";
                return false;
            },
            async onSubmit() {
                this.isInstalling = true;

                if (!this.status.saveKey) {
                    const stepOne = await request("{{ route('adminui.installer.save-key') }}", {
                        licence_key: this.key
                    });
                    this.status = stepOne.status;
                }

                if (!this.status.downloadRelease) {
                    const stepTwo = await request("{{ route('adminui.installer.download-release') }}");
                    this.status = stepTwo.status;
                }

                if (!this.status.dependencies) {
                    const stepFour = await request("{{ route('adminui.installer.dependencies') }}");
                    this.status = stepFour.status;
                }

                setTimeout(() => {
                    this.isInstalling = false;
                }, 2000)
            },
        }).mount();
    </script>
@endpush
