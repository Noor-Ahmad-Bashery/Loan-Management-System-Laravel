@extends('Layout.Layout')

@section('title', 'پرداخت ماهانه')

@section('content')

<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">ثبت پرداخت قرضه</h1>
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطا!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">موفقیت!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <form id="payment-form" action="{{ route('admin.loanPayments') }}" method="POST" onsubmit="disableSubmitButton()">
        @csrf

        <div class="mb-4">
            <label for="number" class="block text-gray-700 font-semibold mb-2">مقدار پرداخت قرض</label>
            <input type="number" id="number" name="number" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            @error('number')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-span-1 mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">انتخاب کاربر</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id">
                <option value="">انتخاب کاربر</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} || {{ $user->lastname }}</option>
                @endforeach
            </select>
            @error('user_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <button type="submit" id="submit-button" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                ثبت مقدار
            </button>
        </div>
    </form>
</div>

<script>
    function disableSubmitButton() {
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.innerText = 'در حال ارسال...'; // Optional: Change button text to indicate the form is being submitted
    }
</script>

@endsection
