<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class AdminDashboard extends Component
{
    public $exercises = [];

    public function mount()
    {
        $this->refreshExercises();
    }
    //get request
    public function refreshExercises()
    {
        try {
            $response = Http::withoutVerifying()->get('https://summa-move-api.vercel.app/api/exercises');
            $this->exercises = $response->json();
        } catch (\Exception $e) {
            $this->exercises = [];
        }
    }
    //DELETE request
    public function deleteExercise($id)
    {
        $apiKey = env('sk_6d9bfbf73ae5f86093a4ed3ba2539faa489fc15f8d61a1c022dde338175eb270');

        $response = Http::withoutVerifying()
            ->withHeaders(['x-api-key' => $apiKey])
            ->delete('https://summa-move-api.vercel.app/api/exercises/' . $id);

        if ($response->successful()) {
            $this->refreshExercises();
            session()->flash('message', 'Exercise deleted successfully!');
        } else {
            session()->flash('error', 'Could not delete exercise.');
        }
    }
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
