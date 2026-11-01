<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\ManagePosts;
use App\Filament\Support\Translations;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
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

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Used in the address: /fa/blog/{slug}'),
            TextInput::make('icon')->required()->default('newspaper')->helperText('A Lucide icon name for the card. See lucide.dev/icons'),
            DateTimePicker::make('published_at')->seconds(false)->default(now())
                ->helperText('A date in the future schedules the post: it appears on that day.'),
            Toggle::make('is_published')->helperText('Off keeps it as a draft, whatever the date.'),
            Translations::tabs(fn (string $l) => [
                TextInput::make("title.$l")->label('Title')->required($l === 'fa'),
                Textarea::make("excerpt.$l")->label('Excerpt')->rows(2)->required($l === 'fa')
                    ->helperText('One or two sentences, shown on the listing and in search results.'),
                RichEditor::make("content.$l")->label('Article')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('published_at')->label('Date')->dateTime('Y-m-d H:i')->sortable(),
                TextColumn::make('title')->limit(60),
                TextColumn::make('slug')->color('gray'),
                IconColumn::make('is_published')->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
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
        return ['index' => ManagePosts::route('/')];
    }
}
