@props(['label', 'name'])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-on-surface">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes->class(['min-h-28 w-full rounded-lg border bg-white px-3 py-2.5 text-on-surface outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20', 'border-error' => $errors->has($name), 'border-outline-variant' => ! $errors->has($name)]) }}></textarea>
    @error($name)<p class="mt-2 text-sm font-medium text-error" role="alert">{{ $message }}</p>@enderror
</div>
