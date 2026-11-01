<?php

namespace App\Filament\Resources\HelpCategories\Pages;

use App\Filament\Resources\HelpCategories\HelpCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHelpCategories extends ManageRecords
{
    protected static string $resource = HelpCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
