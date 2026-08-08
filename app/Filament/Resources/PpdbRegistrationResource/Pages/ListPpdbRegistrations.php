<?php

namespace App\Filament\Resources\PpdbRegistrationResource\Pages;

use App\Filament\Resources\PpdbRegistrationResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPpdbRegistrations extends ListRecords
{
    protected static string $resource = PpdbRegistrationResource::class;

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $search = preg_replace('/\D/', '', (string) $this->getTableSearch());

        if (strlen($search) === 16) {
            return $query->where('nik_hash', hash('sha256', $search));
        }

        return parent::applySearchToTableQuery($query);
    }
}
