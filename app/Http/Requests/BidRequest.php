<?php

namespace App\Http\Requests;

use App\Models\Bid;
use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'price' => [
                function ($attribute, $value, $fail) {
                    // Validate that price has exactly two decimal points
                    $highestBid =  Bid::latest()->value('price');
                    if (!isset($value)){
                        $fail('The bid price is required!');
                    }
                    else if (!preg_match('/^\d+(\.\d{1,2})?$/', $value)) {
                        $fail('The price format is invalid.');
                    }
                    else if ($value <= $highestBid){
                        $fail('The bid price cannot lower than ' . $highestBid + 1);
                    }
                }
            ],
        ];
    }
}
