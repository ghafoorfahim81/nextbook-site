<?php

namespace App\Filament\Resources\PricingPlans;

use App\Filament\Resources\PricingPlans\Pages\ManagePricingPlans;
use App\Filament\Support\Translations;
use App\Models\PricingPlan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
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

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_featured')->helperText('Highlights the plan with a "Recommended" label.'),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("name.$l")->label('Plan name')->required($l === 'fa'),
                Textarea::make("description.$l")->label('Short description')->rows(2),
                TextInput::make("price_label.$l")->label('Price')->required($l === 'fa')
                    ->helperText('Free text: a figure such as "$20", or "Contact us".'),
                TextInput::make("period.$l")->label('Period')->helperText('For example "per month". Leave empty if it does not apply.'),
                Repeater::make("points.$l")->label('Included')->simple(TextInput::make('point')->required())->defaultItems(0),
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
                IconColumn::make('is_featured')->boolean(),
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
        return ['index' => ManagePricingPlans::route('/')];
    }
}
