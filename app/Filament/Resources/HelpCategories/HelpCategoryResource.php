<?php

namespace App\Filament\Resources\HelpCategories;

use App\Filament\Resources\HelpCategories\Pages\ManageHelpCategories;
use App\Filament\Support\Translations;
use App\Models\HelpCategory;
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

class HelpCategoryResource extends Resource
{
    protected static ?string $model = HelpCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|\UnitEnum|null $navigationGroup = 'Help center';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('icon')->required()->default('book-open')->helperText('A Lucide icon name. See lucide.dev/icons'),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("title.$l")->label('Title')->required($l === 'fa'),
                Textarea::make("summary.$l")->label('Summary')->rows(2),
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
                TextColumn::make('articles_count')->counts('articles')->label('Articles'),
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
        return ['index' => ManageHelpCategories::route('/')];
    }
}
