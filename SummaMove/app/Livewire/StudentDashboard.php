<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class StudentDashboard extends Component
{
    public $exercises = [];

    public function mount()
    {
        try {
            $response = Http::withoutVerifying()->get('https://summa-move-api.vercel.app/api/exercises');
            $this->exercises = $response->json();
        } catch (\Exception $e) {
            $this->exercises = [];
        }
    }

    public function render()
    {
        return view('livewire.student-dashboard');
    }
}
