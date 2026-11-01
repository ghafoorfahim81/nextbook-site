<?php

namespace App\Filament\Resources\Features;

use App\Filament\Resources\Features\Pages\ManageFeatures;
use App\Filament\Support\Translations;
use App\Models\Feature;
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

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Used in the address: /fa/features/{slug}'),
            TextInput::make('icon')->required()->default('boxes')->helperText('A Lucide icon name, such as boxes, calculator, users-round. See lucide.dev/icons'),
            Select::make('status')->options(['available' => 'Available', 'soon' => 'Coming soon'])->required()->default('available')
                ->helperText('Mark anything that is not built yet as "Coming soon".'),
            TextInput::make('help_slug')->label('Help center guide')->datalist(fn () => \App\Models\HelpCategory::orderBy('sort')->pluck('slug')->all())
                ->helperText('The slug of a help category; the page links to that guide. Leave empty for none.'),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("title.$l")->label('Title')->required($l === 'fa'),
                Textarea::make("summary.$l")->label('Summary (one line)')->rows(2)->required($l === 'fa'),
                Textarea::make("overview.$l")->label('Overview')->rows(6)->helperText('Separate paragraphs with a blank line.'),
                Repeater::make("highlights.$l")->label('Key capabilities')->schema([
                    TextInput::make('title')->required(),
                    Textarea::make('text')->rows(2)->required(),
                ])->defaultItems(0)->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null),
                Repeater::make("steps.$l")->label('In a working day')->simple(TextInput::make('step')->required())->defaultItems(0),
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
                TextColumn::make('status')->badge()->color(fn (string $state) => $state === 'soon' ? 'warning' : 'success'),
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
        return ['index' => ManageFeatures::route('/')];
    }
}
