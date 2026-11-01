<?php

namespace App\Filament\Resources\PricingAddons\Pages;

use App\Filament\Resources\PricingAddons\PricingAddonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePricingAddons extends ManageRecords
{
    protected static string $resource = PricingAddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
