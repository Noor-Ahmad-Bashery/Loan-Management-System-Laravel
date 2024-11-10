@extends('Layout')


@section('content')

<div class="container mx-auto py-10">
    @if (session('success'))
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    @endsection