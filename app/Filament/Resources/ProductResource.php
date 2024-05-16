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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'product_name';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product')
                    ->schema([
                        TextInput::make('product_id')->required()
                            ->label('Product Code')
                            ->integer()
                            ->default(fn () => (new static)->generateRandomInteger())
                            ->autofocus()
                            ->maxLength(11),
                        TextInput::make('product_name')->required()
                            ->label('Product Name')
                            ->maxLength(255),
                        Select::make('category')->options([
                            'Makanan' => 'Makanan', 'Minuman' => 'Minuman', 'Kesehatan' => 'Kesehatan', 'Elektronik' => 'Elektronik', 'Fashion' => 'Fashion', 'Perawatan Tubuh' => 'Perawatan Tubuh', 'Lainnya' => 'Lainnya'
                        ])->required(),
                        FileUpload::make('image')->required()
                            ->label('Product Image')
                            ->directory('uploads/images')
                            ->dehydrateStateUsing(function ($state) {
                                if (is_string($state)) {
                                    return json_encode([
                                        'path' => $state,
                                        'name' => pathinfo($state, PATHINFO_FILENAME),
                                        'extension' => pathinfo($state, PATHINFO_EXTENSION),
                                        'size' => Storage::disk('public')->size($state),
                                    ]);
                                }
                                return $state;
                            })
                            ->multiple()
                            ->imageEditor()
                            ->image(),
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

    protected function generateRandomInteger($length = 11)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
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
                ImageColumn::make('image')->label('Product Image'),
                TextColumn::make('price')->sortable()
                    ->label('Product Price')->currency('IDR'),
                TextColumn::make('stock')->sortable(),
                TextColumn::make('created_at')->sortable()
                    ->label('Created At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->sortable()
                    ->label('Updated At')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
