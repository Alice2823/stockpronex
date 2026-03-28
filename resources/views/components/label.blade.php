@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1']) }}>
    {{ $value ?? $slot }}
</label>
