<?php

namespace App\Http\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getCustomers($request)
    {
        $customers = Customer::query();


        if ($request->filled('any')) {
            $customers = $customers->where('name', 'like', '%' . $request->any . '%')
                ->orWhere('phone', 'like', '%' . $request->any . '%')
                ->orWhereHas('customerAddress', function ($q) use ($request) {
                    $q->where('address', 'like', '%' . $request->any . '%');
                    $q->orWhere('city', 'like', '%' . $request->any . '%');
                });
        }

        if ($request->filled('name')) $customers = $customers->where('name', 'like', '%' . $request->name . '%');

        if ($request->filled('phone')) $customers = $customers->where('phone', 'like', '%' . $request->phone . '%');

        if ($request->filled('address')) {
            $customers = $customers->whereHas('customerAddress', function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
        }
        $customers = $customers->with('customerAddress')->with(['customerPurchase' => function ($query) {
            $query->select('customer_id', 'method', DB::raw('max(amount) as max_amount'))
                ->groupBy('method');
        }]);

        return $customers->paginate($request->per_page);
    }
}
