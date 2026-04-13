<?php

namespace App\Filament\Resources\PpdbPeriodResource\Pages;

use App\Filament\Resources\PpdbPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpdbPeriod extends EditRecord
{
    protected static string $resource = PpdbPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
