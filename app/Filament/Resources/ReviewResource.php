<?php

namespace App\Filament\Resources;

use App\Models\Review;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Filament\Resources\ReviewResource\Pages;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Модерация';
    protected static ?string $label = 'Отзыв';
    protected static ?string $pluralLabel = 'Отзывы';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Информация об отзыве')
                            ->schema([
                                Forms\Components\Select::make('dealership_id')
                                    ->relationship('dealership', 'title')
                                    ->required()
                                    ->disabled(),
                                Forms\Components\TextInput::make('rating')
                                    ->numeric()
                                    ->prefix('★')
                                    ->disabled(),
                                Forms\Components\Textarea::make('text')
                                    ->rows(5)
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Textarea::make('pros')
                                            ->label('Плюсы')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('cons')
                                            ->label('Минусы')
                                            ->rows(3),
                                    ]),
                            ])->columnSpan(2),

                        Forms\Components\Section::make('Автор и Мета')
                            ->schema([
                                Forms\Components\TextInput::make('author_name')->label('Имя автора')->disabled(),
                                Forms\Components\TextInput::make('author_email')->label('Email')->disabled(),
                                Forms\Components\TextInput::make('author_phone')->label('Телефон')->disabled(),
                                Forms\Components\TextInput::make('ip_address')->label('IP Адрес')->disabled(),
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Дата создания')
                                    ->content(fn ($record) => $record?->created_at->format('d.m.Y H:i')),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'На проверке',
                                        'approved' => 'Одобрен',
                                        'rejected' => 'Отклонен',
                                    ])
                                    ->required()
                                    ->native(false),
                                Forms\Components\Textarea::make('admin_comment')
                                    ->label('Комментарий модератора')
                                    ->placeholder('Причина отклонения или заметка...'),
                            ])->columnSpan(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Новый',
                        'approved' => 'Одобрен',
                        'rejected' => 'Отклонен',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('dealership.title')
                    ->label('Автосалон')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author_name')
                    ->label('Автор')
                    ->searchable()
                    ->description(fn (Review $record): string => $record->author_email ?? 'Email не указан'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Оценка')
                    ->icon('heroicon-m-star')
                    ->color('warning')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('text')
                    ->label('Текст отзыва')
                    ->limit(50)
                    ->tooltip(fn (Review $record): string => $record->text),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'На проверке',
                        'approved' => 'Одобрены',
                        'rejected' => 'Отклонены',
                    ]),
                SelectFilter::make('dealership_id')
                    ->label('Автосалон')
                    ->relationship('dealership', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('rating')
                    ->label('Оценка')
                    ->options([
                        '1' => '1 звезда',
                        '2' => '2 звезды',
                        '3' => '3 звезды',
                        '4' => '4 звезды',
                        '5' => '5 звезд',
                    ]),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('С'),
                        Forms\Components\DatePicker::make('until')->label('По'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    })
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\Action::make('approve')
                        ->label('Одобрить')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn (Review $record) => $record->update(['status' => 'approved']))
                        ->visible(fn (Review $record) => $record->status !== 'approved'),
                    Actions\Action::make('reject')
                        ->label('Отклонить')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn (Review $record) => $record->update(['status' => 'rejected']))
                        ->visible(fn (Review $record) => $record->status !== 'rejected'),
                    Actions\Action::make('reset')
                        ->label('Вернуть на проверку')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(fn (Review $record) => $record->update(['status' => 'pending']))
                        ->visible(fn (Review $record) => $record->status !== 'pending'),
                    Actions\EditAction::make(),
                    Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\BulkAction::make('approve_selected')
                        ->label('Одобрить выбранные')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'approved'])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
