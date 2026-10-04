<?php

namespace App\Filament\Resources\NewsCategories\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('title')->label('Başlık'),
                        TextEntry::make('slug')->label('Slug'),
                        ImageEntry::make('image_path')
                            ->label('Görsel')
                            ->disk('public')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->label('Kısa açıklama')
                            ->columnSpanFull(),
                        TextEntry::make('content')
                            ->label('İçerik')
                            ->html()
                            ->columnSpanFull(),
                    ]),
                Section::make('SEO')
                    ->schema([
                        TextEntry::make('seo_title')->label('SEO başlık'),
                        TextEntry::make('seo_description')->label('SEO açıklama'),
                    ]),
            ]);
    }
}
