<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Category;

class CategoryForm extends Component
{
    public ?int $categoryId = null;
    public string $name = '';
    public string $description = '';

    public function mount(?int $categoryId = null): void
    {
        if ($categoryId) {
            $this->categoryId = $categoryId;
            $category = Category::findOrFail($categoryId);
            $this->name = $category->name;
            $this->description = $category->description ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name' . ($this->categoryId ? ",{$this->categoryId}" : ''),
            'description' => 'nullable|string|max:1000',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
        ];

        if ($this->categoryId) {
            Category::findOrFail($this->categoryId)->update($data);
            session()->flash('success', 'Category updated.');
        } else {
            Category::create($data);
            session()->flash('success', 'Category created.');
        }

        $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.category-form')->layout('layouts.app');
    }
}
