<?php

namespace App\Filament\Resources;

use App\Models\News;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Filament\Resources\NewsResource\Pages;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';
    protected static string | \UnitEnum | null $navigationGroup = 'Контент';
    protected static ?string $label = 'Новость';
    protected static ?string $pluralLabel = 'Новости';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(News::class, 'slug', ignoreRecord: true),
                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('news')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120), // 5MB
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Черновик',
                                'published' => 'Опубликован',
                            ])->default('draft')->required(),
                        Forms\Components\TextInput::make('seo_title'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликован',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
