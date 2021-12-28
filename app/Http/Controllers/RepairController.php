<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairRequest;
use App\Models\Car;
use App\Models\Repair;

class RepairController extends Controller
{
    public function index(Car $car)
    {
        $this->authorize('viewAny', $car);
        return response()->json(['repairs' => $car->repairs]);
    }

    public function store(RepairRequest $request, Car $car)
    {
        $this->authorize('create', [Repair::class, $car]);
        $repair = $car->repairs()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => $repair
        ], 201);
    }

    public function update(RepairRequest $request, Car $car, Repair $repair)
    {
        $this->authorize('update', [$car, $repair]);
        $repair->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => $repair
        ]);
    }

    public function destroy(Car $car, Repair $repair)
    {
        $this->authorize('delete', [$car, $repair]);
        $repair->delete();
    }
}
