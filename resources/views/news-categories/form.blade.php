@csrf
<div class="form-grid">
    <label>Başlık<input name="title" value="{{ old('title', $category->title) }}" required maxlength="150"></label>
    <label>Slug<input name="slug" value="{{ old('slug', $category->slug) }}" required maxlength="160" pattern="[A-Za-z0-9_-]+"></label>
    <label>Resim<input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></label>
    <label>SEO başlığı<input name="seo_title" value="{{ old('seo_title', $category->seo_title) }}" maxlength="160"></label>
    <label class="wide">SEO açıklaması<textarea name="seo_description" maxlength="320">{{ old('seo_description', $category->seo_description) }}</textarea></label>
    <label class="wide">Açıklama<textarea name="description" maxlength="2000">{{ old('description', $category->description) }}</textarea></label>
    <label class="wide">HTML editörü
        <textarea name="content" rows="10" maxlength="100000">{{ old('content', $category->content) }}</textarea>
        <small>Güvenlik için yalnızca p, br, strong, em, liste, bağlantı ve h2/h3 etiketleri saklanır.</small>
    </label>
</div>
<section class="snippet">
    <small>Snippet önizleme</small>
    <strong id="snippet-title">{{ old('seo_title', $category->seo_title) ?: old('title', $category->title) ?: 'Kategori başlığı' }}</strong>
    <span id="snippet-url">{{ url('/haber-kategorileri') }}/{{ old('slug', $category->slug) ?: 'kategori-slug' }}</span>
    <p id="snippet-description">{{ old('seo_description', $category->seo_description) ?: 'SEO açıklamanız burada görünecek.' }}</p>
</section>
<button class="button" type="submit">Kaydet</button>
<a class="button secondary" href="{{ route('news-categories.index') }}">Vazgeç</a>
@push('scripts')
<script>
const bind = (source, target, fallback) => source.addEventListener('input', () => target.textContent = source.value || fallback);
bind(document.querySelector('[name="seo_title"]'), document.querySelector('#snippet-title'), document.querySelector('[name="title"]').value || 'Kategori başlığı');
bind(document.querySelector('[name="slug"]'), document.querySelector('#snippet-url'), '{{ url('/haber-kategorileri') }}/' + 'kategori-slug');
bind(document.querySelector('[name="seo_description"]'), document.querySelector('#snippet-description'), 'SEO açıklamanız burada görünecek.');
</script>
@endpush
