<?php

namespace App\Filament\Resources;
use App\Filament\Resources\ComboPackDispatchResource\Pages;
use App\Filament\Resources\ComboPackDispatchResource\RelationManagers;
use App\Models\ComboPackDispatch;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\Order;
use App\Models\ComboPackOrder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Tables\Actions\BulkAction;


class ComboPackDispatchResource extends Resource
{
    protected static ?string $model = ComboPackDispatch::class;
    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';
    protected static ?string $navigationGroup = 'ComboPack Orders';
    protected static ?string $navigationLabel = 'ComboPack Dispatch';
    protected static ?int $navigationSort = 4;
    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dispatch Details')
                ->schema([
                    Select::make('combo_pack_order_id')
                        ->label('Order_Id')
                        ->options(ComboPackOrder::all()->pluck('id', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('LR_number')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('transport')
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('book_date'),
                    DateTimePicker::make('delivery_date'),
                ])->columns(2),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
       
            ->columns([
                Tables\Columns\TextColumn::make('combo_pack_order_id')->label('Order_Id')->searchable()->toggleable()->sortable(),
                Tables\Columns\TextColumn::make('LR_number')->label('LR Number')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('transport')->label('Transport')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('book_date')->label('Book_date')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('delivery_date')->label('Delivery_date')->searchable()->toggleable(),
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
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // ExportBulkAction::make()->exports([
                //     ExcelExport::make()->fromTable()->askForWriterType(),
                // ]),
                Tables\Actions\DeleteBulkAction::make(),
                // ExportBulkAction::make('inventory')
                //     ->action(function (Collection $records) {
                //         $recordIds = $records->pluck('id')->toArray();
                //         if (count($recordIds) === 0) {
                //             return response()->json(['message' => 'No records selected.']);
                //         }
                //         $apiUrl = route('combo.SelectedOrders', ['combo_pack_ids' => $recordIds]);
                //         return redirect($apiUrl);
                //     })
                //     ->icon('heroicon-o-document-arrow-down')
                //     ->label('Inventory'),
                Tables\Actions\BulkAction::make('Bulk Refund')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        ComboPackOrder::whereIn('id', $recordIds)->update(['status' => 'refund']);
                    })->icon('heroicon-o-arrow-uturn-right'),
                Tables\Actions\BulkAction::make('Bulk Cancel')
                    ->action(function (Collection $records) {
                        $recordIds = $records->pluck('id')->toArray();
                        ComboPackOrder::whereIn('id', $recordIds)->update(['status' => 'cancel']);
                    })->icon('heroicon-o-x-circle'),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ])->paginated([10, 30, 40, 50, 75, 100 => 'all'])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListComboPackDispatches::route('/'),
            'create' => Pages\CreateComboPackDispatch::route('/create'),
            'edit' => Pages\EditComboPackDispatch::route('/{record}/edit'),
        ];
    }
}