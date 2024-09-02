<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Filament\Resources\ProductResource\Pages;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->readOnly(),

                Forms\Components\Select::make('category_id')
                    ->relationship(name: 'category', titleAttribute: 'name'),
                Forms\Components\Textarea::make('short_description'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->collection('images')
                    ->multiple()
                    ->reorderable(),
                Forms\Components\MarkdownEditor::make('description')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'heading',
                        'italic',
                        'link',
                        'redo',
                        'table',
                        'undo',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('images'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make('exportAllAsJson')
                    ->label(__('Export All'))
                    ->action(function (Collection $records) {
                        $archive = new \ZipArchive;
                        $archive->open('products.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                        $json = [];

                        foreach ($records as $record) {
                            $attributes = $record->only(
                                ['id', 'category_id', 'slug', 'name', 'short_description', 'description',]
                            );

                            $medias = $record->getMedia('*');

                            foreach ($medias as $media) {
                                $path = Str::of($media->getUrl())->after('storage');
                                $attributes['images'][] = '/assets/img/products'.$path;
                                $archive->addFile($media->getPath(), $path);
                            }

                            $json[] = $attributes;
                        }

                        $content = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                        $archive->addFromString('products.json', $content);

                        $archive->close();

                        return response()->download('products.zip');
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
