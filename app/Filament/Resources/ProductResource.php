<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Add Product')
                    ->schema([
                        TextInput::make('product_id')->required()
                            ->label('Product Code')
                            ->integer()
                            ->autofocus()
                            ->maxLength(11),
                        TextInput::make('product_name')->required()
                            ->label('Product Name')
                            ->maxLength(255),
                        Select::make('category')->options([
                            'Makanan' => 'Makanan', 'Minuman' => 'Minuman', 'Kesehatan' => 'Kesehatan', 'Elektronik' => 'Elektronik', 'Fashion' => 'Fashion', 'Perawatan Tubuh' => 'Perawatan Tubuh', 'Lainnya' => 'Lainnya'
                        ])->required(),
                        TextInput::make('price')->required()
                            ->label('Product Price')
                            ->numeric()
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->prefix('Rp. '),
                        TextInput::make('stock')->required()
                            ->label('Product Stock')
                            ->integer()
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_id')->sortable()
                    ->searchable()
                    ->label('Product Code'),
                TextColumn::make('product_name')->sortable()
                    ->searchable()
                    ->label('Product Name'),
                TextColumn::make('category')->sortable()
                    ->searchable()
                    ->label('Product Category'),
                TextColumn::make('price')->sortable()
                    ->label('Product Price')->currency('IDR'),
                TextColumn::make('stock')->sortable(),
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
