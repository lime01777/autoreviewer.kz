<?php

namespace App\Filament\Resources;

use App\Models\Dealership;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\DealershipResource\Pages;
use Illuminate\Support\Str;

class DealershipResource extends Resource
{
    protected static ?string $model = Dealership::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home-modern';
    protected static string | \UnitEnum | null $navigationGroup = 'Каталог';
    protected static ?string $label = 'Автосалон';
    protected static ?string $pluralLabel = 'Автосалоны';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Основная информация')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(Dealership::class, 'slug', ignoreRecord: true),
                                Forms\Components\Select::make('categories')
                                    ->multiple()
                                    ->relationship('categories', 'title')
                                    ->preload(),
                                Forms\Components\Textarea::make('short_description')->required(),
                                Forms\Components\RichEditor::make('full_description')->required()->columnSpanFull(),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Контакты и адрес')
                            ->schema([
                                Forms\Components\TextInput::make('city')->required(),
                                Forms\Components\TextInput::make('district'),
                                Forms\Components\TextInput::make('address')->required(),
                                Forms\Components\TextInput::make('phone'),
                                Forms\Components\TextInput::make('whatsapp'),
                                Forms\Components\TextInput::make('website'),
                                Forms\Components\TextInput::make('instagram'),
                                Forms\Components\TextInput::make('working_hours'),
                                Forms\Components\TextInput::make('latitude')->numeric(),
                                Forms\Components\TextInput::make('longitude')->numeric(),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Медиа и SEO')
                            ->schema([
                                Forms\Components\FileUpload::make('logo')
                                    ->image()
                                    ->directory('dealerships/logos')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(2048), // 2MB
                                Forms\Components\FileUpload::make('cover_image')
                                    ->image()
                                    ->directory('dealerships/covers')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120), // 5MB
                                Forms\Components\Section::make('SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('seo_title'),
                                        Forms\Components\Textarea::make('seo_description'),
                                    ])
                            ]),
                        Forms\Components\Tabs\Tab::make('Статус')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Черновик',
                                        'published' => 'Опубликован',
                                        'hidden' => 'Скрыт',
                                    ])->default('draft')->required(),
                                Forms\Components\Toggle::make('is_featured')->label('Рекомендуемый'),
                            ])
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликован',
                        'hidden' => 'Скрыт',
                    ]),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('rating_avg')->label('Рейтинг')->sortable(),
                Tables\Columns\TextColumn::make('reviews_count')->label('Отзывы')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Черновик',
                        'published' => 'Опубликован',
                        'hidden' => 'Скрыт',
                    ]),
                Tables\Filters\SelectFilter::make('city')
                    ->label('Город')
                    ->options(fn() => Dealership::distinct()->pluck('city', 'city')->toArray()),
                Tables\Filters\SelectFilter::make('categories')
                    ->multiple()
                    ->relationship('categories', 'title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDealerships::route('/'),
            'create' => Pages\CreateDealership::route('/create'),
            'edit' => Pages\EditDealership::route('/{record}/edit'),
        ];
    }
}
