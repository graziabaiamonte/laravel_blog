{{-- @props([]) --}}

<div {{ $attributes->merge(['class' => 'mb-4 rounded-card border border-line bg-surface p-5 shadow-card transition-shadow hover:shadow-card-hover']) }}>
    {{ $slot }}
</div>
