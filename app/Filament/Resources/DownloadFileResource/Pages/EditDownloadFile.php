<?php

namespace App\Filament\Resources\DownloadFileResource\Pages;

use App\Filament\Resources\DownloadFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDownloadFile extends EditRecord
{
    protected static string $resource = DownloadFileResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
