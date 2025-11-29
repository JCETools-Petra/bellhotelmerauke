@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-brand-gold focus:ring-brand-gold rounded-md shadow-sm']) !!}>