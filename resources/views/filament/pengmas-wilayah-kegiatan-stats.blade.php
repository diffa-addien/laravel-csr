<div>
    @foreach ($stats as $stat)
        <div class="flex items-center space-x-2">
            <div class="text-2xl font-bold">{{ $stat->value }}</div>
            <div class="text-gray-500">{{ $stat->description }}</div>
            <div class="text-gray-500">
                <x-heroicon-o-{{ $stat->icon }} class="w-6 h-6" />
            </div>
        </div>
    @endforeach
</div>