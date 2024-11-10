@extends('Layout.Layout')

@section('title', 'نمایش پرداختی ها')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6">لیست پرداخت های کاربر</h2>
    <div class="mb-6">

        <p class="text-xl">مجموع کل پرداختی‌های ماهانه: {{$totalMonthlyPayment }} افغانی</p>
    </div>
    <!-- Search Form -->
    <form action="{{ route('admin.getPaidMonths', ['userId' => $userId]) }}" method="GET" class="mb-6 flex flex-col md:flex-row items-center md:space-x-4">
        <input type="text" name="search" placeholder="جستجو پرداخت‌ها..." value="{{ request('search') }}" class="border rounded py-2 px-4 w-full md:w-1/3 mb-2 md:mb-0">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-auto">جستجو</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($payments as $payment)
        <div class="bg-white shadow-md rounded-lg p-6">
            <p>شماره پرداخت: {{ $payment->number }}</p>
            <p>تاریخ پرداخت: {{ $payment->created_at }}</p>
            <div class="mt-4 flex justify-end space-x-2">
                <a href="{{ route('admin.singlePayment', ['id' => $payment->id]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">ویرایش</a>

                <form action="{{ route('admin.deletePayment', ['id' => $payment->id]) }}" method="POST" class="delete-user-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">حذف</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $payments->appends(['search' => request('search')])->links() }}
    </div>

    <div class="mt-6">
        <p class="text-center text-gray-600">
            صفحه {{ $payments->currentPage() }} از {{ $payments->lastPage() }}
        </p>
        {{ $payments->appends(['search' => request('search')])->links() }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-800 bg-opacity-75">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-auto">
        <h3 class="text-lg font-semibold mb-4">آیا مطمئن هستید که می‌خواهید این پرداخت را حذف کنید؟</h3>
        <div class="flex justify-end space-x-4 m-4">
            <button id="cancel-delete" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">لغو</button>
            <button id="confirm-delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">حذف</button>
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
