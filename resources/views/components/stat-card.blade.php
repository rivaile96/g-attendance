<div class="bg-white p-6 rounded-lg shadow-md flex items-center">
    <div class="{{ $iconColor }} text-white rounded-full p-3">
        {{ $slot }}
    </div>
    <div class="ml-4">
        <p class="text-sm text-gray-500">{{ $title }}</p>
        <p class="text-2xl font-bold text-dark-blue">{{ $value }}</p>
    </div>
</div>