@props(['name' => 'dot'])

@php
    // Icons are kept in the component class so props/merge stay lazy and testable.
    $icons = \App\View\Components\Icon::icons();
@endphp

<svg {{ $attributes->merge(['class' => $compiledClass]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $icons[$name] ?? $icons['dot'] !!}
</svg>
