<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsCategoryController extends Controller
{
    public function index(): View
    {
        return view('news-categories.index', [
            'categories' => NewsCategory::query()->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('news-categories.create', ['category' => new NewsCategory()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $category = new NewsCategory();
        $this->saveCategory($request, $category);

        return redirect()->route('news-categories.index')->with('status', 'Haber kategorisi oluşturuldu.');
    }

    public function edit(NewsCategory $newsCategory): View
    {
        return view('news-categories.edit', ['category' => $newsCategory]);
    }

    public function update(Request $request, NewsCategory $newsCategory): RedirectResponse
    {
        $this->saveCategory($request, $newsCategory);

        return redirect()->route('news-categories.index')->with('status', 'Haber kategorisi güncellendi.');
    }

    public function destroy(NewsCategory $newsCategory): RedirectResponse
    {
        if ($newsCategory->image_path) {
            Storage::disk('public')->delete($newsCategory->image_path);
        }

        $newsCategory->delete();

        return redirect()->route('news-categories.index')->with('status', 'Haber kategorisi silindi.');
    }

    private function saveCategory(Request $request, NewsCategory $category): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'slug' => [
                'required', 'string', 'max:160', 'alpha_dash:ascii',
                Rule::unique('news_categories', 'slug')->ignore($category->exists ? $category->getKey() : null),
            ],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'seo_title' => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'description' => ['nullable', 'string', 'max:2000'],
            'content' => ['nullable', 'string', 'max:100000'],
        ]);

        $data['slug'] = Str::slug($data['slug']);
        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('news-categories', 'public');
        }
        unset($data['image']);
        $data['content'] = strip_tags($data['content'] ?? '', '<p><br><strong><em><ul><ol><li><a><h2><h3>');

        $category->fill($data)->save();
    }
}
