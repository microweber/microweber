<?php

namespace MicroweberPackages\Customer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Invoice\Conversation;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Country\Models\Country;
use MicroweberPackages\Invoice\Group;
use MicroweberPackages\Customer\Http\Requests;
use MicroweberPackages\Invoice\Notifications\CustomerAdded;
use Illuminate\Support\Facades\Hash;
use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Customer\Models\Address;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Order\Models\Order;

class CustomersController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;

        $customers = Customer::applyFilters($request->only([
            'search',
            'orderByField',
            'orderBy'
        ]))
            //->whereCompany($request->header('company'))
            ->select('customers.*',
            //  DB::raw('sum(due_amount) as due_amount')
            )
            ->orderBy('id', 'desc')
            ->groupBy('customers.id')
            //->leftJoin('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->paginate($limit);

        return $this->view('customer::admin.customers.index', [
            'customers' => $customers
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\CustomerRequest $request)
    {
        $verifyEmail = Customer::where('email', $request->email)->first();

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->currency_id = $request->currency_id;
        $customer->company_id = $request->header('company');
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        //  $customer->company_name = $request->company_name;
        //    $customer->contact_name = $request->contact_name;
        //   $customer->website = $request->website;
        //   $customer->enable_portal = $request->enable_portal;
        //  $customer->role = 'customer';
        // $customer->password = Hash::make($request->password);
        $customer->active = 1;
        $customer->save();

        if ($request->addresses) {
            foreach ($request->addresses as $address) {

                if (!isset($address["country_id"])) {
                    $address["country_id"] = 0;
                }

                $newAddress = new Address();
                $newAddress->name = $address["name"];
                $newAddress->address_street_1 = $address["address_street_1"];
                // $newAddress->address_street_2 = $address["address_street_2"];
                $newAddress->city = $address["city"];
                $newAddress->state = $address["state"];
                $newAddress->country_id = (int)$address["country_id"];
                $newAddress->zip = $address["zip"];

                if (isset($address["phone"])) {
                    $newAddress->phone = $address["phone"];
                }

                $newAddress->type = $address["type"];
                $newAddress->customer_id = $customer->id;
                $newAddress->save();
                $customer->addresses()->save($newAddress);
            }
        }

        return redirect(route('admin.customers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = Customer::with([
            'orders',
            'billingAddress',
            'shippingAddress',
            'billingAddress.country',
            'shippingAddress.country',
        ])->find($id);

        return redirect(route('admin.customers.index'))->with('status', 'Customer is created success.');
    }


    public function create()
    {
        $countries = Country::all();
        $currencies = Currency::all();

        if (empty($countries)) {
            countries_list();
            $countries = Country::all();
        }

        return $this->view('customer::admin.customers.edit', [
            'customer' => false,
            'countries' => $countries,
            'currencies' => $currencies,
            'currency' => false
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $customer = Customer::with('billingAddress', 'shippingAddress')->findOrFail($id);
        $currency = $customer->currency;
        $currencies = Currency::all();
        $orders = Order::where('customer_id', $customer->id)->get();

        return $this->view('customer::admin.customers.edit', [
            'countries' => Country::all(),
            'customer' => $customer,
            'orders' => $orders,
            'currencies' => $currencies,
            'currency' => $currency
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Requests\CustomerRequest $request)
    {
        $customer = Customer::find($id);

        if ($request->email != null) {
            $verifyEmail = Customer::where('email', $request->email)->first();

            if ($verifyEmail) {
                if ($verifyEmail->id !== $customer->id) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Email already in use'
                    ]);
                }
            }
        }

        if ($request->has('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->name = $request->name;
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->currency_id = $request->currency_id;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        //    $customer->company_name = $request->company_name;
        // $customer->contact_name = $request->contact_name;
        //  $customer->website_url = $request->website;
        // $customer->enable_portal = $request->enable_portal;
        $customer->save();

        $customer->addresses()->delete();
        if ($request->addresses) {
            foreach ($request->addresses as $address) {

                if (!isset($address["type"])) {
                    continue;
                }

                $newAddress = $customer->addresses()->firstOrNew(['type' => $address["type"]]);
                $newAddress->name = $address["name"];
                $newAddress->address_street_1 = $address["address_street_1"];
                if (isset($address["address_street_2"])) {
                    $newAddress->address_street_2 = $address["address_street_2"];
                }
                if (isset($address["phone"])) {
                    $newAddress->phone = $address["phone"];
                }
                if (isset($address["company_name"])) {
                    $newAddress->company_name = $address["company_name"];
                }
                if (isset($address["company_id"])) {
                    $newAddress->company_id = $address["company_id"];
                }
                if (isset($address["company_vat"])) {
                    $newAddress->company_vat = $address["company_vat"];
                }
                if (isset($address["company_vat_registered"])) {
                    $newAddress->company_vat_registered = $address["company_vat_registered"];
                }
                $newAddress->city = $address["city"];
                $newAddress->state = $address["state"];
                $newAddress->country_id = $address["country_id"];
                $newAddress->zip = $address["zip"];
                $newAddress->type = $address["type"];
                $newAddress->customer_id = $customer->id;
                $newAddress->save();
            }
        }

        return redirect(route('admin.customers.edit', $customer->id));
    }

    /**
     * Remove the specified Customer along side all his/her resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        $findCustomer = Customer::where('id', $id)->first();
        if ($findCustomer) {
            $findCustomer->delete();
        }

        return redirect(route('admin.customers.index'));
    }


    /**
     * Remove a list of Customers along side all their resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                try {
                    Customer::find($id)->delete();
                } catch (\Exception $e) {
                    //
                }
            }
        }

        return redirect(route('admin.customers.index'));
    }
}
