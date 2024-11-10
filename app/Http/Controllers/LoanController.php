<?php

namespace App\Http\Controllers;

use App\Models\additionalpayments;
use App\Models\loans;
use App\Models\monthlypayments;
use App\Models\notebooks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Corrected import
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class LoanController extends Controller
{
    public function getLoanRequest()
    {
        $users = User::all(); // Fetch all users or apply necessary filters
        return view('Admin.Loan.Loan', compact('users'));
    }

    public function postLoanRequest(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'number' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);



        // Get user input from the request
        $userInput = $request->input('number');
        $userId = $request->input('user_id');



        if (loans::find($userId)) {
            return redirect()->back()
                ->withErrors(['user_id' => 'کاربر انتخاب شده  باقی است نمی توانید قرضه بگیرد.'])
                ->withInput();
        }
        // Sum the total monthly and additional payments
        $totalMonthlyPayments = monthlypayments::where('user_id', $userId)->sum('number');
        $additionalPayments = additionalpayments::where('user_id', $userId)->sum('number');
        $total = $totalMonthlyPayments + $additionalPayments;
        var_dump($total);

        $totalMonthlypayemts = monthlypayments::sum('number');
        $additionalPayments = additionalpayments::sum('number');
        $notebooksPayment = notebooks::sum('number');
        $totalPayments = $totalMonthlypayemts + $additionalPayments + $notebooksPayment;

        $percentageOfTotal = $totalPayments * 0.25;

        if ($userInput > $percentageOfTotal) {
            return redirect()->back()
                ->withErrors(['number' => 'مقدار درخواستی زیاد تر از 25 فیصد کل دخل است'])
                ->withInput();
        }


        // Check if the total matches the user input
        if ($total == $userInput) {
            // Use a transaction to ensure data integrity
            DB::transaction(function () use ($userId) {
                // Delete records for the provided user ID
                monthlypayments::where('user_id', $userId)->delete();
                additionalpayments::where('user_id', $userId)->delete();
            });

            return redirect()->route('admin.alluser')->with('success', 'User payment deleted successfully!');
        } else {
            return redirect()->back()
                ->withErrors(['number' => 'مقدار وارده مساوی نیست با پول کاربر'])
                ->withInput();
        }
    }
    public function getMultiLoanRequest()
    {
        $users = User::all(); // Fetch all users or apply necessary filters
        return view('Admin.MultiLoanRequest.MultiLoanRequest', compact('users'));
    }

    public function postMultiLoanRequest(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'user_id_1' => 'required|exists:users,id',
            'user_id_2' => 'required|exists:users,id',
            'user_id_3' => 'required|exists:users,id',
        ]);

        $userInput =    $request->input('number');
        $userIds = [
            $request->input('user_id_1'),
            $request->input('user_id_2'),
            $request->input('user_id_3'),
        ];

        $existingLoans = loans::whereIn('user_id', $userIds)
            ->where(function ($query) {
                $query->whereNotNull('number')
                    ->orWhereNotNull('user_id')
                    ->orWhereNotNull('sponsor_1')
                    ->orWhereNotNull('sponsor_2');
            })
            ->exists();

        if ($existingLoans) {
            return redirect()->back()
                ->withErrors(['user_id' => 'کاربر انتخاب شده باقی است نمی توانید قرضه بگیرد.'])
                ->withInput();
        }

        if (count($userIds) !== count(array_unique($userIds))) {
            return back()->withErrors(['message' => 'ضامنین باید منحصر به فرد باشند.']);
        }

        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $latestMonthlyPayments = [];
        foreach ($userIds as $userId) {
            $latestMonthlyPayments[$userId] = monthlypayments::where('user_id', $userId)->orderBy('created_at', 'desc')->first();
        }

        $noPaymentUser = null;
        foreach ($latestMonthlyPayments as $userId => $payment) {
            if ($payment === null) {
                $noPaymentUser = $users[$userId]->name;
                break;
            }
        }

        if ($noPaymentUser) {
            return back()->withErrors(['message' => 'ضامن ' . $noPaymentUser . ' هیچ پرداختی ثبت نکرده است.']);
        }

        $dates = [];
        foreach ($latestMonthlyPayments as $userId => $payment) {
            $dates[$userId] = Carbon::parse($payment->created_at)->format('Y-m');
        }

        if (count(array_unique($dates)) > 1) {
            $unequalDateUsers = [];
            foreach ($dates as $userId => $date) {
                $unequalDateUsers[] = $users[$userId]->name . ' (' . $date . ')';
            }
            return back()->withErrors(['message' => 'تاریخ پرداختی ماهانه ضامنین باید یکی باشد: ' . implode(', ', $unequalDateUsers)]);
        }


        $totalMonthlypayemts = monthlypayments::sum('number');
        $additionalPayments = additionalpayments::sum('number');
        $notebooksPayment = notebooks::sum('number');


        $totals = $totalMonthlypayemts + $additionalPayments + $notebooksPayment;
        (int) $loans = loans::sum('number');
        (int) $totalsMinusLoan = $totals - $loans;

        $percentageOfTotal = $totalsMinusLoan * 0.25;

        if ($userInput > $percentageOfTotal) {
            return redirect()->back()
                ->withErrors(['number' => 'مقدار درخواستی زیاد تر از 25 فیصد کل دخل است'])
                ->withInput();
        } else if ($userInput < $percentageOfTotal) {
            DB::table('loans')->insert([
                'number' => $userInput,
                'user_id' => $userIds[0],
                'sponsor_1' => $userIds[1],
                'sponsor_2' => $userIds[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return redirect()->route('admin.alluser')->with('success', 'User paid successfully!');
    }


    public function getLoans(Request $request)
    {
        // Fetch loans with optional search functionality
        $search = $request->input('search');
        $loans = loans::with('user')->when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('number', 'LIKE', "%$search%")
                    ->orWhere('amount', 'LIKE', "%$search%")
                    ->orWhere('sponsor_1', 'LIKE', "%$search%")
                    ->orWhere('sponsor_2', 'LIKE', "%$search%")
                    ->orWhere('user_id', 'LIKE', "%$search%");
            });
        })->paginate(10);

        // Pass the loans to the view
        return view('Admin.Loan.AllLoans', compact('loans'));
    }





    public function getSingleLoan($id)
    {
        // Fetch the loan using the ID, or fail with a 404 error
        $payment = loans::findOrFail($id); // This will throw a 404 error if not found

        // Return a view with the payment data
        return view('Admin.Loan.EditLoan', compact('payment'));
    }



    public function updateLoan(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'number' => 'required|numeric|min:0', // Ensure the number is a required numeric field
        ]);

        $userInput = $request->input('number');
        $loan = loans::findOrFail($id);

        $totalMonthlypayemts = monthlypayments::sum('number');
        $additionalPayments = additionalpayments::sum('number');
        $notebooksPayment = notebooks::sum('number');


        $totals = $totalMonthlypayemts + $additionalPayments + $notebooksPayment;
        (int) $loans = loans::sum('number');
        (int) $totalsMinusLoan = $totals - $loans;

        $percentageOfTotal = $totalsMinusLoan * 0.25;

        if ($userInput < $percentageOfTotal) {
            $loan->number = $request->input('number');
            $loan->save();
            return redirect()->route('admin.getLoans')->with('success', 'Loan payment updated successfully.');
        } else {
            return redirect()->back()
                ->withErrors(['number' => 'مقدار درخواستی زیاد تر از 25 فیصد کل دخل است'])
                ->withInput();
        }
    }




    public function deleteLoan($id)
    {
        // Find the loan by its ID
        $loan = loans::findOrFail($id);

        // Check if the loan number is zero
        if ($loan->number == 0) {
            // Automatically delete the loan
            $loan->delete();

            // You might want to redirect back with a success message
            return redirect()->route('admin.getLoans')->with('success', 'Loan has been deleted because the number was zero.');
        }

        // If the loan number is not zero, you can either just return or handle accordingly
        return redirect()->route('admin.getLoans')->with('error', 'Loan number is not zero, deletion was not performed.');
    }



    public function loanPayments(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'number' => 'required|numeric|min:0',
            ]);
    
            $userId = $request->input('user_id');  
            $userInput = $request->input('number');
    
            // Retrieve the user's current loan
            $currentLoan = loans::where('user_id', $userId)->first();
    
            // Check if the user has a current loan
            if ($currentLoan) {
                // If the input number is greater than the current loan number
                if ($userInput > $currentLoan->number) {
                    return redirect()->route('admin.loanPayments')->withErrors(['number' => 'مقدار وارده اضافه تر از پول وام']);
                }
    
                // Subtract the input number from the current loan number and save the updated loan
                $currentLoan->number -= $userInput;
                $currentLoan->save();
    
                return redirect()->route('admin.alluser')->with('success', 'Loan amount updated successfully.');
            } else {
                // Handle the case where the user has no current loan
                return redirect()->route('admin.loanPayments')->withErrors(['user_id' => 'The selected user does not have an active loan.']);
            }
        }
    
        $users = User::join('loans', 'users.id', '=', 'loans.user_id')
            ->select('users.*')
            ->distinct()
            ->get();
    
        return view('Admin.Loan.LoanPayment', compact('users'));
    }
    
    
    
}
