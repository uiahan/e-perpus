<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ReportResource\Widgets\ReportStats;
use Filament\Tables\Filters\Filter;
use Livewire\Livewire;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ReportStats::class,
        ];
    }

    public function updatedTableFilters(): void
    {
        $this->dispatch('filters-updated', filters: $this->tableFilters);
    }
}
