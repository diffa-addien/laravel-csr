<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <div class="flex items-end gap-4">
                <div class="flex-grow">
                    {{ $this->form }}
                </div>
                <div>
                    {{ $this->getApplyAction() }}
                </div>
                <div>
                    {{ $this->getPrintAction() }}
                </div>
            </div>
        </div>

        <div class="shadow-lg">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>