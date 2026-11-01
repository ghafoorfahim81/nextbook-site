<?php

namespace App\Filament\Resources\PricingAddons;

use App\Filament\Resources\PricingAddons\Pages\ManagePricingAddons;
use App\Filament\Support\Translations;
use App\Models\PricingAddon;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PricingAddonResource extends Resource
{
    protected static ?string $model = PricingAddon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 35;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('icon')->required()->default('sparkles')->helperText('A Lucide icon name. See lucide.dev/icons'),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("name.$l")->label('Name')->required($l === 'fa'),
                Textarea::make("description.$l")->label('Description')->rows(2)->required($l === 'fa'),
                TextInput::make("price_label.$l")->label('Price text')->helperText('For example "Quoted to your needs" or "$50 per day".'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->sortable(),
                TextColumn::make('name'),
                TextColumn::make('price_label')->label('Price'),
                IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('sort')
            ->recordActions([
                EditAction::make()->mutateRecordDataUsing(fn (array $data, Model $record) => Translations::fill($record, $data)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePricingAddons::route('/')];
    }
}
