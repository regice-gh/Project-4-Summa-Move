<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Beschikbare Oefeningen</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @foreach($exercises as $exercise)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $exercise['name'] }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $exercise['difficulty'] == 'Beginner' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ $exercise['difficulty'] ?? 'N/A' }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4 text-sm">
                        {{ $exercise['description'] ?? 'Geen beschrijving beschikbaar.' }}
                    </p>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            Categorie: <strong>{{ $exercise['category'] ?? 'Algemeen' }}</strong>
                        </span>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</div>