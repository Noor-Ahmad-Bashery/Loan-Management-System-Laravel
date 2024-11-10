<?php

namespace App\Http\Controllers\Admin;

use App\Models\monthlypayments;
use App\Models\Role; // Correct namespace for the Role model
use App\Models\User; // Correct namespace for the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Corrected import for Validator
use App\Http\Controllers\Controller;
use App\Models\additionalpayments;
use App\Models\loans;
use App\Models\notebooks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\Console;

class AdminController extends Controller
{
    public function getDashboard()
    {
        return view("Admin.AdminDashboard");
    }
    public function getRegister(Request $request)
    {
        $roles = Role::all(); // Fetch all roles
        $users = User::all(); // Fetch all users

        // Calculate the total monthly payment amount
        (int) $totalMonthlyPayment = MonthlyPayments::sum('number'); // Assuming 'number' is the field name for monthly payments

        // Calculate the total additional payment amount
        (int)  $totalAdditionalPayment = AdditionalPayments::sum('number'); // Assuming 'number' is the field name for additional payments

        // Calculate the combined total of monthly and additional payments
        $totalPayments = $totalMonthlyPayment + $totalAdditionalPayment;

        // Debugging
        Log::info($totalPayments);

        // Pass all calculated totals to the view
        return view('Admin.Register.Register', compact('roles', 'users', 'totalMonthlyPayment', 'totalAdditionalPayment', 'totalPayments'));
    }


    public function postRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:1,10', // Ensure phone is numeric and max 10 digits
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Unique validation rule for email
            'password' => 'required|string|min:3', // Ensure a minimum password length
            'age_reference' => 'nullable|exists:users,id',
            'role' => 'required|exists:roles,id',
            'age' => 'required|integer|min:1|max:100',
            'national_id_image' => 'required|image|mimes:jpg,png|max:2048',
            'profile_image' => 'required|image|mimes:jpg,png|max:2048',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        try {
            $user = new User();
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Encrypt password before saving
            $user->role_id = $request->role;
            $user->age = $request->age;
            $user->age_reference = $request->age_reference;

            // Set user_id correctly
            $user->user_id = $request->age_reference; // Assuming this is correct
            // If there is a guarantor field, make sure it's properly set
            if ($request->has('user_id')) {
                $user->user_id = $request->user_id;
            }

            if ($request->hasFile('national_id_image')) {
                $nationalIdImagePath = $request->file('national_id_image')->store('images/national_ids', 'public');
                $user->national_id_image = $nationalIdImagePath;
            }

            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('images/profiles', 'public');
                $user->profile_image = $profileImagePath;
            }
            $user->save(); // Save the user to the database

