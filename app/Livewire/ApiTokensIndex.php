<?php

namespace App\Livewire;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Component;
use Livewire\WithPagination;

class ApiTokensIndex extends Component
{
    use WithPagination;

    public $searchTerm;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        $api_tokens = PersonalAccessToken::where('name', 'like', $searchTerm)->orderBy('id', 'desc')->paginate();

        return view('livewire.api-tokens-index', compact('api_tokens'));
    }
}
