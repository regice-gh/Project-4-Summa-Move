<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class AdminDashboard extends Component
{
    
    public $allExercises = []; 
    
    public $name = '';
    public $description = '';
    public $instructions = '';
    public $category = 'Kracht';
    public $difficulty = 'Beginner';
    
    public $showCreateForm = false;
    public $search = ''; 
    public $editingId = null;

    public function mount()
    {
        $this->refreshExercises();
    }

    public function refreshExercises()
    {
        $response = Http::withoutVerifying()->get('https://summa-move-api.vercel.app/api/exercises');
        
        $this->allExercises = json_decode($response->body(), true) ?? [];
    }

    
    public function getFilteredExercisesProperty()
    {
        return collect($this->allExercises)
            ->filter(function ($item) {
                
                if ($this->search && stripos($item['name'], $this->search) === false) {
                    return false;
                }
                return true;
            })
            ->all();
    }

    public function createExercise()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'required',
            'instructions' => 'required',
        ]);

        $apiKey = env('ADMIN_API_KEY');

        $response = Http::withoutVerifying()
            ->withHeaders(['x-api-key' => $apiKey])
            ->post('https://summa-move-api.vercel.app/api/exercises', [
                'name' => $this->name,
                'description' => $this->description,
                'instructions' => $this->instructions,
                'category' => $this->category,
                'difficulty' => $this->difficulty,
            ]);

        if ($response->successful()) {
            $this->reset(['name', 'description', 'instructions', 'showCreateForm']);
            $this->category = 'Kracht';
            $this->difficulty = 'Beginner';
            $this->refreshExercises();
            session()->flash('message', 'Oefening succesvol aangemaakt!');
        } else {
            session()->flash('error', 'Fout: ' . $response->body());
        }
    }
    //PUT request
    public function editExercise($id)
    {
        $exercise = collect($this->allExercises)->firstWhere('id', $id);

        if ($exercise) {
            $this->editingId = $exercise['id'];
            $this->name = $exercise['name'];
            $this->description = $exercise['description'];
            $this->instructions = $exercise['instructions'] ?? ''; 
            $this->category = $exercise['category'];
            $this->difficulty = $exercise['difficulty'];

            $this->showCreateForm = true;
        }
    }

    public function cancelForm()
    {
        $this->reset(['name', 'description', 'instructions', 'editingId', 'showCreateForm']);
        $this->category = 'Kracht';
        $this->difficulty = 'Beginner';
    }

    public function updateExercise()
    {
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'required',
            'instructions' => 'required',
        ]);

        $apiKey = env('SUMMA_API_KEY');

        
        $response = Http::withoutVerifying()
            ->withHeaders(['x-api-key' => $apiKey])
            ->put('https://summa-move-api.vercel.app/api/exercises/' . $this->editingId, [
                'name' => $this->name,
                'description' => $this->description,
                'instructions' => $this->instructions,
                'category' => $this->category,
                'difficulty' => $this->difficulty,
            ]);

        if ($response->successful()) {
            $this->cancelForm(); 
            $this->refreshExercises();
            session()->flash('message', 'Oefening succesvol geüpdatet!');
        } else {
            session()->flash('error', 'Update mislukt: ' . $response->body());
        }
    }

    public function deleteExercise($id)
    {
        $apiKey = env('ADMIN_API_KEY');
        $response = Http::withoutVerifying()
            ->withHeaders(['x-api-key' => $apiKey])
            ->delete('https://summa-move-api.vercel.app/api/exercises/' . $id);

        if ($response->successful()) {
            $this->refreshExercises();
            session()->flash('message', 'Oefening verwijderd!');
        }
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'exercises' => $this->filteredExercises 
        ]);
    }
}