            return redirect()->route('admin.alluser')->with('success', 'User registered successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error saving user', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while saving the user. Please try again.')->withInput();
        }
    }


    public function getRegisteredUser(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }
        $totalMonthlyPayment = MonthlyPayments::sum('number'); // Assuming 'number' is the field name for monthly payments

        $totalAdditionalPayment = AdditionalPayments::sum('number'); // Assuming 'number' is the field name for additional payments

        $totalNotebooks = notebooks::sum('number');
        // Calculate the combined total of monthly and additional payments

        $totals = $totalMonthlyPayment + $totalAdditionalPayment + $totalNotebooks;

        (int) $loans = loans::sum('number');
        (int) $totalPayments = $totals;
        (int) $totalsMinusLoan = $totals - $loans;

        // Debugging
        Log::info($totalPayments);

        $users = $query->paginate(4); // Fetch 6 users per page
        return view('Admin.Register.AllUser', compact('users', 'totalsMinusLoan', 'totalPayments', 'loans'));
    }

    public function getSingleUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.alluser')->with('error', 'User not found.');
        }

        $roles = Role::all();

        return view('Admin.Register.EditUser', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.alluser')->with('error', 'User not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:1,10',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'age' => 'required|integer|min:1|max:100',
            'national_id_image' => 'nullable|image|mimes:jpg,png|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->role_id = $request->role;
            $user->age = $request->age;

            if ($request->hasFile('national_id_image')) {
                // Delete the old image if it exists
                if ($user->national_id_image) {
                    Storage::disk('public')->delete($user->national_id_image);
                }
                $nationalIdImagePath = $request->file('national_id_image')->store('images/national_ids', 'public');
                $user->national_id_image = $nationalIdImagePath;
            }

            if ($request->hasFile('profile_image')) {
                // Delete the old image if it exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $profileImagePath = $request->file('profile_image')->store('images/profiles', 'public');
                $user->profile_image = $profileImagePath;
            }

            $user->save();

            return redirect()->route('admin.alluser')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while updating the user. Please try again.')->withInput();
        }
    }


    public function deleteUser($id)
    {
        $user = User::find($id);
        $loan = loans::findOrFail($id);
        if ($loan) {
            return redirect()->back()
                ->withErrors(['number' => 'کاربر باقی است شما نمی توانید حذف کنید'])
                ->withInput();
        }
        if ($user) {
            // Delete the user's images if they exist
            if ($user->national_id_image) {
                Storage::disk('public')->delete($user->national_id_image);
            }

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->delete(); // Delete the user from the database

            return redirect()->route('admin.alluser')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->route('admin.alluser')->with('error', 'User not found.');
        }
    }


    public function getNotebook()
    {
        // Fetch users who do not have a notebook entry with a filled number
        $users = User::whereDoesntHave('notebook', function ($query) {
            $query->whereNotNull('number');
        })->get();

        return view('Admin.Notebook.Notebook', compact('users')); // Pass the users to the view
    }



    public function postNotebook(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'user_id' => 'required|exists:users,id', // Ensure user_id exists in users table
        ]);

        try {
            Log::info('Notebook entry submitted', [
                'number' => $request->number,
                'user_id' => $request->user_id,
            ]);

            // Create and save the notebook entry
            $notebook = new notebooks();
            $notebook->number = $request->number;
            $notebook->user_id = $request->user_id;
            $notebook->save();

            return redirect()->route('admin.alluser')->with('success', 'Notebook entry added successfully!'); // Redirect back with success message
        } catch (\Exception $e) {
            Log::error('Error processing notebook entry', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while processing the notebook entry. Please try again.')->withInput();
        }
    }

    public function getMonthlyPayment(Request $request)
    {
        $users = User::all();
        return view('Admin.Monthlypayment.Monthlypayment', compact('users'));
    }





    public function postMonthlyPayment(Request $request)
    {
        // Validate the request
        $request->validate([
            'number' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'manual_payment_date' => 'date_format:Y-m-d', // Optional date field for manual entries
        ]);

        $userInput = $request->input('number');
        $userId = $request->input('user_id');

        if (!notebooks::find($userId)) {
            return redirect()->back()
                ->withErrors(['user_id' => 'کاربر انتخاب شده  پول کتابچه خود را پرداخت نکرده است'])
                ->withInput();
        }

        $user = notebooks::where('user_id', $request->user_id)->firstOrFail();

        // Check if the user exists
        if (!$user) {
            Log::error('User not found in Notebooks table', ['user_id' => $request->user_id]);
            return redirect()->back()->with('error', 'User not found.');
        }

        // Retrieve the last payment for the user
        $userLastPayment = monthlypayments::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->first();
        $currentDate = Carbon::now();

        // Determine the start date for calculating months and years difference
        $startDate = $userLastPayment ? $userLastPayment->created_at : $user->created_at;

        // Calculate year and month difference
        $yearsDifference = (int) $startDate->diffInYears($currentDate);
        $monthsDifference = (int) $startDate->diffInMonths($currentDate) % 12; // Remainder for months in the current year


        // Handle regular and missed payments
        if ($yearsDifference == 0 && $monthsDifference == 0) {
            // If no months or years are missed, make a regular payment
            $this->createPayment($request->number, $user->id, $currentDate);
            return redirect()->back()->withErrors(['no_payment' => 'کاربر هیچ ماه باقی مانده برای پرداخت ندارد']);
        } elseif ($yearsDifference > 0 || $monthsDifference > 0) {
            // Handle missed months by making a payment for the next unpaid month after the last payment
            $nextPaymentDate = $startDate->copy()->addMonth();
            $this->createPayment($request->number, $user->id, $nextPaymentDate);
        }

        return redirect()->route('admin.alluser')->with('success', 'User paid successfully!');
    }

    private function createPayment($number, $userId, $paymentDate)
    {
        $payment = new MonthlyPayments();
        $payment->number = $number;
        $payment->user_id = $userId;
        $payment->created_at = $paymentDate;
        $payment->save();
    }



    // app/Http/Controllers/AdminController.php



    public function getPaidMonths(Request $request, $userId)
    {
        $totalMonthlyPayment = MonthlyPayments::where('user_id', $userId)->sum('number');
        $query = MonthlyPayments::where('user_id', $userId);
        $query = MonthlyPayments::where('user_id', $userId)
            ->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('created_at', 'like', "%{$search}%")
                    ->orWhere('updated_at', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(6); // Fetch 6 payments per page
        return view('Admin.Monthlypayment.AllPayment', compact('payments', 'userId', 'totalMonthlyPayment'));
    }



    public function getSinglePayment($id)
    {
        // Retrieve the payment by ID
        $payment = monthlypayments::findOrFail($id); // This will throw a 404 error if not found

        // Return a view with the payment data
        return view('Admin.Monthlypayment.EditMonthlypayment', compact('payment'));
    }
    public function getSingleMonthlyPaymentForUpdate($id)
    {
        $payment = MonthlyPayments::find($id);

        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        $roles = Role::all();

        return view('Admin.Monthlypayment.EditMonthlypayment', compact('payment', 'roles'));
    }




    public function updateMonthlypayment(Request $request, $id)
    {
        // Find the specific payment by ID
        $payment = MonthlyPayments::find($id);

        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        // Validate the input
        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric',
            'payment_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update payment details
            $payment->number = $request->input('number');
            $payment->created_at = $request->input('payment_date');
            $payment->save();

            return redirect()->route('admin.getPaidMonths', ['userId' => $payment->user_id])
                ->with('success', 'Payment updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating payment', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while updating the payment.')->withInput();
        }
    }


    public function deletePayment(Request $request, $id)
    {
        // Find the payment by ID
        $payment = MonthlyPayments::find($id);

        // Check if the payment exists
        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        try {
            // Delete the payment
            $payment->delete();

            return redirect()->route('admin.alluser')->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting payment', [
                'error' => $e->getMessage(),
                'payment_id' => $id,
            ]);

            return redirect()->route('admin.alluser')->with('error', 'An error occurred while deleting the payment.');
        }
    }



    public function getAdditionalPayment(Request $request)
    {
        $users = User::all();
        return view('Admin.AdditionalPayment.AdditionalPayment', compact('users'));
    }


    public function postAdditionalPayment(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($request) {
            $payment = new additionalpayments();
            $payment->number = $request->number; // Correct access to `number`
            $payment->user_id = $request->user_id; // Correct access to `user_id`
            $payment->save();
        }
        return redirect()->route('admin.alluser')->with('success', 'User paid successfully!');
    }


    public function getSingleAdditionalPaymentForUpdate($id)
    {
        $payment = additionalpayments::find($id);

        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        $roles = Role::all();

        return view('Admin.AdditionalPayment.EditAdditionalPayment', compact('payment', 'roles'));
    }


    public function getPaidAdditionalPayments(Request $request, $userId)
    {
        $additionalpayments = additionalpayments::where('user_id', $userId)->sum('number');
        $query = additionalpayments::where('user_id', $userId);
        $query = additionalpayments::where('user_id', $userId)
            ->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('created_at', 'like', "%{$search}%")
                    ->orWhere('updated_at', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(6); // Fetch 6 payments per page
        return view('Admin.AdditionalPayment.additionalPaymentAll', compact('payments', 'userId', 'additionalpayments'));
    }


    public function updateAdditionalPayment(Request $request, $id)
    {
        // Find the specific payment by ID
        $payment = additionalpayments::find($id);

        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        // Validate the input
        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric',
            'payment_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update payment details
            $payment->number = $request->input('number');
            $payment->created_at = $request->input('payment_date');
            $payment->save();

            return redirect()->route('admin.getPaidAdditionalPayments', ['userId' => $payment->user_id])
                ->with('success', 'Payment updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating payment', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while updating the payment.')->withInput();
        }
    }


    public function deleteAdditionalPayment(Request $request, $id)
    {
        // Find the payment by ID
        $payment = additionalpayments::find($id);

        // Check if the payment exists
        if (!$payment) {
            return redirect()->route('admin.alluser')->with('error', 'Payment not found.');
        }

        try {
            // Delete the payment
            $payment->delete();

            return redirect()->route('admin.getPaidAdditionalPayments')->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting payment', [
                'error' => $e->getMessage(),
                'payment_id' => $id,
            ]);

            return redirect()->route('admin.alluser')->with('error', 'An error occurred while deleting the payment.');
        }
    }
}
