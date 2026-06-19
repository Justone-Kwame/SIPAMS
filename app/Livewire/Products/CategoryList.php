<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryList extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deleteId = null;
    public bool $confirmDelete = false;

    protected $queryString = ['search'];

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmDelete = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            Category::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Category deleted.');
        }
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $categories = Category::when($this->search, function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
              ->orWhere('description', 'like', "%{$this->search}%");
        })->orderBy('name')->paginate(15);

        return view('livewire.products.category-list', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
