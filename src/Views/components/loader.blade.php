<span class="step-loader border-b-primary"></span>

@push('scripts')
    <style>
        .step-loader {
            width: 28px;
            height: 28px;
            border: 3px solid #FFF;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
