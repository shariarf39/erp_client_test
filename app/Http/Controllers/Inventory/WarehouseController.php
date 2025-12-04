<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Employee;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::with('manager')->orderBy('warehouse_name')->paginate(20);
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        return view('inventory.warehouses.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_code' => 'required|unique:warehouses',
            'warehouse_name' => 'required|max:255',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'phone' => 'nullable|max:20',
            'manager_id' => 'nullable|exists:employees,id',
            'is_active' => 'boolean',
        ]);

        Warehouse::create($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['manager', 'stocks.item']);
        return view('inventory.warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();
        return view('inventory.warehouses.edit', compact('warehouse', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'warehouse_code' => 'required|unique:warehouses,warehouse_code,' . $warehouse->id,
            'warehouse_name' => 'required|max:255',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'phone' => 'nullable|max:20',
            'manager_id' => 'nullable|exists:employees,id',
            'is_active' => 'boolean',
        ]);

        try {
            $warehouse->update($validated);

            return redirect()->route('inventory.warehouses.show', $warehouse)
                ->with('success', 'Warehouse updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            // Check if warehouse has stock records
            if ($warehouse->stocks()->count() > 0) {
                return redirect()->route('inventory.warehouses.index')
                    ->with('error', 'Cannot delete warehouse with stock records. Please transfer or remove stock first.');
            }

            $warehouse->delete();

            return redirect()->route('inventory.warehouses.index')
                ->with('success', 'Warehouse deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('inventory.warehouses.index')
                ->with('error', 'Error deleting warehouse: ' . $e->getMessage());
        }
    }
}
