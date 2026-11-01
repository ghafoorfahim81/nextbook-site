<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\ManageRecords;

class ManageContactMessages extends ManageRecords
{
    protected static string $resource = ContactMessageResource::class;

    // Messages come from the public form; the team does not create them.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
