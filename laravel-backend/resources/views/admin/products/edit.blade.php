@extends('admin.layout')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="discount_percent" class="form-label">Discount (%)</label>
                        <div class="input-group">
                            <input type="number" min="0" max="100" name="discount_percent" id="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror" value="{{ old('discount_percent', $product->discount_percent) }}">
                            <span class="input-group-text">%</span>
                            @error('discount_percent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Scheduled Discount Dates --}}
                @php
                    $startVal = old('discount_start_at', $product->discount_start_at ? $product->discount_start_at->format('Y-m-d\TH:i') : '');
                    $endVal = old('discount_end_at', $product->discount_end_at ? $product->discount_end_at->format('Y-m-d\TH:i') : '');
                @endphp
                <div class="card border-0" style="background: #f8f9fc; border-radius: 12px;">
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-2" style="font-size: 14px; color: #1e1e2f;">
                            <i class="bi bi-calendar-event me-1"></i> Schedule Discount
                        </h6>
                        <p class="text-muted small mb-3">Leave both fields empty for an instant, permanent discount.</p>
                        @if($product->discount_status === 'expired')
                            <div class="alert alert-warning py-2 mb-3" style="font-size: 13px; border: none; border-radius: 8px;">
                                <i class="bi bi-clock-history me-1"></i> This discount has expired. Adjust the dates or percentage to reactivate.
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="discount_start_at" class="form-label" style="font-size: 13px;">Start Date</label>
                                <input type="datetime-local" name="discount_start_at" id="discount_start_at" class="form-control @error('discount_start_at') is-invalid @enderror" value="{{ $startVal }}">
                                @error('discount_start_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_end_at" class="form-label" style="font-size: 13px;">End Date</label>
                                <input type="datetime-local" name="discount_end_at" id="discount_end_at" class="form-control @error('discount_end_at') is-invalid @enderror" value="{{ $endVal }}">
                                @error('discount_end_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3"></div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100" style="object-fit: cover;">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
