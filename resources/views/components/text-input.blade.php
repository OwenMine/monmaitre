@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-mm-secondary focus:ring-mm-secondary rounded-lg shadow-sm text-sm']) }}>