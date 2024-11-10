@extends('Layout.Layout')

@section('title', 'اخذ مکمل پول')

@section('content')

<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-center">اخذ مکمل پول</h1>
    <form action="{{ route('admin.loan') }}" method="POST">
    @csrf

        <div class="mb-4">
            <label for="number" class="block text-gray-700 font-semibold mb-2">مقدار اخذ:</label>
            <input type="number" id="number" name="number" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            @error('number')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-span-1 mb-4">
            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">انتخاب کاربر</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id" onchange="updateFormAction(this)">
                <option value="">انتخاب کاربر</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} || {{ $user->lastname }}</option>
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




@endsection