<?php

namespace App\Filament\Resources\NewsCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Temel bilgiler')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                                if (filled($get('slug'))) {
                                    return;
                                }

                                $set('slug', Str::slug((string) $state));
                            }),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(160)
                            ->alphaDash()
                            ->unique(ignoreRecord: true),
                        FileUpload::make('image_path')
                            ->label('Görsel')
                            ->image()
                            ->disk('public')
                            ->directory('news-categories')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Kısa açıklama')
                            ->rows(3)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('İçerik')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('SEO')
                    ->columns(1)
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('SEO başlık')
                            ->maxLength(160),
                        Textarea::make('seo_description')
                            ->label('SEO açıklama')
                            ->rows(3)
                            ->maxLength(320),
                    ]),
            ]);
    }
}
