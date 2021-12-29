<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        return response()->json([
            'cars' => CarResource::collection(auth()->user()->cars)
        ]);
    }

    public function store(CarRequest $request)
    {
        $car = auth()->user()->cars()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => new CarResource($car)
        ], 201);
    }

    public function update(Car $car, CarRequest $request)
    {
        $this->authorize('update', $car);
        $car->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => new CarResource($car)
        ]);
    }

    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);
        $car->delete();
        return response()->json([
            'msg' => 'ok'
        ]);
    }
}
