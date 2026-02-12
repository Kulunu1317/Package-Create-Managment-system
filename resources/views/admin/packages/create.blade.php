@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-box-open text-indigo-600 mr-2"></i> Create New Package</h2>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Package Name</label>
                <input type="text" name="name" class="w-full border p-3 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Base Price ($)</label>
                <input type="number" name="price" step="0.01" class="w-full border p-3 rounded-lg" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ad Limit</label>
                <input type="number" name="ad_limit" class="w-full border p-3 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                <input type="file" name="image" class="w-full border p-2 rounded-lg" required>
            </div>
        </div>

        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
            <h3 class="text-sm font-bold text-indigo-800 mb-3"><i class="fa-regular fa-clock mr-1"></i> Validity</h3>
            <div class="flex gap-4">
                <input type="number" name="validity_value" placeholder="Val" class="w-1/2 border p-2 rounded" required>
                <select name="duration_unit" class="w-1/2 border p-2 rounded">
                    <option value="minutes">Minutes</option>
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                </select>
            </div>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <details class="group">
                <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-gray-50 hover:bg-gray-100 transition">
                    <span class="text-indigo-700 font-bold flex items-center">
                        <i class="fa-solid fa-gem mr-2"></i> Advanced Feature: Tiered Pricing
                    </span>
                    <span class="transition group-open:rotate-180">
                        <i class="fa-solid fa-chevron-down text-gray-500"></i>
                    </span>
                </summary>
                
                <div class="text-gray-500 text-sm px-4 pt-2 pb-2">
                    Optional: Set higher prices for priority tiers. Diamond ads appear at the top.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white">
                    <div class="border rounded-lg p-3 hover:shadow-md transition bg-gray-50">
                        <div class="flex items-center mb-2 text-gray-600">
                            <i class="fa-solid fa-medal mr-2"></i> <span class="font-bold">Silver Price</span>
                        </div>
                        <input type="number" step="0.01" name="price_silver" placeholder="ex: 60.00" class="w-full border p-2 rounded">
                    </div>

                    <div class="border rounded-lg p-3 hover:shadow-md transition bg-yellow-50">
                        <div class="flex items-center mb-2 text-yellow-600">
                            <i class="fa-solid fa-medal mr-2"></i> <span class="font-bold">Gold Price</span>
                        </div>
                        <input type="number" step="0.01" name="price_gold" placeholder="ex: 80.00" class="w-full border p-2 rounded">
                    </div>

                    <div class="border rounded-lg p-3 hover:shadow-md transition bg-blue-50">
                        <div class="flex items-center mb-2 text-blue-600">
                            <i class="fa-regular fa-gem mr-2"></i> <span class="font-bold">Diamond Price</span>
                        </div>
                        <input type="number" step="0.01" name="price_diamond" placeholder="ex: 100.00" class="w-full border p-2 rounded">
                    </div>
                </div>
            </details>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border p-3 rounded-lg" required></textarea>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">Create Package</button>
    </form>
</div>
@endsection