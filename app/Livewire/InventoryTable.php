<?php

namespace App\Livewire;

use App\Models\StoreInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class InventoryTable extends PowerGridComponent
{
    public string $tableName = 'inventory-table-a5yyb5-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return StoreInventory::query()
            ->leftJoin('product_catalogs', 'store_inventories.product_id', '=', 'product_catalogs.id')
            ->leftJoin('peripherals', 'store_inventories.peripheral_id', '=', 'peripherals.id')
            ->select(
                'store_inventories.*',
                DB::raw("COALESCE(product_catalogs.name, peripherals.name) as device_name")
            );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('device_name')
            ->add('device_type')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Device', 'device_name')
                ->sortable()
                ->searchable(),

            Column::make('Device Type', 'device_type')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        $devices = DB::table('product_catalogs')
            ->select('id', 'name', DB::raw("'product' as type"))
            ->whereIn('id', StoreInventory::groupBy('product_id')->pluck('product_id'))
            ->union(
                DB::table('peripherals')
                    ->select('id', 'name', DB::raw("'peripheral' as type"))
                    ->whereIn('id', StoreInventory::groupBy('peripheral_id')->pluck('peripheral_id'))
            )
            ->orderBy('name')
            ->get()
            ->toArray();

        $devices = array_map(function ($device) {
            return (array) $device;
        }, $devices);

        return [
            Filter::select('device_name', 'product_id')
                ->dataSource($devices)
                ->optionValue('id')
                ->optionLabel('name'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(StoreInventory $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
