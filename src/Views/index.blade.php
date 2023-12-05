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
            partialInstall: !!@json(true === $isMigrated),
            version: "",
            error: "",
            log: [],
            installMessage: "",
            installError: "",
            isInstalling: false,
            onError(errorMessage = null) {
                console.log(this.log);
                this.isInstalling = false;
                this.installError = errorMessage ?? "An error occurred!";
                return false;
            },
            async onSubmit() {
                this.error = this.installError = this.version = "";
                if (this.partialInstall) {
                    this.version = window.localStorage.getItem('aui-installing-version') || "";
                } else {
                    window.localStorage.removeItem("aui-installing-version");
                }
                this.isInstalling = true;
                this.log = [];

                if (this.partialInstall !== true) {
                    /* ******************************************
                     * STEP ONE
                     ****************************************** */
                    this.installMessage = "Downloading install package...";
                    const stepOneResult = await fetch("{{ route('adminui.installer.download') }}", {
                        method: "POST",
                        headers: jsonHeaders,
                        body: JSON.stringify({
                            key: this.key
                        })
                    });
                    const stepOneJson = await stepOneResult.json().catch(err => {
                        return genericError;
                    });;
                    if (stepOneJson?.log) this.log.push(...stepOneJson.log);
                    if (stepOneJson?.data?.version) this.version = stepOneJson.data.version;
                    if (stepOneJson.status !== "success") {
                        return this.onError(stepOneJson.error);
                    }


                    /* ******************************************
                     * STEP TWO
                     ****************************************** */
                    this.installMessage = `Extracting AdminUI ${this.version} package...`;
                    const stepTwoResult = await fetch("{{ route('adminui.installer.extract') }}", {
                        method: "POST",
                        headers: jsonHeaders,
                        body: JSON.stringify({
                            key: this.key
                        })
                    });
                    const stepTwoJson = await stepTwoResult.json().catch(err => {
                        return genericError;
                    });
                    if (stepTwoJson?.log) this.log.push(...stepTwoJson.log);
                    if (stepTwoJson.status !== "success") {
                        return this.onError(stepTwoJson.error);
                    }

                    /* ******************************************
                     * STEP THREE
                     ****************************************** */
                    this.installMessage = "Downloading system dependencies...";
                    const stepThreeResult = await fetch("{{ route('adminui.installer.dependencies') }}", {
                        method: "POST",
                        headers: jsonHeaders,
                        body: JSON.stringify({
                            key: this.key,
                        })
                    });
                    const stepThreeJson = await stepThreeResult.json().catch(err => {
                        return genericError;
                    });
                    if (stepThreeJson?.log) this.log.push(...stepThreeJson.log);
                    if (stepThreeJson.status !== "success") {
                        return this.onError(stepThreeJson.error);
                    }
                }

                /* ******************************************
                 * STEP THREE POINT FIVE
                 ****************************************** */
                this.installMessage = "Flushing Cache...";
                const stepCacheResult = await fetch("{{ route('adminui.installer.clear-cache') }}", {
                    method: "POST",
                    headers: jsonHeaders,
                    body: JSON.stringify({
                        key: this.key,
                    })
                });
                const stepCacheJson = await stepCacheResult.json().catch(err => {
                    return genericError;
                });
                if (stepCacheJson?.log) this.log.push(...stepCacheJson.log);
                if (stepCacheJson.status !== "success") {
                    return this.onError(stepCacheJson.error);
                }

                /* ******************************************
                 * STEP FOUR
                 ****************************************** */
                this.installMessage = "Preparing database update...";
                const stepFourResult = await fetch("{{ route('adminui.installer.base-publish') }}", {
                    method: "POST",
                    headers: jsonHeaders,
                });
                const stepFourJson = await stepFourResult.json().catch(err => {
                    return genericError;
                });
                if (stepFourJson?.log) this.log.push(...stepFourJson.log);
                if (stepFourJson.status !== "success") {
                    return this.onError(stepFourJson.error);
                }

                /* ******************************************
                 * STEP FIVE
                 ****************************************** */
                this.installMessage = "Updating database...";
                const stepFiveResult = await fetch("{{ route('adminui.installer.base-migrations') }}", {
                    method: "POST",
                    headers: jsonHeaders,
                });
                const stepFiveJson = await stepFiveResult.json().catch(err => {
                    return genericError;
                });
                if (stepFiveJson?.log) this.log.push(...stepFiveJson.log);
                if (stepFiveJson.status !== "success") {
                    return this.onError(stepFiveJson.error);
                }

                /* ******************************************
                 * STEP SIX
                 ****************************************** */
                this.installMessage = "Installing AdminUI resources";
                const stepSixResult = await fetch("{{ route('adminui.installer.publish') }}", {
                    method: "POST",
                    headers: jsonHeaders,
                    body: JSON.stringify({
                        key: this.key,
                        version: this.version
                    })
                });
                const stepSixJson = await stepSixResult.json().catch(err => {
                    return genericError;
                });
                if (stepSixJson?.log) this.log.push(...stepSixJson.log);
                if (stepSixJson.status !== "success") {
                    return this.onError(stepSixJson.error);
                }


                /* ******************************************
                 * STEP SEVEN
                 ****************************************** */
                this.installMessage = "Finishing installation";
                await sleep(2000);
                const stepSevenResult = await fetch("{{ route('adminui.installer.finish') }}", {
                    method: "POST",
                    headers: jsonHeaders,
                    body: JSON.stringify({
                        key: this.key,
                        version: this.version
                    })
                });
                const stepSevenJson = await stepSevenResult.json().catch(err => {
                    return genericError;
                });
                if (stepSevenJson?.log) this.log.push(...stepSevenJson.log);
                if (stepSevenJson.status !== "success") {
                    return this.onError(stepSevenJson.error);
                }

                let count = 3,
                    setCountdown;
                (setCountdown = () => {
                    this.installMessage =
                        `Complete. Redirecting to admin registration page in ${count}`;
                })();

                this.isInstalling = false;

                let interval = setInterval(() => {
                    if (count <= 0) {
                        clearInterval(interval);
                        window.location.href = "{{ route('adminui.installer.register') }}";
                    }
                    setCountdown();
                    count--;
                }, 1000)
            }
        }).mount()
    </script>
@endpush
