<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Aktualności';
    protected static ?string $navigationGroup = 'Treści';
    protected static ?string $modelLabel = 'aktualność';
    protected static ?string $pluralModelLabel = 'aktualności';
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string { return 'aktualność'; }
    public static function getPluralModelLabel(): string { return 'aktualności'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Podstawowe informacje')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Tytuł')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                            if (! $get('slug')) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->maxLength(255)
                        ->unique(BlogPost::class, 'slug', ignoreRecord: true)
                        ->helperText('Automatycznie generowany z tytułu'),
                    Forms\Components\Textarea::make('excerpt')
                        ->label('Krótki opis')
                        ->maxLength(500)
                        ->helperText('Jeśli pozostawisz puste, zostanie automatycznie wygenerowane z treści')
                        ->columnSpanFull(),
                ])->columns(2),
            Forms\Components\Section::make('Treść')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->label('Treść artykułu')
                        ->required()
                        ->toolbarButtons([
                            'blockquote','bold','bulletList','codeBlock','h2','h3','italic','link','orderedList','redo','strike','underline','undo',
                        ])
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Zdjęcie główne')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->label('Zdjęcie główne')
                        ->image()
                        ->directory('blog')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth(800)
                        ->imageResizeTargetHeight(450)
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Ustawienia publikacji')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(['active' => 'Aktywny','inactive' => 'Nieaktywny',])
                        ->default('active')
                        ->required(),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Post wyróżniony')
                        ->helperText('Wyróżnione posty mogą być wyświetlane na stronie głównej'),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Data publikacji')
                        ->default(now())
                        ->helperText('Post będzie widoczny od tej daty'),
                ])->columns(3),
            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\KeyValue::make('seo_meta')
                        ->label('Meta dane SEO')
                        ->keyLabel('Klucz')
                        ->valueLabel('Wartość')
                        ->default([
                            'meta_title' => '',
                            'meta_description' => '',
                            'meta_keywords' => '',
                        ])
                        ->helperText('Meta title, description, keywords dla SEO')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('featured_image')
                ->label('Zdjęcie')
                ->square()
                ->size(60),
            Tables\Columns\TextColumn::make('title')
                ->label('Tytuł')
                ->searchable()
                ->sortable()
                ->limit(50)
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();
                    return strlen($state) > 50 ? $state : null;
                }),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors(['success' => 'active','danger' => 'inactive',])
                ->formatStateUsing(fn ($state): string => match ($state) {
                    'active' => 'Aktywny',
                    'inactive' => 'Nieaktywny',
                    default => is_string($state) ? $state : (string) $state,
                }),
            Tables\Columns\IconColumn::make('is_featured')
                ->label('Wyróżniony')
                ->boolean()
                ->trueIcon('heroicon-o-star')
                ->falseIcon('heroicon-o-star')
                ->trueColor('warning')
                ->falseColor('gray'),
            Tables\Columns\TextColumn::make('published_at')
                ->label('Data publikacji')
                ->dateTime('d.m.Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Utworzono')
                ->dateTime('d.m.Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Zaktualizowano')
                ->dateTime('d.m.Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options(['active' => 'Aktywny','inactive' => 'Nieaktywny',]),
            Tables\Filters\Filter::make('is_featured')
                ->label('Tylko wyróżnione')
                ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
            Tables\Filters\Filter::make('published')
                ->label('Opublikowane')
                ->query(fn (Builder $query): Builder => $query->published()),
            Tables\Filters\TrashedFilter::make(),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()->label('Podgląd'),
            Tables\Actions\EditAction::make()->label('Edytuj'),
            Tables\Actions\DeleteAction::make()->label('Usuń'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label('Usuń zaznaczone'),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}
