<?php

namespace App\Filament\Resources\PricingPlans\Pages;

use App\Filament\Resources\PricingPlans\PricingPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePricingPlans extends ManageRecords
{
    protected static string $resource = PricingPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
