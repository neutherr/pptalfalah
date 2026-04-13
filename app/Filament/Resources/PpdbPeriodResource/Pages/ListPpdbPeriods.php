<?php

namespace App\Filament\Resources\PpdbPeriodResource\Pages;

use App\Filament\Resources\PpdbPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPpdbPeriods extends ListRecords
{
    protected static string $resource = PpdbPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
