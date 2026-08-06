@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-[#1E3A5F] focus:ring-[#1E3A5F]/30 rounded-xl shadow-sm bg-white transition']) }}>