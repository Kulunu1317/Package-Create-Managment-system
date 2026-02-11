@extends('layout.app')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-box-open text-indigo-600 mr-2"></i> My Active Packages</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($myPackages as $up)
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden relative">
        <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">{{ $up->package->name }}</h3>
            <span class="text-xs font-mono bg-white border px-2 py-1 rounded text-gray-500">#{{ $up->id }}</span>
        </div>

        <div class="p-6">
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Ads Posted</span>
                    <span class="font-bold text-indigo-600">{{ $up->ads_posted }} / {{ $up->package->ad_limit }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($up->ads_posted / $up->package->ad_limit) * 100 }}%"></div>
                </div>
            </div>

            <div class="flex items-center text-sm text-red-500 mb-6 bg-red-50 p-2 rounded">
                <i class="fa-regular fa-clock mr-2"></i> Expires: {{ $up->expires_at->format('M d, Y') }}
                <span class="ml-auto text-xs font-bold">({{ $up->expires_at->diffForHumans() }})</span>
            </div>

            @if($up->ads_posted < $up->package->ad_limit)
                <a href="{{ route('user.create_ad', $up->id) }}" class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-md">
                    <i class="fa-solid fa-plus mr-1"></i> Post Advertisement
                </a>
            @else
                <button disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-xl font-semibold cursor-not-allowed border border-gray-200">
                    <i class="fa-solid fa-ban mr-1"></i> Limit Reached
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection