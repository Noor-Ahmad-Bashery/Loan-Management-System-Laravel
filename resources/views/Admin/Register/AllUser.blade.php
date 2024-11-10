@extends('Layout.Layout')

@section('title', 'نمایش کاربران')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6">لیست کاربران</h2>
    <div class="mb-6">
    <p class="text-xl flex justify-between items-center space-x-4">
        <span>مجموع کل پرداختی‌ها: {{$totalPayments }} افغانی</span>
        <span>مجموع کل باقیداری‌ها: {{$loans }} افغانی</span>
        <span>مجموع کل پرداختی‌هامنهای باقیداری: {{$totalsMinusLoan }} افغانی</span>
    </p>
</div>

@if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">خطا!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif
    <!-- Search Form -->
    <form action="{{ route('admin.alluser') }}" method="GET" class="mb-6 flex flex-col md:flex-row items-center md:space-x-4">
        <input type="text" name="search" placeholder="جستجو کاربران..." value="{{ request('search') }}" class="border rounded py-2 px-4 w-full md:w-1/3 mb-2 md:mb-0">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto">جستجو</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @foreach($users as $user)
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center">
                <img src="{{ $user->profile_image_url }}" alt="Profile Image" class="h-20 w-20 rounded-full border mr-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ $user->name }} {{ $user->lastname }}</h3>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap justify-end space-y-2 md:space-y-0 md:space-x-2">
                <a href="{{ route('admin.getPaidMonths', ['userId' => $user->id]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm md:text-base w-full md:w-auto">پرداخت ها</a>

                <a href="{{ route('admin.getPaidAdditionalPayments', ['userId' => $user->id]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm md:text-base w-full md:w-auto">اضافه ها</a>

             

                <a href="{{ route('admin.getsingeluser', ['id' => $user->id]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm md:text-base w-full md:w-auto">ویرایش</a>

                <form action="{{ route('admin.deleteUser', ['id' => $user->id]) }}" method="POST" class="delete-user-form inline-block w-full md:w-auto"> <!-- Inline-block to align with buttons -->
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm md:text-base w-full md:w-auto">حذف</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        <p class="text-center text-gray-600">
            صفحه {{ $users->currentPage() }} از {{ $users->lastPage() }}
        </p>
        {{ $users->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-800 bg-opacity-75">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-auto">
        <h3 class="text-lg font-semibold mb-4">آیا مطمئن هستید که می‌خواهید این کاربر را حذف کنید؟</h3>
        <div class="flex flex-wrap justify-end space-y-2 md:space-y-0 md:space-x-4 m-4">
            <button id="cancel-delete" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto">لغو</button>
            <button id="confirm-delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto">حذف</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-user-form');
        const modal = document.getElementById('delete-confirmation-modal');
        const cancelDeleteButton = document.getElementById('cancel-delete');
        const confirmDeleteButton = document.getElementById('confirm-delete');
        let formToSubmit;

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                formToSubmit = form;
                modal.classList.remove('hidden');
            });
        });

        cancelDeleteButton.addEventListener('click', function() {
            modal.classList.add('hidden');
            formToSubmit = null;
        });

        confirmDeleteButton.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    });
</script>
@endsection