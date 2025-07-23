@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-lg font-bold mb-4">All Items</h1>

                <ul>
                    @forelse ($items as $item)
                        <li class="border-b py-2">
                            {{ $item->name }} — Lab: {{ $item->lab->name ?? 'N/A' }}
                        </li>
                    @empty
                        <li>No items found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
