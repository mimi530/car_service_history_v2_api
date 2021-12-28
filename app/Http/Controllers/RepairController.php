<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairRequest;
use App\Models\Car;
use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Car $car)
    {
        return response()->json(['repairs' => $car->repairs]);
    }

    public function show(Car $car, Repair $repair)
    {
        return response()->json(['repair' => $car->repairs->find($repair)]);
    }

    public function store(RepairRequest $request, Car $car)
    {
        $repair = $car->repairs()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => $repair
        ], 201);
    }

    public function update(RepairRequest $request, Car $car, Repair $repair)
    {
        $repair->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'repair' => $repair
        ]);
    }

    public function destroy(Car $car, Repair $repair)
    {
        $repair->delete();
    }
}
