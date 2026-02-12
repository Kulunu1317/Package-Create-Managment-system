<div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition relative group {{ $border }}">
    
    @if($ad->tier !== 'normal')
        <div class="absolute -top-3 -right-3 rounded-full px-3 py-1 text-xs font-bold shadow-sm flex items-center {{ $badge }}">
            <i class="fa-{{ $ad->tier === 'diamond' ? 'regular fa-gem' : 'solid fa-medal' }} mr-1"></i>
            {{ ucfirst($ad->tier) }}
        </div>
    @endif

    <div class="flex items-start justify-between mb-4">
        <img src="{{ asset('storage/'.$ad->company_logo) }}" class="w-14 h-14 rounded-xl object-cover shadow-sm bg-gray-50">
        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">{{ $ad->job_type }}</span>
    </div>
    
    <h4 class="font-bold text-lg text-gray-900 mb-1 line-clamp-1">{{ $ad->job_name }}</h4>
    <p class="text-sm text-gray-500 mb-4 h-10 line-clamp-2">{{ $ad->description }}</p>
    
    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
        <span class="text-green-600 font-bold text-sm"><i class="fa-solid fa-money-bill-wave mr-1"></i> {{ $ad->salary }}</span>
        
        @if(Auth::check() && Auth::id() == $ad->user_id)
            <button onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.remove('hidden')" 
                    class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg hover:bg-indigo-200 transition font-bold">
                Update Time
            </button>
            
            <div id="extend-modal-{{ $ad->id }}" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-xl w-96 shadow-2xl transform transition-all scale-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-gray-800">Extend Ad Duration</h3>
                        <button onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.add('hidden')" class="text-gray-400 hover:text-red-500">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="{{ route('user.extend_ad', $ad->id) }}" method="POST">
                        @csrf
                        <div class="flex space-x-2 mb-4">
                            <input type="number" name="extension_value" class="border border-gray-300 p-2 rounded w-2/3 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Value" required>
                            <select name="extension_unit" class="border border-gray-300 p-2 rounded w-1/3 focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="minutes">Mins</option>
                                <option value="hours">Hours</option>
                                <option value="days">Days</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="document.getElementById('extend-modal-{{ $ad->id }}').classList.add('hidden')" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg transition">Cancel</button>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>