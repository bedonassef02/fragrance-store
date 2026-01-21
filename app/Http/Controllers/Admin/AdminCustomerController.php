<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'latest');

        $customers = $this->customerService->getAllCustomers($search, $sort);

        return view('admin.customers.index', compact('customers', 'search', 'sort'));
    }

    public function show($email)
    {
        // Decode email in case it was encoded in URL
        $email = urldecode($email);
        
        $data = $this->customerService->getCustomerDetails($email);

        return view('admin.customers.show', [
            'customer' => $data['stats'],
            'orders' => $data['orders']
        ]);
    }
}
