<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class StudentDashboard extends Component
{
    // Data Holders
    public $allExercises = [];
    
    // UI State
    public $search = ''; 
    public $categoryFilter = '';
    public $viewingExercise = null; 

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
    public function viewExercise($id)
    {
        $exercise = collect($this->allExercises)->firstWhere('id', $id);
        if ($exercise) {
            $this->viewingExercise = $exercise;
        }
    }

    public function closeViewExercise()
    {
        $this->viewingExercise = null;
    }

    public function render()
    {
        return view('livewire.student-dashboard', [
            'exercises' => $this->filteredExercises
        ]);
    }
}