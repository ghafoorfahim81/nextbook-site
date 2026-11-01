<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ManageContactMessages;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|\UnitEnum|null $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 20;

    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::where('is_read', false)->count();

        return $unread ? (string) $unread : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Text::make(fn (?ContactMessage $record) => $record ? "{$record->name} · {$record->phone} · {$record->email}" : '')->columnSpanFull(),
            Text::make(fn (?ContactMessage $record) => $record?->subject ?: '-')->columnSpanFull(),
            Text::make(fn (?ContactMessage $record) => $record?->message ?? '')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Received')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->copyable(),
                TextColumn::make('email')->copyable(),
                TextColumn::make('message')->limit(50),
                ToggleColumn::make('is_read')->label('Read'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageContactMessages::route('/')];
    }
}
