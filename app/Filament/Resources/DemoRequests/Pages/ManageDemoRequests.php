<?php

namespace App\Filament\Resources\DemoRequests\Pages;

use App\Filament\Resources\DemoRequests\DemoRequestResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDemoRequests extends ManageRecords
{
    protected static string $resource = DemoRequestResource::class;

    // Requests come from the public form; the team does not create them.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
