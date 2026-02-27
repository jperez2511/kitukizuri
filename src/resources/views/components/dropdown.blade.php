@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1', 'dropdownClasses' => ''])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'start-0';
        break;
    case 'top':
        $alignmentClasses = 'start-50 translate-middle-x';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    case 'right':
    default:
        $alignmentClasses = 'end-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'min-width: 12rem;';
        break;
    default:
        $width = '';
        break;
}
@endphp

<div class="position-relative d-inline-block" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition.opacity.duration.150ms
            class="dropdown-menu show mt-2 {{ $alignmentClasses }} {{ $dropdownClasses }}"
            style="display: none; {{ $width }}"
            @click="open = false">
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
