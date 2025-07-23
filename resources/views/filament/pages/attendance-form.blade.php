<div class="min-h-screen flex items-center justify-center p-6">
    <form wire:submit.prevent="submit" class="space-y-6 w-full max-w-md bg-white dark:bg-gray-900 p-6 rounded shadow">
        {{ $this->form }}
        <x-filament::button type="submit" color="primary" class="w-full">
            Simpan Kehadiran
        </x-filament::button>
    </form>
</div>
