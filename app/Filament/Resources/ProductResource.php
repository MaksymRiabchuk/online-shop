<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\ImagesRelationManager;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Main info')->collapsible()->schema([
                    TextInput::make('name')
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                            if (!$get('is_slug_changed_manually') && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        })
                        ->live(true)
                        ->required(),
                    TextInput::make('slug')
                        ->afterStateUpdated(function (Set $set) {
                            $set('is_slug_changed_manually', true);
                        })->unique(ignoreRecord: true)
                        ->required(),
                    Hidden::make('is_slug_changed_manually')
                        ->default(false)
                        ->dehydrated(false),
                    Forms\Components\Select::make('category_id')->relationship('category', 'name')
                        ->preload()->searchable()->required()->label('Category'),
                    Forms\Components\Select::make('brand_id')->relationship('brand', 'name')
                        ->preload()->searchable()->required()->label('Brand'),
                    Forms\Components\TextInput::make('vendor_code')->unique(ignoreRecord:true)->required()->label('Vendor code')->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail) {
                            if (strlen($value) !== 14) {
                                $fail('The :attribute is must be 14 characters long.');
                            }
                        },
                    ]),
                    Forms\Components\TextInput::make('price')->numeric()->required()->label('Price')->columnSpan(1),
                    Forms\Components\RichEditor::make('description')->required()->label('Description')->columnSpan(2),
                    Forms\Components\RichEditor::make('shipping')->required()->label('Shipping description')->columnSpan(2),
                    Forms\Components\RichEditor::make('guarantee')->required()->label('Guarantee description')->columnSpan(2),
                ])->columns(2),
                Forms\Components\Section::make('Images for product')->collapsible()->schema([
                    Repeater::make('Images')
                        ->relationship('images')
                        ->reorderable()
                        ->required()
                        ->grid(2)
                        ->schema([
                            FileUpload::make('image')
                                ->required()
                                ->directory('/products')
                                ->imageEditor()
                                ->label('Image')
                                ->columnSpan(1),
                            Forms\Components\Group::make()->schema([
                                Forms\Components\Toggle::make('is_main')
                                    ->label('Main')
                            ]),
                        ])
                        ->orderColumn('order'),
                ])->columns(1),
                Forms\Components\Section::make('Features for product')->collapsible()->schema([
                    Repeater::make('Features')
                        ->required()
                        ->grid(3)
                        ->relationship('features')
                        ->schema([
                            TextInput::make('name')->required(),
                        ])
                ])->columns(1),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('price')->label('Price')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('vendor_code')->label('Vendor Code')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('description')->label('Description')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('shipping')->label('Shipping description')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('guarantee')->label('Guarantee description')->searchable()->sortable()->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('rate')->label('Rate')->searchable()->sortable(),
            ])
//            ->filters([
//                Filter::make('category_id')
//                    ->form([
//                        Forms\Components\Select::make('category_id')
//                            ->label('Категорія')
//                            ->options(function () {
//                                return Category::query()
//                                    ->whereNotNull('name')
//                                    ->select('name', 'id')
//                                    ->distinct()
//                                    ->orderBy('name', 'asc')
//                                    ->pluck('name', 'id')
//                                    ->toArray();
//                            })
//                            ->searchable()
//                            ->placeholder('Choose category')
//                    ])
//                    ->query(function (Builder $query, array $data): Builder {
//                        return $query->when(
//                            $data['category_id'],
//                            fn($query, $np) => $query->where('category_id', $np)
//                        );
//                    }),
//                Filter::make('brand_id')
//                    ->form([
//                        Forms\Components\Select::make('brand_id')
//                            ->label('Brand')
//                            ->options(function () {
//                                return Brand::query()
//                                    ->whereNotNull('name')
//                                    ->select('name', 'id')
//                                    ->distinct()
//                                    ->orderBy('name', 'asc')
//                                    ->pluck('name', 'id')
//                                    ->toArray();
//                            })
//                            ->searchable()
//                            ->placeholder('Choose brand')
//                    ])
//                    ->query(function (Builder $query, array $data): Builder {
//                        return $query->when(
//                            $data['brand_id'],
//                            fn($query, $np) => $query->where('brand_id', $np)
//                        );
//                    }),
//            ])
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
            ImagesRelationManager::class,
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
