@props([])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150']) }}
    style="background-color:#1E3A5F; box-shadow: 0 4px 14px -4px rgba(30, 58, 95, 0.4)"
    onmouseover="this.style.backgroundColor='#3B5A7A'" onmouseout="this.style.backgroundColor='#1E3A5F'">
    {{ $slot }}
</button>