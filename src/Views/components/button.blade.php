@props(['loading' => '', 'type' => 'button', 'tag' => 'button'])

<{{ $tag }}
    class="relative mb-1 mr-1 inline-flex rounded bg-blue-500 px-6 py-3 text-sm font-bold uppercase text-white shadow outline-none transition-all duration-150 ease-linear after:absolute after:inset-0 after:z-0 after:bg-current after:opacity-0 after:transition-opacity hover:shadow-lg hover:after:opacity-10 focus:outline-none active:bg-blue-800"
    :disabled="{{ $loading }}" :class="{
        'pointer-events-none': {{ $loading }}
    }"
    type="{{ $type }}" {{ $attributes }}>
    <div class="flex items-center transition-opacity" :class="{
        'opacity-0': {{ $loading }}
    }">
        {{ $icon }}
        <span>{{ $slot }}</span>
    </div>
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-0 transition-opacity"
        :class="{
            'opacity-100': {{ $loading }}
        }">
        <span
            class="h-6 w-6 animate-spin rounded-full border-4 border-solid border-b-current border-l-transparent border-r-transparent border-t-current"></span>
    </div>
    </{{ $tag }}>
