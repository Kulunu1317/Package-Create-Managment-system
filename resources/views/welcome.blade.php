@extends('layout.app')

@section('content')

<div class="bg-indigo-600 rounded-3xl p-10 mb-12 text-center text-white shadow-2xl relative overflow-hidden">
    <h1 class="text-4xl font-extrabold mb-4 relative z-10">Find Jobs & Hire Talent</h1>
    <p class="text-indigo-100 text-lg relative z-10">Boost your ad visibility with our Diamond Tier packages!</p>
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <i class="fa-solid fa-briefcase text-9xl absolute -top-10 -left-10 transform rotate-12"></i>
        <i class="fa-solid fa-layer-group text-9xl absolute -bottom-10 -right-10 transform -rotate-12"></i>
    </div>
</div>

<h2 class="text-3xl font-bold text-gray-800 mb-8"><i class="fa-solid fa-tags text-indigo-600 mr-2"></i> Choose Your Tier</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    @foreach($packages as $pkg)
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 flex flex-col h-full transform transition hover:-translate-y-1" 
         x-data="{ tier: 'normal', price: {{ $pkg->price }} }">
        
        <div class="relative h-48 bg-gray-200 group">
            @if($pkg->image)
                <img src="{{ asset('storage/'.$pkg->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            @else
                <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">No Image</div>
            @endif
            <div class="absolute top-4 right-4 bg-white/90 px-3 py-1 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                <i class="fa-regular fa-clock mr-1"></i> {{ $pkg->validity_value }} {{ ucfirst($pkg->duration_unit) }}
            </div>
        </div>

        <div class="p-6 flex-grow flex flex-col">
            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $pkg->name }}</h3>
            
            <div class="flex items-baseline mb-4">
                <span class="text-3xl font-extrabold text-indigo-600" x-text="'$' + price"></span>
                <span class="text-gray-500 ml-1 text-sm font-bold uppercase" x-text="tier"></span>
            </div>

            @if($pkg->price_silver || $pkg->price_gold || $pkg->price_diamond)
            <div class="grid grid-cols-2 gap-2 mb-4">
                <button @click="tier = 'normal'; price = {{ $pkg->price }}" 
                    :class="tier === 'normal' ? 'bg-gray-800 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-2 py-1.5 rounded text-xs font-bold transition">
                    Normal
                </button>

                @if($pkg->price_silver)
                <button @click="tier = 'silver'; price = {{ $pkg->price_silver }}" 
                    :class="tier === 'silver' ? 'bg-gray-500 text-white shadow-md ring-2 ring-gray-300' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-2 py-1.5 rounded text-xs font-bold transition flex items-center justify-center">
                    <i class="fa-solid fa-medal mr-1"></i> Silver
                </button>
                @endif

                @if($pkg->price_gold)
                <button @click="tier = 'gold'; price = {{ $pkg->price_gold }}" 
                    :class="tier === 'gold' ? 'bg-yellow-500 text-white shadow-md ring-2 ring-yellow-300' : 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100'"
                    class="px-2 py-1.5 rounded text-xs font-bold transition flex items-center justify-center">
                    <i class="fa-solid fa-medal mr-1"></i> Gold
                </button>
                @endif

                @if($pkg->price_diamond)
                <button @click="tier = 'diamond'; price = {{ $pkg->price_diamond }}" 
                    :class="tier === 'diamond' ? 'bg-blue-600 text-white shadow-md ring-2 ring-blue-300' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'"
                    class="px-2 py-1.5 rounded text-xs font-bold transition flex items-center justify-center">
                    <i class="fa-regular fa-gem mr-1"></i> Diamond
                </button>
                @endif
            </div>
            @endif

            <p class="text-gray-600 text-sm mb-6 flex-grow">{{ $pkg->description }}</p>

            @auth
                @if(Auth::user()->role == 'admin')
                    <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Delete this package?');" class="mt-auto">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 py-3 rounded-xl font-bold hover:bg-red-600 hover:text-white transition duration-300 flex items-center justify-center">
                            <i class="fa-solid fa-trash mr-2"></i> Delete Package
                        </button>
                    </form>
                @else
                    <form action="{{ route('user.buy_package', $pkg->id) }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="tier" x-model="tier">
                        <button type="submit" class="w-full py-3 rounded-xl font-semibold shadow-md transition duration-300 flex justify-center items-center"
                            :class="{
                                'bg-gray-800 text-white hover:bg-gray-900': tier === 'normal',
                                'bg-gray-500 text-white hover:bg-gray-600': tier === 'silver',
                                'bg-yellow-500 text-white hover:bg-yellow-600': tier === 'gold',
                                'bg-blue-600 text-white hover:bg-blue-700': tier === 'diamond'
                            }">
                            <span>Buy</span> <span class="ml-1 capitalize" x-text="tier"></span>
                        </button>
                    </form>
                @endif
            @endauth
            
            @guest
                <a href="{{ route('login') }}" class="mt-auto w-full bg-indigo-600 text-white py-3 rounded-xl font-bold text-center hover:bg-indigo-700 shadow-md">
                    Login to Buy
                </a>
            @endguest
        </div>
    </div>
    @endforeach
