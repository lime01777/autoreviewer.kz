<?php

namespace App\Filament\Resources;

use App\Models\Dealership;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\DealershipResource\Pages;
use Illuminate\Support\Str;

class DealershipResource extends Resource
{
    protected static ?string $model = Dealership::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home-modern';
    protected static string | \UnitEnum | null $navigationGroup = 'Каталог';
    protected static ?string $label = 'Автосалон';
    protected static ?string $pluralLabel = 'Автосалоны';

    public static function getNavigationBadge(): ?string
    {
        return (string) Dealership::where('data_status', 'needs_review')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FORM
    // ─────────────────────────────────────────────────────────────────────────
    public static function form(Schema $schema): Schema
    {
        $typeOptions = [
            'official_dealer' => 'Официальный дилер',
            'dealership'      => 'Автосалон',
            'used'            => 'Авто с пробегом',
            'shop'            => 'Автомагазин',
            'service'         => 'Сервис',
            'parts'           => 'Запчасти',
        ];

        $brandOptions = [
            'Toyota', 'Hyundai', 'Kia', 'Chevrolet', 'Chery', 'Haval', 'Geely',
            'JAC', 'Jetour', 'BYD', 'Renault', 'Skoda', 'Volkswagen',
            'BMW', 'Mercedes-Benz', 'Lexus', 'Mitsubishi', 'Nissan',
            'Audi', 'Land Rover', 'Porsche', 'Ford', 'Honda',
        ];

        $cityOptions = [
            'Алматы', 'Астана', 'Шымкент', 'Костанай', 'Караганда',
            'Актобе', 'Павлодар', 'Атырау', 'Актау', 'Усть-Каменогорск',
            'Кокшетау', 'Тараз', 'Кызылорда', 'Уральск', 'Петропавловск',
        ];

        return $schema->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([

                    // ── Tab 1: Основная информация ─────────────────────────
                    Forms\Components\Tabs\Tab::make('Основная')
                        ->icon('heroicon-o-building-storefront')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Название')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('legal_name')
                                    ->label('Юридическое название')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(Dealership::class, 'slug', ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\Select::make('type')
                                    ->label('Тип')
                                    ->options($typeOptions)
                                    ->default('dealership')
                                    ->required(),

                                Forms\Components\Select::make('brand')
                                    ->label('Основной бренд')
                                    ->options(array_combine($brandOptions, $brandOptions))
                                    ->searchable()
                                    ->nullable(),

                                Forms\Components\Toggle::make('is_official_dealer')
                                    ->label('Официальный дилер')
                                    ->inline(false),
                            ]),

                            Forms\Components\TagsInput::make('brands')
                                ->label('Все представленные бренды')
                                ->placeholder('Toyota, Lexus, ...')
                                ->suggestions($brandOptions)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('categories')
                                ->label('Категории')
                                ->multiple()
                                ->relationship('categories', 'title')
                                ->preload()
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('short_description')
                                ->label('Краткое описание')
                                ->required()
                                ->rows(2)
                                ->columnSpanFull(),

                            Forms\Components\RichEditor::make('full_description')
                                ->label('Полное описание')
                                ->required()
                                ->columnSpanFull(),
                        ]),

                    // ── Tab 2: Контакты и адрес ───────────────────────────
                    Forms\Components\Tabs\Tab::make('Контакты')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Select::make('city')
                                    ->label('Город')
                                    ->options(array_combine($cityOptions, $cityOptions))
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('district')
                                    ->label('Район'),

                                Forms\Components\TextInput::make('address')
                                    ->label('Адрес')
                                    ->required()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Телефон')
                                    ->tel()
                                    ->placeholder('+7 XXX XXX XX XX'),

                                Forms\Components\TextInput::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->tel()
                                    ->placeholder('+7 XXX XXX XX XX'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),

                                Forms\Components\TextInput::make('website')
                                    ->label('Веб-сайт')
                                    ->url()
                                    ->placeholder('https://'),

                                Forms\Components\TextInput::make('instagram')
                                    ->label('Instagram')
                                    ->placeholder('@username'),

                                Forms\Components\TextInput::make('working_hours')
                                    ->label('График работы')
                                    ->placeholder('Пн-Пт: 09:00-20:00, Сб: 10:00-18:00')
                                    ->helperText('Формат: Пн-Пт: 09:00-20:00 или JSON для сложных расписаний')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('latitude')
                                    ->label('Широта (lat)')
                                    ->numeric()
                                    ->step(0.000001),

                                Forms\Components\TextInput::make('longitude')
                                    ->label('Долгота (lng)')
                                    ->numeric()
                                    ->step(0.000001),
                            ]),
                        ]),

                    // ── Tab 3: Медиа и SEO ────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Медиа / SEO')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\FileUpload::make('logo')
                                    ->label('Логотип')
                                    ->image()
                                    ->directory('dealerships/logos')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('80'),

                                Forms\Components\FileUpload::make('cover_image')
                                    ->label('Обложка')
                                    ->image()
                                    ->directory('dealerships/covers')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('80'),
                            ]),

                            Forms\Components\Section::make('SEO')->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(70)
                                    ->helperText('Рекомендуется до 70 символов'),
                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->helperText('Рекомендуется до 160 символов'),
                            ])->columns(1),
                        ]),

                    // ── Tab 4: Источник и верификация ─────────────────────
                    Forms\Components\Tabs\Tab::make('Источник')
                        ->icon('heroicon-o-shield-check')
                        ->badge(fn ($record) => $record?->data_status === 'needs_review' ? '!' : null)
                        ->badgeColor('warning')
                        ->schema([
                            Forms\Components\Placeholder::make('source_hint')
                                ->label('')
                                ->content('⚠️ Реальные данные должны иметь источник и дату проверки. Не добавляйте записи без source_url.')
                                ->columnSpanFull(),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('source_name')
                                    ->label('Название источника')
                                    ->placeholder('Toyota.kz, Astana Motors, ручной ввод...')
                                    ->helperText('Откуда взяты данные'),

                                Forms\Components\TextInput::make('source_url')
                                    ->label('Ссылка на источник')
                                    ->url()
                                    ->placeholder('https://toyota.kz/dealers/...')
                                    ->helperText('Прямая ссылка на страницу с данными'),

                                Forms\Components\DateTimePicker::make('source_checked_at')
                                    ->label('Дата последней проверки')
                                    ->default(now()),

                                Forms\Components\Select::make('data_status')
                                    ->label('Статус данных')
                                    ->options([
                                        'draft'        => '📝 Черновик',
                                        'needs_review' => '⚠️ Требует проверки',
                                        'verified'     => '✅ Проверено',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->native(false),

                                Forms\Components\Toggle::make('data_verified')
                                    ->label('Данные верифицированы модератором')
                                    ->inline(false),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Рекомендуемый (featured)')
                                    ->inline(false),
                            ]),

                            Forms\Components\Textarea::make('notes')
                                ->label('Заметки модератора')
                                ->rows(3)
                                ->placeholder('Внутренние примечания, не показываются на сайте...')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('status')
                                ->label('Статус публикации')
                                ->options([
                                    'draft'     => '📝 Черновик',
                                    'published' => '✅ Опубликован',
                                    'hidden'    => '🙈 Скрыт',
                                ])
                                ->default('draft')
                                ->required()
                                ->native(false),
                        ]),

                ])->columnSpanFull(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(asset('images/placeholders/logo.svg')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->legal_name)
                    ->wrap(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Город')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('brand')
                    ->label('Бренд')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'official_dealer' => 'Офиц. дилер',
                        'dealership'      => 'Автосалон',
                        'used'            => 'С пробегом',
                        'shop'            => 'Магазин',
                        'service'         => 'Сервис',
                        'parts'           => 'Запчасти',
                        default           => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'official_dealer' => 'success',
                        'dealership'      => 'info',
                        'used'            => 'gray',
                        'service'         => 'warning',
                        default           => 'gray',
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('rating_avg')
                    ->label('★')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) : '—')
                    ->color(fn ($state) => $state >= 4 ? 'success' : ($state >= 3 ? 'warning' : 'danger')),

                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Отзывы')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?: '0'),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'draft'     => 'Черновик',
                        'published' => 'Опубликован',
                        'hidden'    => 'Скрыт',
                    ]),

                Tables\Columns\TextColumn::make('data_status')
                    ->label('Данные')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'verified'     => '✅ Проверено',
                        'needs_review' => '⚠️ На проверке',
                        default        => '📝 Черновик',
                    })
                    ->color(fn ($state) => match($state) {
                        'verified'     => 'success',
                        'needs_review' => 'warning',
                        default        => 'gray',
                    }),

                Tables\Columns\TextColumn::make('source_name')
                    ->label('Источник')
                    ->toggleable()
                    ->limit(20)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('source_url')
                    ->label('Ссылка на источник')
                    ->toggleable()
                    ->limit(30)
                    ->url(fn ($record) => $record->source_url)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->visible(fn ($record) => !empty($record->source_url)),

                Tables\Columns\TextColumn::make('source_checked_at')
                    ->label('Проверено')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable()
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()

            // ── FILTERS ────────────────────────────────────────────────────
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('Город')
                    ->options(fn () => Dealership::distinct()->orderBy('city')->pluck('city', 'city')->filter()->toArray()),

                Tables\Filters\SelectFilter::make('brand')
                    ->label('Бренд')
                    ->options(fn () => Dealership::distinct()->orderBy('brand')->pluck('brand', 'brand')->filter()->toArray()),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'official_dealer' => 'Официальный дилер',
                        'dealership'      => 'Автосалон',
                        'used'            => 'Авто с пробегом',
                        'shop'            => 'Автомагазин',
                        'service'         => 'Сервис',
                        'parts'           => 'Запчасти',
                    ]),

                Tables\Filters\SelectFilter::make('data_status')
                    ->label('Статус данных')
                    ->options([
                        'draft'        => 'Черновик',
                        'needs_review' => 'Требует проверки',
                        'verified'     => 'Проверено',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус публикации')
                    ->options([
                        'draft'     => 'Черновик',
                        'published' => 'Опубликован',
                        'hidden'    => 'Скрыт',
                    ]),

                Tables\Filters\TernaryFilter::make('is_official_dealer')
                    ->label('Официальный дилер')
                    ->trueLabel('Только официальные')
                    ->falseLabel('Не официальные'),

                Tables\Filters\TernaryFilter::make('data_verified')
                    ->label('Верификация данных')
                    ->trueLabel('Верифицированы')
                    ->falseLabel('Не верифицированы'),

                Tables\Filters\Filter::make('no_source')
                    ->label('Без источника')
                    ->query(fn ($query) => $query->whereNull('source_url')->orWhere('source_url', '')),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)

            // ── ROW ACTIONS ────────────────────────────────────────────────
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make(),
                    Actions\Action::make('verify')
                        ->label('Подтвердить')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->action(fn (Dealership $record) => $record->update([
                            'data_verified'    => true,
                            'data_status'      => 'verified',
                            'source_checked_at'=> now(),
                        ]))
                        ->visible(fn ($record) => $record->data_status !== 'verified'),

                    Actions\Action::make('publish')
                        ->label('Опубликовать')
                        ->icon('heroicon-o-globe-alt')
                        ->color('info')
                        ->action(fn (Dealership $record) => $record->update(['status' => 'published']))
                        ->visible(fn ($record) => $record->status !== 'published'),

                    Actions\Action::make('hide')
                        ->label('Скрыть')
                        ->icon('heroicon-o-eye-slash')
                        ->color('gray')
                        ->action(fn (Dealership $record) => $record->update(['status' => 'hidden']))
                        ->visible(fn ($record) => $record->status !== 'hidden'),

                    Actions\DeleteAction::make(),
                ])->tooltip('Действия'),
            ])

            // ── BULK ACTIONS ───────────────────────────────────────────────
            ->bulkActions([
                Actions\BulkActionGroup::make([

                    Actions\BulkAction::make('bulk_verify')
                        ->label('Подтвердить данные')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update([
                            'data_verified'    => true,
                            'data_status'      => 'verified',
                            'source_checked_at'=> now(),
                        ])),

                    Actions\BulkAction::make('bulk_needs_review')
                        ->label('Отправить на проверку')
                        ->icon('heroicon-o-flag')
                        ->color('warning')
                        ->action(fn (Collection $records) => $records->each->update([
                            'data_status'  => 'needs_review',
                            'data_verified'=> false,
                        ])),

                    Actions\BulkAction::make('bulk_publish')
                        ->label('Опубликовать выбранные')
                        ->icon('heroicon-o-globe-alt')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'published'])),

                    Actions\BulkAction::make('bulk_hide')
                        ->label('Скрыть выбранные')
                        ->icon('heroicon-o-eye-slash')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'hidden'])),

                    Actions\DeleteBulkAction::make(),
                ]),
            ])

            // ── SEARCH ────────────────────────────────────────────────────
            ->searchPlaceholder('Поиск по названию, городу, бренду, телефону...')

            // ── EMPTY STATE ───────────────────────────────────────────────
            ->emptyStateHeading('Автосалоны не найдены')
            ->emptyStateDescription('Используйте импорт CSV или добавьте вручную.')
            ->emptyStateIcon('heroicon-o-home-modern');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDealerships::route('/'),
            'create' => Pages\CreateDealership::route('/create'),
            'edit'   => Pages\EditDealership::route('/{record}/edit'),
        ];
    }
}
