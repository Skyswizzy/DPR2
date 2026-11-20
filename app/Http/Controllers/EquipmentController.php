<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD for pickleball equipment records.
 * Registered as a resource route, so each method maps to one standard action.
 */
class EquipmentController extends Controller
{
    /** List all equipment, with optional search by name. */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $equipment = Equipment::query()
            ->when($search !== '', fn ($q) => $q->where('equipment_name', 'like', "%{$search}%"))
            ->orderBy('equipment_name')
            ->paginate(10)
            ->withQueryString();

        // Totals ignore the search so the header always describes the whole inventory.
        $counts = [
            'total' => Equipment::count(),
            'available' => Equipment::where('availability_status', 'available')->count(),
            'unavailable' => Equipment::where('availability_status', 'unavailable')->count(),
        ];

        return view('equipment.index', compact('equipment', 'counts', 'search'));
    }

    public function create(): View
    {
        return view('equipment.create');
    }

    public function store(EquipmentRequest $request): RedirectResponse
    {
        // Business logic: user_id is set here from the logged-in user, never from the form.
        // Creating through the relationship fills user_id automatically.
        $equipment = $request->user()->equipment()->create($request->validated());

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('status', "“{$equipment->equipment_name}” was added to the inventory.");
    }

    public function show(Equipment $equipment): View
    {
        $equipment->load('user');

        return view('equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(EquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        // user_id is not fillable, so the original creator is never overwritten.
        $equipment->update($request->validated());

        return redirect()
            ->route('equipment.show', $equipment)
            ->with('status', 'Changes saved.');
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        $name = $equipment->equipment_name;
        $equipment->delete();

        return redirect()
            ->route('equipment.index')
            ->with('status', "“{$name}” was deleted.");
    }
}
