@extends('layout.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-box-open text-indigo-600 mr-2"></i> Create New Package</h2>
        <p class="text-gray-500 text-sm mt-1">Set up a new advertising plan for your users.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border-l-4 border-red-500">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = '<i class=\'fa-solid fa-spinner fa-spin mr-2\'></i> Processing...';">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Package Name</label>
                <input type="text" name="name" placeholder="e.g. Premium Blaster" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Price ($)</label>
                <input type="number" name="price" placeholder="e.g. 50.00" step="0.01" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ad Limit (Max Ads)</label>
                <input type="number" name="ad_limit" placeholder="e.g. 10" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Package Image</label>
                <input type="file" name="image" class="w-full border border-gray-300 p-2 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
            </div>
        </div>

        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
            <h3 class="text-sm font-bold text-indigo-800 mb-3"><i class="fa-regular fa-clock mr-1"></i> Package Validity Duration</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Value</label>
                    <input type="number" name="validity_value" placeholder="e.g. 30" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Unit</label>
                    <select name="duration_unit" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="minutes">Minutes</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                    </select>
                </div>
            </div>
            <p class="text-xs text-indigo-500 mt-2">Example: Value <b>2</b> + Unit <b>Minutes</b> = Package expires in 2 Minutes.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Describe the benefits..." class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" required></textarea>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:bg-indigo-700 transform hover:-translate-y-0.5 transition duration-200">
                <i class="fa-solid fa-plus mr-2"></i> Create Package
            </button>
        </div>
    </form>
</div>
@endsection