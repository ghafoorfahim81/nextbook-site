<?php

namespace App\Filament\Resources\Industries;

use App\Filament\Resources\Industries\Pages\ManageIndustries;
use App\Filament\Support\Translations;
use App\Models\Industry;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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

class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Select::make('business_type')->options(
                collect(config('site.business_types'))->mapWithKeys(fn ($t) => [$t => $t])->all()
            )->helperText('The business type in the ERP that this page describes. The demo form preselects it.'),
            TextInput::make('icon')->required()->default('store')->helperText('A Lucide icon name. See lucide.dev/icons'),
            \Filament\Forms\Components\CheckboxList::make('feature_slugs')->label('Modules this trade uses')
                ->options(fn () => \App\Models\Feature::where('status', 'available')->orderBy('sort')->get()->mapWithKeys(fn ($f) => [$f->slug => $f->getTranslation('title', 'en', true)])->all())
                ->columns(3)->columnSpanFull(),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("title.$l")->label('Title')->required($l === 'fa'),
                Textarea::make("summary.$l")->label('Summary (one line)')->rows(2)->required($l === 'fa'),
                Textarea::make("overview.$l")->label('Overview')->rows(6)->helperText('Separate paragraphs with a blank line.'),
                Repeater::make("highlights.$l")->label('What is different for this trade')->schema([
                    TextInput::make('title')->required(),
                    Textarea::make('text')->rows(2)->required(),
                ])->defaultItems(0)->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null),
                Repeater::make("steps.$l")->label('A day with NextBook')->simple(TextInput::make('step')->required())->defaultItems(0),
                Repeater::make("points.$l")->label('Also included')->simple(TextInput::make('point')->required())->defaultItems(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->sortable(),
                TextColumn::make('title'),
                TextColumn::make('slug')->color('gray'),
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
        return ['index' => ManageIndustries::route('/')];
    }
}
