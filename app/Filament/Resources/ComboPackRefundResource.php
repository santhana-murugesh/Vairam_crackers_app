<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboPackRefundResource\Pages;
use App\Filament\Resources\ComboPackRefundResource\RelationManagers;
use App\Models\ComboPackOrder;
use App\Models\ComboPackRefund;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

// class ComboPackRefundResource extends Resource
// {
//     protected static ?string $model = ComboPackOrder::class;
//     protected static ?string $navigationGroup = 'ComboPack Orders';
//     protected static ?string $navigationLabel = 'ComboPack Refund';
//     protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-right';
//     protected static ?int $navigationSort = 6;
//     public static function getNavigationBadge(): ?string
//     {
//         return static::getModel()::where('status', '=', 'refund')->count();
//     }
//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Select::make('customer_id')
//                 ->label('Order_Id')
//                 ->options(ComboPackOrder::all()->pluck('id', 'id'))
//                 ->required()
//                 ->searchable(),
//                 TextInput::make('net_total')->numeric()
//                 ->required()
//                 ->maxLength(255),
//                 TextInput::make('sub_total')->numeric()
//                 ->required()
//                 ->maxLength(255),
//                 Select::make('status')
//                 ->options([
//                     'placed' => 'placed',
//                     'cancelled' => 'cancelled',
//                     'refund' => 'refund',
//                 ])->required(),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//         ->query(function () {
//             $query = ComboPackOrder::query();
//             return $query->where('status', 'refund');
//         })
//             ->columns([
//                 Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
//                 Tables\Columns\TextColumn::make('customer.mobile_number')->toggleable()->searchable(),
//                 Tables\Columns\TextColumn::make('customer.whatsapp_number')->toggleable()->searchable(),
//                 Tables\Columns\TextColumn::make('address.delivery_location')->toggleable()->searchable(),
//                 Tables\Columns\TextColumn::make('net_total')->searchable(),
//                 Tables\Columns\TextColumn::make('status')->label('Status')->toggleable(),
//                 Tables\Columns\TextColumn::make('created_at')
//                 ->dateTime('d-m-y H:i:s')
//                 ->sortable()
//                 ->toggleable()
//                 ->searchable(),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//                 Tables\Actions\DeleteAction::make()
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                     Tables\Actions\BulkAction::make('Bulk Place')
//                     ->action(function (Collection $records) {
//                         $recordIds = $records->pluck('id')->toArray();
//                         ComboPackOrder::whereIn('id', $recordIds)->update(['status' => 'placed']);
//                     })->icon('heroicon-o-x-circle'),
//                 ]),
//             ])->paginated([10, 30, 40, 50, 75, 100 => 'all']);
//     }

//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListComboPackRefunds::route('/'),
//             'create' => Pages\CreateComboPackRefund::route('/create'),
//             'edit' => Pages\EditComboPackRefund::route('/{record}/edit'),
//         ];
//     }
// }
