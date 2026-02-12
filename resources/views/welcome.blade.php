@extends('layout.app')

@section('content')

<div class="bg-indigo-600 rounded-3xl p-10 mb-12 text-center text-white shadow-2xl relative overflow-hidden">
    <div class="absolute inset-0 bg-pattern opacity-10"></div> 
    <h1 class="text-4xl md:text-5xl font-extrabold mb-4 relative z-10">Find Your Dream Job or Hire Talent</h1>
    <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto relative z-10">Join thousands of companies and professionals. Buy a package to post ads or browse opportunities today.</p>
    @guest
        <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-8 py-3 rounded-full font-bold text-lg shadow-lg hover:bg-gray-100 transition relative z-10">
            Create Account <i class="fa-solid fa-arrow-right ml-2"></i>
        </a>
    @endguest
</div>

<div class="flex items-center justify-between mb-8">
    <h2 class="text-3xl font-bold text-gray-800"><i class="fa-solid fa-tags text-indigo-600 mr-2"></i> Advertising Packages</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    @foreach($packages as $pkg)
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition duration-300 border border-gray-100">
        <div class="relative h-48 bg-gray-200">
            @if($pkg->image)
                <img src="{{ asset('storage/'.$pkg->image) }}" class="w-full h-full object-cover">
            @else
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <i class="fa-solid fa-image text-4xl mb-2"></i>
                    <span>No Image</span>
                </div>
            @endif
            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-700 shadow-sm">
                <i class="fa-regular fa-clock mr-1"></i> {{ $pkg->validity_value }} {{ ucfirst($pkg->duration_unit) }}
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $pkg->name }}</h3>
            <div class="flex items-baseline mb-4">
                <span class="text-3xl font-extrabold text-indigo-600">${{ number_format($pkg->price, 0) }}</span>
                <span class="text-gray-500 ml-1 text-sm">/ package</span>
            </div>
            
            <p class="text-gray-600 text-sm mb-6 line-clamp-2 h-10">{{ $pkg->description }}</p>

            <ul class="text-sm text-gray-600 space-y-2 mb-6 border-t pt-4">
                <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Post up to <strong>{{ $pkg->ad_limit }}</strong> Ads</li>
                <li class="flex items-center"><i class="fa-solid fa-check text-green-500 mr-2"></i> Premium Support</li>
            </ul>
            
            @auth
                @if(Auth::user()->role == 'admin')
                    <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Delete this package?');">
                        @csrf @method('DELETE')
                        <button class="w-full bg-red-50 text-red-600 border border-red-200 py-2.5 rounded-xl font-semibold hover:bg-red-600 hover:text-white transition duration-300">
                            <i class="fa-solid fa-trash mr-2"></i> Delete Package
                        </button>
                    </form>
                @else
                    <form action="{{ route('user.buy_package', $pkg->id) }}" method="POST">
                        @csrf
                        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold shadow-md hover:bg-indigo-700 hover:shadow-lg transition duration-300 flex justify-center items-center">
                            <span>Buy Now</span> <i class="fa-solid fa-cart-shopping ml-2"></i>
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
    @endforeach
</div>

<div class="flex items-center justify-between mb-8 border-t pt-10">
    <h2 class="text-3xl font-bold text-gray-800"><i class="fa-solid fa-briefcase text-indigo-600 mr-2"></i> Latest Jobs</h2>
</div>

@if($ads->isEmpty())
    <div class="bg-blue-50 text-blue-800 p-6 rounded-xl flex items-center justify-center">
        <i class="fa-solid fa-circle-info mr-2"></i> No active job advertisements available at the moment.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($ads as $ad)
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 group relative">
            
            <div class="flex items-start justify-between mb-4">
                <img src="{{ asset('storage/'.$ad->company_logo) }}" class="w-14 h-14 rounded-xl object-cover shadow-sm border border-gray-100 group-hover:scale-105 transition">
                <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wide">
                    {{ $ad->job_type }}
                </span>
            </div>
            
            <h4 class="font-bold text-lg text-gray-900 mb-1 line-clamp-1 group-hover:text-indigo-600 transition">{{ $ad->job_name }}</h4>
            <p class="text-sm text-gray-500 mb-4 h-10 line-clamp-2">{{ $ad->description }}</p>
            
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                <span class="text-green-600 font-bold text-sm"><i class="fa-solid fa-money-bill-wave mr-1"></i> {{ $ad->salary }}</span>
                <button class="text-gray-400 hover:text-indigo-600"><i class="fa-regular fa-bookmark"></i></button>
            </div>

            @if(Auth::check() && Auth::id() == $ad->user_id)
                <div class="mt-4">
                    <button onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.remove('hidden')" 
                            class="w-full bg-indigo-100 text-indigo-700 text-xs font-bold py-2 rounded hover:bg-indigo-200 transition">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Update Time
                    </button>
                </div>

                <div id="extend-modal-{{ $ad->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-xl w-96 shadow-2xl animate-fade-in-up">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg text-gray-800">Extend Ad Duration</h3>
                            <button onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.add('hidden')" class="text-gray-400 hover:text-red-500">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-4">Request admin to extend the removal time for <strong>{{ $ad->job_name }}</strong>.</p>
                        
                        <form action="{{ route('user.extend_ad', $ad->id) }}" method="POST">
                            @csrf
                            <div class="flex space-x-2 mb-4">
                                <input type="number" name="extension_value" class="border border-gray-300 p-2 rounded w-2/3 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Value (e.g. 30)" required>
                                <select name="extension_unit" class="border border-gray-300 p-2 rounded w-1/3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="minutes">Mins</option>
                                    <option value="hours">Hours</option>
                                    <option value="days">Days</option>
                                </select>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancel</button>
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-medium hover:bg-indigo-700 transition">Send Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
        @endforeach
    </div>
@endif

@endsection