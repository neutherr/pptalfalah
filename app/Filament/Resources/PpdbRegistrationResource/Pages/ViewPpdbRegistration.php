<?php

namespace App\Filament\Resources\PpdbRegistrationResource\Pages;

use App\Filament\Resources\PpdbRegistrationResource;
use App\Models\PpdbRegistration;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPpdbRegistration extends ViewRecord
{
    protected static string $resource = PpdbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('correctData')
                ->label('Koreksi Data')
                ->icon('heroicon-o-pencil-square')
                ->fillForm(fn () => PpdbRegistrationResource::correctionFormData($this->record))
                ->form(PpdbRegistrationResource::correctionFormSchema())
                ->action(function (array $data): void {
                    PpdbRegistrationResource::correctData($this->record, $data);
                    $this->record->refresh();
                }),
            Actions\Action::make('markReviewed')
                ->label('Tandai Ditinjau')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === PpdbRegistration::STATUS_NEW)
                ->action(function (): void {
                    $this->record->update([
                        'status' => PpdbRegistration::STATUS_REVIEWED,
                        'reviewed_at' => now(),
                    ]);
                    $this->record->refresh();
                }),
            Actions\Action::make('contactWhatsapp')
                ->label('Hubungi via WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn () => PpdbRegistrationResource::contactWhatsappUrl($this->record))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status === PpdbRegistration::STATUS_REVIEWED),
        ];
    }
}
