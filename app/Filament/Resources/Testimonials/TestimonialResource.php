<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Filament\Support\Translations;
use App\Models\Testimonial;
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

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 45;

    protected static ?string $modelLabel = 'Testimonial';

    /** Testimonials sent from the website wait here, unpublished, until someone approves them. */
    public static function getNavigationBadge(): ?string
    {
        $pending = Testimonial::query()->where('source', 'website')->where('is_published', false)->count();

        return $pending ? (string) $pending : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sort')->numeric()->default(0),
            Toggle::make('is_published')->default(true)
                ->helperText('Testimonials are not shown on the public site yet (SITE_SHOW_TESTIMONIALS).'),
            FileUpload::make('attachments')->label('Photos, videos and voice notes')->multiple()->disk('public')->directory('testimonials')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4', 'video/webm', 'video/quicktime', 'audio/mpeg', 'audio/mp4', 'audio/x-m4a', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/webm'])
                ->maxSize(20480)->maxFiles(5)->openable()->downloadable()->columnSpanFull(),
            TextInput::make('contact')->label('Contact (sent from the website, never shown)')->disabled()->visible(fn (?Model $record) => filled($record?->contact)),
            Translations::tabs(fn (string $l) => [
                TextInput::make("name.$l")->label('Name'),
                TextInput::make("role.$l")->label('Role or city'),
                Textarea::make("quote.$l")->label('Testimonial')->rows(4),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')->sortable(),
                TextColumn::make('name'),
                TextColumn::make('source')->badge(),
                TextColumn::make('quote')->limit(70),
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
        return ['index' => ManageTestimonials::route('/')];
    }
}
