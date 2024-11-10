@extends('Layout.Layout')

@section('title', 'ویرایش پرداخت')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6 text-center">ویرایش پرداخت</h2>

    @if ($errors->any())
        <div class="mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.updateLoan', ['id' => $payment->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="number" class="block text-gray-700">Payment Amount:</label>
            <input type="text" name="number" id="number" value="{{ $payment->number }}" class="border rounded py-2 px-4 w-full">
        </div>

        <div class="mb-4">
            <label for="payment_date" class="block text-gray-700">Payment Date:</label>
            <input type="date" disabled  name="payment_date" id="payment_date" value="{{ $payment->created_at->format('Y-m-d') }}" class="border rounded py-2 px-4 w-full">
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-full shadow-lg transform transition-transform duration-200 hover:scale-105">
                Update Payment
            </button>
        </div>
    </form>
</div>
@endsection
