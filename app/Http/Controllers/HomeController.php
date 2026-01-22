<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class HomeController extends Controller
{
  protected $paymentService;

  public function __construct(PaymentInterface $paymentService)
  {
      $this->paymentService = $paymentService;
  }

   
    public function index()
    {
        return view('home');
    }

    
    public function payment(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'amount' => [
                    'required',
                    'numeric',
                    'min:1',
                    'max:999.99',
                    'regex:/^\d+(\.\d{1,2})?$/'
                ],
                'discount' => [
                    'required',
                    'numeric',
                    'min:0',
                    'regex:/^\d+(\.\d{1,2})?$/'
                ],
                'tax' => [
                    'required',
                    'numeric',
                    'min:1',
                    'max:99.99',
                    'regex:/^\d+(\.\d{1,2})?$/'
                ]
            ]);


            if ($request->has('amount') && $request->has('discount')) {
                $amount = (float) $request->amount;
                $discount = (float) $request->discount;
                
                if ($discount > $amount) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('discount', 'The discount cannot be greater than the amount.');
                    });
                }
            }

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }

     
            $validated = $validator->validated();

    
            $result = $this->paymentService->pay(
                (float) $validated['amount'],
                (float) $validated['discount'],
                (float) $validated['tax']
            );
            dd($result);
            return redirect()->route('home')->with('success', true)->with('result', $result);

        } catch (Exception $e) {
          
            return redirect()->route('home')->with('error', $e->getMessage())->withInput();
        }
    }
}

