<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
     public function index()
    {
        $payment = Payment:: //with('users','category')
         included()
        ->filter()
        ->sort()
        ->getOrPaginate();
        return response()->json($payment);
        
    }

    public function store(Request $request)
    {
               $request->validate([
            'monto' => 'required|string|max:255',
            'metodo_pago' => 'required|string|max:255',
            'estado' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            ]);

        $payment = Payment::create($request->all());
        return response()->json($payment, 201);
    }

   public function show($id)
    {
        $lesson = Lesson::with(['course','user'])->findOrFail($id);
        return response()->json($lesson);
    }

    public function update(Request $request, Payment $payment)
    {
          $request->validate([
            'monto' => 'required|string|max:255',
            'metodo_pago' => 'required|string|max:255',
            'estado' => 'required|integer|max:255',
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',         
        ]);

        $payment->update($request->all());
        return response()->json($payment);
        
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Deleted successfully']);
        
    }
}
