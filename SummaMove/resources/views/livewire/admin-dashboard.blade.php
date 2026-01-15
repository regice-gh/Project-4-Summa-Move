<div class="min-h-screen bg-gray-50 py-12 font-sans">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Admin Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Beheer en organiseer het trainingsaanbod.</p>
            </div>
            
            <button 
                wire:click="$toggle('showCreateForm')" 
                class="group relative inline-flex items-center justify-center px-6 py-2 text-sm font-medium text-white transition-all duration-200 bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                
                @if($showCreateForm)
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Annuleren
                @else
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nieuwe Oefening
                @endif
            </button>
        </div>

        <div class="space-y-4 mb-6">
            @if (session()->has('message'))
                <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 border-l-4 border-green-500" role="alert">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 border-l-4 border-red-500" role="alert">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <div 
            x-data="{ show: @entangle('showCreateForm') }" 
            x-show="show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 mb-8"
            style="display: none;"> <div class="border-b border-gray-100 pb-4 mb-6">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $editingId ? 'Oefening Bewerken' : 'Nieuwe Oefening Toevoegen' }}
                </h3>
                <p class="text-sm text-gray-500">Vul de details in om een nieuwe oefening aan de database toe te voegen.</p>
            </div>
            
            <form wire:submit.prevent="createExercise">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Naam Oefening <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text" placeholder="bv. Bench Press" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 placeholder-gray-400">
                            @error('name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Moeilijkheid</label>
                                <select wire:model="difficulty" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900">
                                    <option value="Beginner">Beginner</option>
                                    <option value="Gemiddeld">Gemiddeld</option>
                                    <option value="Gevorderd">Gevorderd</option>
                                    <option value="Expert">Expert</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categorie</label>
                                <select wire:model="category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900">
                                    <option value="Kracht">Kracht</option>
                                    <option value="Conditie">Conditie</option>
                                    <option value="Balans">Balans</option>
                                    <option value="Flexibiliteit">Flexibiliteit</option>
                                    <option value="Fullbody">Fullbody</option>
                                    <option value="Benen">Benen</option>
                                    <option value="Armen">Armen</option>
                                    <option value="Core">Core</option>
                                    <option value="Rug">Rug</option>
                                    <option value="Bovenlichaam">Bovenlichaam</option>
                                    <option value="Onderlichaam">Onderlichaam</option>
                                    <option value="Cardio">Cardio</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Beschrijving <span class="text-red-500">*</span></label>
                            <textarea wire:model="description" rows="2" placeholder="Wat is het doel van deze oefening?" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900"></textarea>
                            @error('description') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Instructies <span class="text-red-500">*</span></label>
                            <textarea wire:model="instructions" rows="2" placeholder="Stap-voor-stap uitleg..." 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900"></textarea>
                            @error('instructions') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end items-center border-t border-gray-100 pt-6">
                    <div wire:loading wire:target="createExercise" class="mr-4 text-blue-600 flex items-center text-sm font-medium">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Bezig met opslaan...
                    </div>
                    
                    <button type="button" 
                        wire:click="cancelForm"
                        class="mr-4 text-gray-500 hover:text-gray-700 font-medium">
                        Annuleren
                    </button>

                    <button type="submit" 
                        wire:loading.attr="disabled"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-md">
                        {{ $editingId ? 'Update Opslaan' : 'Aanmaken' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Zoek op naam..." 
                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 sm:text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($exercises as $exercise)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden flex flex-col h-full relative">
                    
                    <div class="h-1 w-full bg-gradient-to-r from-blue-500 to-indigo-500"></div>

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
                        
                        <div class="text-xs text-gray-400 bg-gray-50 p-2 rounded border border-gray-100">
                            <strong>Instructie:</strong> {{ Str::limit($exercise['instructions'] ?? '...', 50) }}
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <button 
                            wire:click="editExercise('{{ $exercise['id'] }}')"
                            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-full hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="Bewerk oefening">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        
                        <button 
                            wire:click="deleteExercise('{{ $exercise['id'] }}')"
                            wire:confirm="Weet je zeker dat je '{{ $exercise['name'] }}' wilt verwijderen? Dit kan niet ongedaan worden gemaakt."
                            wire:loading.attr="disabled"
                            class="text-gray-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500"
                            aria-label="Verwijder oefening">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-12 text-center bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">Geen oefeningen gevonden</h3>
                    <p class="text-gray-500 mt-1">Probeer een andere zoekterm of voeg een nieuwe oefening toe.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>