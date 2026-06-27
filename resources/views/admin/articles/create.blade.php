@extends('layouts.admin')

@section('title', 'Create Article')
@section('header_title', 'Write New Article')

@section('content')
<div class="admin-card">
  <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Title & Slug -->
    <div class="form-group">
      <label for="title" class="form-label">Article Title <span style="color: var(--danger)">*</span></label>
      <input type="text" name="title" id="title" class="form-control" placeholder="Enter article title" value="{{ old('title') }}" required>
    </div>

    <div class="form-group">
      <label for="slug" class="form-label">Slug (Optional)</label>
      <input type="text" name="slug" id="slug" class="form-control" placeholder="e.g., budget-2026-announcement (leave empty to auto-generate from title)" value="{{ old('slug') }}">
    </div>

    <!-- Category & Thumbnail -->
    <div class="form-row form-row-2">
      <div class="form-group">
        <label for="category_id" class="form-label">Select Category <span style="color: var(--danger)">*</span></label>
        <select name="category_id" id="category_id" class="form-control" required>
          <option value="">Select Category</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_bn }} ({{ $cat->name_en }})</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="thumbnail" class="form-label">Thumbnail Image (2MB Max)</label>
        <input type="file" name="thumbnail" id="thumbnail" class="form-control" style="padding: 8px 12px;">
      </div>
    </div>

    <!-- Excerpt -->
    <div class="form-group">
      <label for="excerpt" class="form-label">Short Description (Excerpt - Optional)</label>
      <textarea name="excerpt" id="excerpt" class="form-control" placeholder="Short news description (leave empty to auto-generate from main content)">{{ old('excerpt') }}</textarea>
    </div>

    <!-- Content -->
    <div class="form-group">
      <label for="content" class="form-label">Main News Content (HTML supported) <span style="color: var(--danger)">*</span></label>
      <textarea name="content" id="content" class="form-control" style="min-height: 250px;" placeholder="Enter detailed content">{{ old('content') }}</textarea>
    </div>

    <!-- Flags Checkboxes -->
    <div class="form-group">
      <label class="form-label">Article Placement Settings (নিবন্ধ স্থাপনের সেটিংস)</label>
      <div style="display: flex; flex-wrap: wrap; gap: 24px; margin-top: 10px;">
        <label class="form-check">
          <input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking') ? 'checked' : '' }}>
          <span>Show in Breaking News Ticker (ব্রেকিং নিউজ টিকারে দেখান)</span>
        </label>
        
        <label class="form-check">
          <input type="checkbox" name="is_lead" value="1" {{ old('is_lead') ? 'checked' : '' }}>
          <span>Show as Lead News (প্রধান সংবাদ হিসেবে দেখান)</span>
        </label>
        
        <label class="form-check">
          <input type="checkbox" name="is_sub_lead" value="1" {{ old('is_sub_lead') ? 'checked' : '' }}>
          <span>Show as Sub Lead News (উপ-প্রধান সংবাদ হিসেবে দেখান)</span>
        </label>

        <label class="form-check">
          <input type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
          <span>Show in Popular (সবচেয়ে জনপ্রিয়তে দেখান)</span>
        </label>

        <label class="form-check">
          <input type="checkbox" name="is_latest" value="1" {{ old('is_latest') ? 'checked' : '' }}>
          <span>Show in Latest (সর্বশেষ সংবাদে দেখান)</span>
        </label>

        <label class="form-check">
          <input type="checkbox" name="is_special_banner" value="1" {{ old('is_special_banner') ? 'checked' : '' }}>
          <span>Show as Special Banner (বিশেষ ব্যানার হিসেবে দেখান)</span>
        </label>
      </div>
    </div>

    <!-- Tags selection -->
    <div class="form-group" style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px;">
      <label class="form-label">Select Tags</label>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; margin-top: 10px;">
        @foreach($tags as $tag)
          <label class="form-check" style="background: rgba(255,255,255,0.02); padding: 8px; border-radius: 6px; border: 1px solid var(--border-color);">
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}>
            <span style="font-size: 13px;">{{ $tag->name_bn }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <!-- Status & Published At -->
    <div class="form-row form-row-2" style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px;">
      <div class="form-group">
        <label for="status" class="form-label">Status <span style="color: var(--danger)">*</span></label>
        <select name="status" id="status" class="form-control" required>
          <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
          <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
        </select>
      </div>

      <div class="form-group">
        <label for="published_at" class="form-label">Publish Date/Time (leave empty for current time)</label>
        <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
      </div>
    </div>

    <!-- SEO Options -->
    <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 20px; background: rgba(0,0,0,0.1); padding: 20px; border-radius: 8px;">
      <h3 class="card-title" style="font-size: 15px; margin-bottom: 16px;"><i class="fas fa-search-plus"></i> Search Engine Optimization (SEO Settings - Optional)</h3>
      
      <div class="form-group">
        <label for="meta_title" class="form-label">Meta Title</label>
        <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Title for search engines" value="{{ old('meta_title') }}">
      </div>
      
      <div class="form-group">
        <label for="meta_description" class="form-label">Meta Description</label>
        <textarea name="meta_description" id="meta_description" class="form-control" placeholder="Short description for search results">{{ old('meta_description') }}</textarea>
      </div>
      
      <div class="form-group">
        <label for="keywords" class="form-label">Keywords (comma separated)</label>
        <input type="text" name="keywords" id="keywords" class="form-control" placeholder="e.g. budget, tax, 2026" value="{{ old('keywords') }}">
      </div>
    </div>

    <!-- Form Submit -->
    <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
      <a href="{{ route('admin.articles.index') }}" class="btn-admin btn-admin-secondary">Cancel</a>
      <button type="submit" class="btn-admin btn-admin-primary">Save Article</button>
    </div>
  </form>
</div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
      selector: '#content',
      height: 400,
      menubar: false,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | blocks | bold italic blockquote | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code fullscreen | help',
      toolbar_mode: 'sliding',
      skin: 'oxide-dark',
      content_css: 'dark',
      branding: false,
      promotion: false,
      image_title: true,
      automatic_uploads: true,
      file_picker_types: 'image',
      file_picker_callback: (cb, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.addEventListener('change', (e) => {
          const file = e.target.files[0];
          const reader = new FileReader();
          reader.addEventListener('load', () => {
            const id = 'blobid' + (new Date()).getTime();
            const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
            const base64 = reader.result.split(',')[1];
            const blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);

            cb(blobInfo.blobUri(), { title: file.name });
          });
          reader.readAsDataURL(file);
        });

        input.click();
      }
    });
  });
</script>
@endsection
