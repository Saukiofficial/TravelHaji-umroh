<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Artikel')
                    ->description('Atur judul, slug, kategori, dan status artikel.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Artikel')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(table: 'articles', column: 'slug', ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Panduan Umroh')
                            ->maxLength(255),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('published')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Thumbnail Artikel')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('articles')
                            ->disk('public')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048),
                    ]),

                Section::make('Isi Artikel')
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Ringkasan Artikel')
                            ->rows(3)
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Konten Artikel')
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }
}