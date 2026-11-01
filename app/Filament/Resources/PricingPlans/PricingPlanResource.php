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
            TextInput::make('monthly')->label('Monthly price (US dollars)')->numeric()->minValue(0)->prefix('$')
                ->helperText('Leave both prices empty for a plan without a fixed price: the page then shows the text under "Price text".'),
            TextInput::make('yearly')->label('Yearly price (US dollars)')->numeric()->minValue(0)->prefix('$')
                ->helperText('The page works out the monthly equivalent and what the year saves.'),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_featured')->helperText('Highlights the plan with a "Recommended" label.'),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("name.$l")->label('Plan name')->required($l === 'fa'),
                TextInput::make("audience.$l")->label('Who it is for'),
                TextInput::make("includes.$l")->label('Lead-in line')->helperText('For example "Everything in Starter, plus:". Leave empty for the first plan.'),
                TextInput::make("price_label.$l")->label('Price text')->helperText('Only shown when the plan has no fixed price, for example "Contact us".'),
                Repeater::make("points.$l")->label('What is included')->schema([
                    TextInput::make('text')->required(),
                    Toggle::make('soon')->label('Not built yet: show "Coming soon"'),
                ])->defaultItems(0)->collapsible()->itemLabel(fn (array $state) => $state['text'] ?? null),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->sortable(),
                TextColumn::make('name'),
                TextColumn::make('monthly')->prefix('$')->label('Monthly'),
                TextColumn::make('yearly')->prefix('$')->label('Yearly'),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('sort')
            ->recordActions([
                EditAction::make()->slideOver()->modalWidth('4xl')
                    ->mutateRecordDataUsing(fn (array $data, Model $record) => self::fill($record, $data)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    /** All languages, with every included item as {text, soon} (older rows held plain strings). */
    private static function fill(Model $record, array $data): array
    {
        $data = Translations::fill($record, $data);

        foreach ($data['points'] as $locale => $points) {
            $data['points'][$locale] = array_map(
                fn ($point) => is_array($point) ? $point + ['soon' => false] : ['text' => (string) $point, 'soon' => false],
                (array) $points,
            );
        }

        return $data;
    }

    public static function getPages(): array
    {
        return ['index' => ManagePricingPlans::route('/')];
    }
}