</div>

<div class="mb-12">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4"><i class="fa-solid fa-briefcase text-indigo-600 mr-2"></i> Latest Jobs</h2>

    @if($ads->isEmpty())
        <div class="bg-blue-50 text-blue-800 p-8 rounded-xl flex flex-col items-center justify-center text-center">
            <i class="fa-solid fa-circle-info text-4xl mb-3"></i>
            <p class="text-lg font-medium">No active job advertisements available at the moment.</p>
        </div>
    @else
        @php
            $diamondAds = $ads->where('tier', 'diamond');
            $goldAds = $ads->where('tier', 'gold');
            $silverAds = $ads->where('tier', 'silver');
            $normalAds = $ads->where('tier', 'normal');
        @endphp

        @if($diamondAds->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <h3 class="text-2xl font-bold text-blue-600 flex items-center">
                    <i class="fa-regular fa-gem mr-2"></i> Diamond Listings
                </h3>
                <div class="ml-4 h-1 flex-grow bg-gradient-to-r from-blue-200 to-transparent rounded"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($diamondAds as $ad)
                    @include('partials.ad_card', ['ad' => $ad, 'border' => 'border-2 border-blue-500 ring-2 ring-blue-50', 'badge' => 'bg-blue-600 text-white'])
                @endforeach
            </div>
        </div>
        @endif

        @if($goldAds->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <h3 class="text-2xl font-bold text-yellow-500 flex items-center">
                    <i class="fa-solid fa-medal mr-2"></i> Gold Listings
                </h3>
                <div class="ml-4 h-1 flex-grow bg-gradient-to-r from-yellow-200 to-transparent rounded"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($goldAds as $ad)
                    @include('partials.ad_card', ['ad' => $ad, 'border' => 'border-2 border-yellow-400 ring-2 ring-yellow-50', 'badge' => 'bg-yellow-500 text-white'])
                @endforeach
            </div>
        </div>
        @endif

        @if($silverAds->isNotEmpty())
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-500 flex items-center">
                    <i class="fa-solid fa-medal mr-2"></i> Silver Listings
                </h3>
                <div class="ml-4 h-1 flex-grow bg-gradient-to-r from-gray-200 to-transparent rounded"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($silverAds as $ad)
                    @include('partials.ad_card', ['ad' => $ad, 'border' => 'border border-gray-400', 'badge' => 'bg-gray-500 text-white'])
                @endforeach
            </div>
        </div>
        @endif

        @if($normalAds->isNotEmpty())
        <div class="mb-10">
             <div class="flex items-center mb-4">
                <h3 class="text-xl font-bold text-gray-700 flex items-center">
                    <i class="fa-solid fa-list mr-2"></i> Standard Listings
                </h3>
                <div class="ml-4 h-1 flex-grow bg-gray-100 rounded"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($normalAds as $ad)
                    @include('partials.ad_card', ['ad' => $ad, 'border' => 'border border-gray-100', 'badge' => null])
                @endforeach
            </div>
        </div>
        @endif

    @endif
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection

@section('scripts')
@endsection