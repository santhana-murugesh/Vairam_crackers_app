<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboPackPackingResource\Pages;
use App\Filament\Resources\ComboPackPackingResource\RelationManagers;
use App\Models\ComboPackOrder;
use App\Models\ComboPackDispatch;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Customer;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ButtonAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use App\Filament\Resources\Dispatch;

class ComboPackPackingResource extends Resource
{
    protected static ?string $model = ComboPackOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'ComboPack Orders';

    protected static ?string $navigationLabel = 'ComboPack Packing';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'packing')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('customer_id')
                ->label('Order_Id')
                ->options(ComboPackOrder::all()->pluck('id', 'id'))
                ->required()
                ->searchable(),


                Forms\Components\TextInput::make('net_total')->numeric()
                ->required()
                ->maxLength(255),

                Forms\Components\TextInput::make('sub_total')->numeric()
                ->required()
                ->maxLength(255),

                Select::make('status')
                ->options([
                    'placed' => 'placed',
                    'dispatched' => 'dispatched',
                    'packing' => 'packing',
                   
                ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

        ->query(function () {

            $query = ComboPackOrder::query();

            return $query->whereNot('status', 'dispatched')->latest('created_at');
        })

        ->query(function () {

            $query = ComboPackOrder::query();

            return $query->where('status', 'packing')->latest('created_at');
        })
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('customer.mobile_number')->label('mobile_number')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('address.address')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('net_total')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d-m-y H:i:s')
                ->sortable()
                ->toggleable()
                ->searchable(),

            ])
            ->filters([
                Filter::make('Created_at')
                ->form([
                    DatePicker::make('From_date'),
                    DatePicker::make('To_date'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['From_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['To_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                ->icon('heroicon-o-document-arrow-down')
                ->url(
                    fn (ComboPackOrder $record): string => route('admin.combo.download', ['id' => $record->id]),
                    shouldOpenInNewTab: true
                ),

                ButtonAction::make('dispatched')
                ->label('Dispatch')
                ->form([
                    Select::make('combo_pack_order_id')
                        ->label('Order_Id')
                        ->options(ComboPackOrder::all()->pluck('id', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('LR_number')->label('LR Number'),
                    TextInput::make('transport')->label('Transport'),
                    DatePicker::make('book_date')->label('Book Date')->default(now()),
                    DatePicker::make('delivery_date')->label('Delivery Date')->default(now()),
                    ])

                    ->action(function (array $data, ComboPackOrder $record) {
                        ComboPackDispatch::create([
                            'combo_pack_order_id'  => $record->id,
                            'LR_number' => $data['LR_number'],
                            'transport' => $data['transport'],
                            'book_date' => $data['book_date'],
                            'delivery_date' => $data['delivery_date'],
                        ]);
    
                        $record->update(['status' => 'dispatched']);
                    }),

                // Tables\Actions\ReplicateAction::make()
                // ->action(function (ComboPackOrder $order) {
                //     $orderToClone = ComboPackOrder::with('customer', 'address', 'items.combo_pack')->find($order->id);
                    
                //     if ($orderToClone) {
                //         $cloneOrder = $orderToClone->replicate();
                //         $cloneOrder->created_at = now();
                //         $cloneOrder->save();
                        
                //         if ($orderToClone->customer) {
                //             $cloneCustomer = $orderToClone->customer->replicate();
                //             $cloneCustomer->save();
                //             $cloneOrder->customer()->associate($cloneCustomer);
                //         }
                        
                //         if ($orderToClone->address) {
                //             $cloneAddress = $orderToClone->address->replicate();
                //             $cloneAddress->save();
                //             $cloneOrder->address()->associate($cloneAddress);
                //         }
                        
                //         $cloneItems = collect();
                        
                //         foreach ($orderToClone->items as $itemToClone) {
                //             if ($itemToClone) {
                //                 $clonedItem = $itemToClone->replicate();
                //                 $clonedItem->save();
                //                 $cloneItems->push($clonedItem);
                //             }
                //         }
                        
                //         $cloneOrder->save();
                        
                //         if ($cloneItems->isNotEmpty()) {
                //             $cloneOrder->items()->saveMany($cloneItems);
                //         }
                        
                //         return $cloneOrder->id;
                //     }
                    
                //     return null;
                // })

            ])
            ->bulkActions([
               
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\BulkAction::make('Download Pdf')
                    // ->action(function (Collection $records) {
                    //     $recordIds = $records->pluck('id')->toArray();

                    //     if (count($recordIds) === 0) {
                    //         return response()->json(['message' => 'No records selected.']);
                    //     }

                    //     $apiUrl = route('family.bulk-download', ['order_ids' => $recordIds]); // Replace with your API URL

                    //     return redirect($apiUrl);
                    // })
                    // ->icon('heroicon-o-document-arrow-down'),
            
                
                // ExportBulkAction::make('Inventory')
                // ->action(function (Collection $records) {
                //     $recordIds = $records->pluck('id')->toArray();
                //     if (count($recordIds) === 0) {
                //         return response()->json(['message' => 'No records selected.']);
                //     }

                //     $apiUrl = route('combo.SelectedOrders', ['combo_pack_ids' => $recordIds]);

                //     return redirect($apiUrl);
                // })
                // ->icon('heroicon-o-document-arrow-down')
                // ->label('Inventory'),

                BulkAction::make('Bulk Dispatch')
                    ->form([
                        TextInput::make('LR_number')->label('LR Number'),
                        TextInput::make('transport')->label('Transport'),
                        DatePicker::make('book_date')->label('Book Date')->default(now()),
                        DatePicker::make('delivery_date')->label('Delivery Date')->default(now()),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $recordIds = $records->pluck('id')->toArray();
                        if (count($recordIds) === 0) {
                            return response()->json(['message' => 'No records selected.']);
                        }
                        foreach ($records as $record) {
                            comboPackDispatch::create([
                                'order_id' => $record->id,
                                'LR_number' => $data['LR_number'],
                                'transport' => $data['transport'],
                                'book_date' => $data['book_date'],
                                'delivery_date' => $data['delivery_date'],
                            ]);
                        }
                        $records->each(function ($record) {
                            $record->update(['status' => 'dispatched']);
                        });
                    }),

                    Tables\Actions\BulkAction::make('Bulk cancel')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        CombopackOrder::whereIn('id', $recordIds)->update(['status' => 'cancel']);
                    })->icon('heroicon-o-x-circle'),

                Tables\Actions\BulkAction::make('Bulk Refund')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        CombopackOrder::whereIn('id', $recordIds)->update(['status' => 'refund']);
                    })->icon('heroicon-o-arrow-uturn-right'),

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
            'index' => Pages\ListComboPackPackings::route('/'),
            'create' => Pages\CreateComboPackPacking::route('/create'),
            'edit' => Pages\EditComboPackPacking::route('/{record}/edit'),
        ];
    }    
}