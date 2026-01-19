<div class="min-h-screen bg-gray-50 py-6 sm:py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Student Dashboard</h1>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Bekijk hier jouw oefeningen en instructies.</p>
            </div>
            </div>

        <div class="flex flex-col sm:flex-row gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Zoek op naam..." 
                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($exercises as $exercise)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full relative">
                    
                    <div class="h-1 w-full bg-gradient-to-r from-green-400 to-blue-500"></div>

                    <div class="p-6 flex-grow">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $exercise['category'] ?? 'Algemeen' }}
                            </span>
                            
                            @php
                                $diffColor = match($exercise['difficulty'] ?? '') {
                                    'Beginner' => 'text-green-500',
                                    'Gemiddeld' => 'text-yellow-500',
                                    'Gevorderd' => 'text-orange-500',
                                    'Expert' => 'text-red-500',
                                    default => 'text-gray-400',
                                };
                            @endphp
                            <div class="flex items-center text-xs font-medium text-gray-500">
                                <svg class="{{ $diffColor }} w-3 h-3 mr-1 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                {{ $exercise['difficulty'] ?? 'N/A' }}
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $exercise['name'] }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                            {{ $exercise['description'] ?? 'Geen beschrijving.' }}
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs text-gray-400"></span> <button 
                            wire:click="viewExercise('{{ $exercise['id'] }}')"
                            class="flex items-center text-gray-500 hover:text-blue-600 transition-colors text-sm font-medium p-2 rounded-lg hover:bg-blue-50"
                            title="Bekijk details">
                            Details Bekijken
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-12 text-center bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Geen oefeningen gevonden</h3>
                    <p class="text-gray-500 mt-1">Probeer een andere zoekterm.</p>
                </div>
            @endforelse
        </div>

        @if($viewingExercise)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
             x-data 
             @keydown.escape.window="$wire.closeViewExercise()">
            
            <div class="fixed inset-0 transparent bg-opacity-50 transition-opacity" 
                 wire:click="closeViewExercise"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all p-6 sm:p-8">
                
                <button wire:click="closeViewExercise" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="space-y-6">
                    <div class="border-b border-gray-100 pb-4">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">
                            {{ $viewingExercise['name'] }}
                        </h2>
                        
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $viewingExercise['category'] }}
                            </span>

                            @php
                                $diffColor = match($viewingExercise['difficulty'] ?? '') {
                                    'Beginner' => 'bg-green-100 text-green-800',
                                    'Gemiddeld' => 'bg-yellow-100 text-yellow-800',
                                    'Gevorderd' => 'bg-orange-100 text-orange-800',
                                    'Expert' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $diffColor }}">
                                {{ $viewingExercise['difficulty'] }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Beschrijving</h3>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                {{ $viewingExercise['description'] }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Instructies</h3>
                            <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $viewingExercise['instructions'] ?? 'Geen instructies beschikbaar.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>