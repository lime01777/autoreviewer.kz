<?php

namespace App\Filament\Resources;

use App\Models\Banner;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Filament\Resources\BannerResource\Pages;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';
    protected static string | \UnitEnum | null $navigationGroup = 'Реклама';
    protected static ?string $label = 'Баннер';
    protected static ?string $pluralLabel = 'Баннеры';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Детали баннера')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('position')
                            ->options([
                                'main_top' => 'Главная (Верх)',
                                'main_sidebar' => 'Главная (Сайдбар)',
                                'dealership_top' => 'Карточка автосалона (Верх)',
                                'dealership_sidebar' => 'Карточка автосалона (Сайдбар)',
                                'catalog_top' => 'Каталог (Верх)',
                                'catalog_sidebar' => 'Каталог (Сайдбар)',
                                'news_sidebar' => 'Новости (Сайдбар)',
                                'footer' => 'Футер',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('banners')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(3072) // 3MB
                            ->required(),
                        Forms\Components\TextInput::make('link')
                            ->url()
                            ->label('Ссылка перехода'),
                    ])->columns(2),

                Forms\Components\Section::make('Расписание и Статус')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Активен',
                                'inactive' => 'Неактивен',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Дата начала показа'),
                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('Дата завершения показа'),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->options([
                        'main_top' => 'Главная (Верх)',
                        'main_sidebar' => 'Главная (Сайдбар)',
                        'dealership_top' => 'Карточка автосалона (Верх)',
                        'dealership_sidebar' => 'Карточка автосалона (Сайдбар)',
                        'catalog_top' => 'Каталог (Верх)',
                        'catalog_sidebar' => 'Каталог (Сайдбар)',
                        'news_sidebar' => 'Новости (Сайдбар)',
                        'footer' => 'Футер',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ManageBanners::route('/'),
        ];
    }
}
