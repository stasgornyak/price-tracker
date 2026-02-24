@props([
    'icon' => null,
    'iconPosition' => 'left',
    'variant' => 'primary',
    'size' => 'default',
    'rounded' => false
])

@php
    $roundedClass = $rounded ? 'rounded-full p-2' : 'rounded-md';
    $baseClasses = "inline-flex items-center justify-center font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors $roundedClass";

    $variantClasses = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 focus:ring-gray-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    ];

    $sizeClasses = [
        'sm' => $rounded ? 'p-1.5' : 'px-1.5 py-1.5 text-sm',
        'default' => $rounded ? 'p-2' : 'px-2 py-2',
        'lg' => $rounded ? 'p-3' : 'px-2.5 py-2.5 text-lg',
    ];

    $iconSizeClasses = [
        'sm' => 'h-4 w-4',
        'default' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
    ];

    $iconClasses = [
        'primary' => 'w-5 h-5 text-white',
        'secondary' => 'w-5 h-5 text-gray-700 dark:text-gray-300',
        'success' => 'w-5 h-5 text-white',
        'danger' => 'w-5 h-5 text-white',
];

    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    $iconSize = $iconSizeClasses[$size];
    $iconClass = $iconClasses[$variant];
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && $iconPosition === 'left')
        @svg($icon, $iconClass)
        {{--@include("icons.{$icon}", ['class' => $iconSize . ($slot->isEmpty() ? '' : ' mr-2')])--}}
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right')
        @svg($icon, $iconClass)
        {{--@include("icons.{$icon}", ['class' => $iconSize . ($slot->isEmpty() ? '' : ' ml-2')])--}}
    @endif
</a>