<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $emailValidator = $this->validateEmail();
        $addressValidator = $this->validateAddress();

        if ($addressValidator->fails() || $emailValidator->fails()) {
            return response()->json(['message' => 'Failed',
                $emailValidator->messages(),
                $addressValidator->messages()], 400);
        }
        $user = USER::where('email', $emailValidator->validated()['email'])->first();
        if ($user->address) {
            return response()->json(['message' => 'User has Address Already', 'data' => null], 400);
        };
        $address = new Address($addressValidator->validated());
        if ($user->address()->save($address)) {
            return response()->json(['message' => 'Success address', 'data' => $address], 201);
        }
        response()->json(['message' => 'Failed', 'data' => null], 400);
    }

    public function validateEmail()
    {
        return Validator::make(request()->all(), [
            'email' => 'required|string|email|max:255'
        ]);
    }

    public function validateAddress()
    {
        return Validator::make(request()->all(), [
            'country' => 'required|string|min:1|max:5',
            'zipcode' => 'required|string|min:1|max:6'
        ]);
    }
    public function show(Address $address)
    {
        return response()->json(['message'=>'', 'data'=> $address], 200);
    }

    public function show_user(Address $address)
    {
        return response()->json(['message'=>'', 'data'=> $address->user], 200);
    }
}
