@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 rounded-lg shadow-sm transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500 font-medium text-gray-800 dark:text-white']) !!}>{{ $slot }}</textarea>
