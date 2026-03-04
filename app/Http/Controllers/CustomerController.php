<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Customer::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $perPage = $request->per_page ?? 10;

        return response()->json(
            $query->paginate($perPage)
        );
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:customers,email',
            'gender'     => 'nullable|string|max:20',
            'ip_address' => 'nullable|ip',
            'company'    => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:255',
            'title'      => 'nullable|string|max:255',
            'website'    => 'nullable|url'
        ]);

        $customer = Customer::create($data);

        return response()->json([
            'message' => 'Customer created',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name'  => 'sometimes|string|max:100',
            'email'      => "sometimes|email|unique:customers,email,{$customer->id}",
            'gender'     => 'sometimes|string|max:20',
            'ip_address' => 'sometimes|ip',
            'company'    => 'sometimes|string|max:255',
            'city'       => 'sometimes|string|max:255',
            'title'      => 'sometimes|string|max:255',
            'website'    => 'sometimes|url'
        ]);

        $customer->update($data);

        return response()->json($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }
}
