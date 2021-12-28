<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        return response()->json([
            'cars' => auth()->user()->cars
        ]);
    }

    public function show(Car $car)
    {
        $this->authorize('view', $car);
        return response()->json([
            'car' => $car
        ]);
    }

    public function store(CarRequest $request)
    {
        auth()->user()->cars()->create($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => $car
        ], 201);
    }

    public function update(Car $car, CarRequest $request)
    {
        $this->authorize('update', $car);
        $car->update($request->validated());
        return response()->json([
            'msg' => 'ok',
            'car' => $car
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
