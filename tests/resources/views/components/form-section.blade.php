@props(['submit' => null])

<form wire:submit="{{ $submit }}">
    <div>{{ $title ?? '' }}</div>
    <div>{{ $description ?? '' }}</div>
    <div>{{ $form ?? '' }}</div>
    <div>{{ $actions ?? '' }}</div>
</form>
