<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BidRequest;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    public function create(BidRequest $request)
    {
        $user = User::findOrFail($request['user_id']);

        // Calculate the full name
        $fullName = $user->first_name . ' ' . $user->last_name;

        $formattedPrice = number_format($request['price'], 2, '.', '');

        $responseData = [
            'message' => 'Success',
            'data' => [
                'full_name' => $fullName,
                'price' => $formattedPrice,
            ],
        ];

        $bid = new Bid();
        $bid->user_id = $request['user_id'];
        $bid->price = $request['price'];

        // Save the bid to the database
        $bid->save();

        return response()->json($responseData, 201);
    }
}
