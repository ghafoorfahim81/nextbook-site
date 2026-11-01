<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Resources\Faqs\Pages\ManageFaqs;
use App\Filament\Support\Translations;
use App\Models\Faq;
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

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Translations::tabs(fn (string $l) => [
                TextInput::make("question.$l")->label('Question')->required($l === 'fa'),
                Textarea::make("answer.$l")->label('Answer')->rows(4)->required($l === 'fa'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->sortable(),
                TextColumn::make('question')->limit(70),
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
        return ['index' => ManageFaqs::route('/')];
    }
}
