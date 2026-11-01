<?php

namespace App\Filament\Resources\DemoRequests;

use App\Filament\Resources\DemoRequests\Pages\ManageDemoRequests;
use App\Models\DemoRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DemoRequestResource extends Resource
{
    protected static ?string $model = DemoRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static string|\UnitEnum|null $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 10;

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'demo_done' => 'Demo done',
        'customer' => 'Customer',
        'lost' => 'Lost',
    ];

    public static function getNavigationBadge(): ?string
    {
        $new = DemoRequest::where('status', 'new')->count();

        return $new ? (string) $new : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Text::make(fn (?DemoRequest $record) => $record ? "{$record->name} · {$record->phone} · {$record->email}" : '')->columnSpanFull(),
            Text::make(fn (?DemoRequest $record) => $record ? trim("{$record->company} · {$record->business_type} · {$record->branches}") : '')->columnSpanFull(),
            Text::make(fn (?DemoRequest $record) => $record?->message ?: '-')->columnSpanFull(),
            Select::make('status')->options(self::STATUSES)->required(),
            Textarea::make('notes')->label('Follow-up notes')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Received')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable()->copyable(),
                TextColumn::make('business_type')->label('Business'),
                TextColumn::make('branches'),
                SelectColumn::make('status')->options(self::STATUSES),
            ])
            ->filters([SelectFilter::make('status')->options(self::STATUSES)])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageDemoRequests::route('/')];
    }
}
