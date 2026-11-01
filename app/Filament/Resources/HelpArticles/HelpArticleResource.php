<?php

namespace App\Filament\Resources\HelpArticles;

use App\Filament\Resources\HelpArticles\Pages\ManageHelpArticles;
use App\Filament\Support\Translations;
use App\Models\HelpArticle;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HelpArticleResource extends Resource
{
    protected static ?string $model = HelpArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Help center';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('help_category_id')->relationship('category', 'slug')->label('Category')->required()->preload(),
            TextInput::make('slug')->required()->unique(
                ignoreRecord: true,
                modifyRuleUsing: fn ($rule, $get) => $rule->where('help_category_id', $get('help_category_id')),
            )->helperText('Unique inside its category.'),
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("title.$l")->label('Title')->required($l === 'fa'),
                Textarea::make("summary.$l")->label('Summary')->rows(2),
                RichEditor::make("content.$l")->label('Article')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.slug')->label('Category')->sortable(),
                TextColumn::make('sort')->sortable(),
                TextColumn::make('title')->limit(60),
                TextColumn::make('helpful_yes')->label('Helpful')->numeric(),
                TextColumn::make('helpful_no')->label('Not helpful')->numeric(),
                IconColumn::make('is_published')->boolean(),
            ])
            ->filters([SelectFilter::make('help_category_id')->relationship('category', 'slug')->label('Category')])
            ->defaultSort('sort')
            ->recordActions([
                EditAction::make()->slideOver()->modalWidth('5xl')
                    ->mutateRecordDataUsing(fn (array $data, Model $record) => Translations::fill($record, $data)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageHelpArticles::route('/')];
    }
}
