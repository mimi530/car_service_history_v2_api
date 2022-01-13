<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairRequest;
use App\Http\Resources\RepairResource;
use App\Models\Car;
use App\Models\Repair;

class RepairController extends Controller
{
    public function index(Car $car)
    {
        $this->authorize('viewAny', [Repair::class, $car]);
        return response()->json([
            'repairs' => RepairResource::collection($car->repairs->sortByDesc('date'))
        ]);
    }

    public function store(RepairRequest $request, Car $car)
    {
        $this->authorize('create', [Repair::class, $car]);
        $repair = $car->repairs()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => new RepairResource($repair)
        ], 201);
    }

    public function update(RepairRequest $request, Car $car, Repair $repair)
    {
        $this->authorize('update', [$car, $repair]);
        $repair->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => new RepairResource($repair)
        ]);
    }

    public function destroy(Car $car, Repair $repair)
    {
        $this->authorize('delete', [$car, $repair]);
        $repair->delete();
        return response()->json([
            'msg' => 'ok',
        ]);
    }
}
