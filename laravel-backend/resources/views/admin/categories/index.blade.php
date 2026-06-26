<<<<<<< HEAD
=======
@extends('admin.layout')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1); color: #667eea; font-size: 22px;">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Total Categories</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $totalCategories }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-box"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Total Products</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $totalProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 22px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">With Products</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $hasProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 22px;">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Empty Categories</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $emptyCategories }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border: none; border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea, #764ba2); box-shadow: 0 4px 12px rgba(102,126,234,0.3); font-weight: 600; font-size: 14px;">
            <i class="bi bi-plus-lg"></i> Create Category
        </a>
    </div>

    <div class="card card-modern">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-list-columns text-primary"></i>
                All Categories
                <span class="badge rounded-pill" style="background: #eef2ff; color: #667eea; font-size: 11px; font-weight: 600;">{{ $totalCategories }} total</span>
            </span>
            <div class="d-flex gap-2">
                {{-- Product presence filter --}}
                <select id="products-filter" class="form-select form-select-sm" style="width: auto; border-radius: 8px; font-size: 13px; min-width: 140px;">
                    <option value="">All categories</option>
                    <option value="has">With Products</option>
                    <option value="empty">Empty</option>
                </select>
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                        <i class="bi bi-search" style="font-size: 12px; color: #9ca3af;"></i>
                    </span>
                    <input type="text" id="category-search" class="form-control border-start-0 ps-0" placeholder="Search categories..." style="border-radius: 0 8px 8px 0; font-size: 13px;">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern" id="categories-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 70px;">Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 110px;">Products</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="category-row"
                                data-products-count="{{ $category->products_count }}"
                                data-search="{{ $category->name }} {{ $category->description ?? '' }}">
                                <td class="text-muted" style="font-size: 13px;">#{{ $category->id }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-3" style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #f0f2f5;">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #eef2ff; color: #667eea; font-size: 18px; border: 1px solid #f0f2f5;">
                                            <i class="bi bi-folder"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; background: #eef2ff; color: #667eea; font-size: 13px; font-weight: 600;">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size: 14px; color: #1e1e2f;">{{ $category->name }}</div>
                                            <div class="text-muted" style="font-size: 11px;">/{{ $category->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color: #6b7280; font-size: 13px; max-width: 250px;">
                                    @if($category->description)
                                        <span class="text-truncate d-inline-block" style="max-width: 250px;">{{ Str::limit($category->description, 80) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">No description</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->products_count > 0)
                                        <span class="badge badge-custom" style="background: #d1fae5; color: #065f46;">
                                            <i class="bi bi-box me-1" style="font-size: 10px;"></i>
                                            {{ $category->products_count }} product{{ $category->products_count !== 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="badge badge-custom" style="background: #f3f4f6; color: #9ca3af;">
                                            <i class="bi bi-dash me-1" style="font-size: 10px;"></i>
                                            Empty
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary border-0" title="Edit Category" style="border-radius: 8px; padding: 6px 12px; color: #667eea;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary border-0" title="Delete Category" style="border-radius: 8px; padding: 6px 12px; color: #ef4444;" onclick="return confirm('Delete category \"{{ addslashes($category->name) }}\"? This cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">
                                            <i class="bi bi-collection"></i>
                                        </div>
                                        <h6 style="color: #6b7280; font-weight: 600;">No categories yet</h6>
                                        <p class="text-muted mb-0" style="font-size: 13px;">Organize your products by creating your first category.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary mt-3" style="border-radius: 8px;">
                                            <i class="bi bi-plus-lg"></i> Create Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->count() > 0)
            <div class="card-footer bg-white border-top-0 px-3 py-2 d-flex justify-content-between align-items-center" style="border-radius: 0 0 16px 16px;">
                <small class="text-muted">Showing {{ $categories->count() }} categor{{ $categories->count() !== 1 ? 'ies' : 'y' }}</small>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsFilter = document.getElementById('products-filter');
    const searchInput = document.getElementById('category-search');
    const rows = document.querySelectorAll('.category-row');

    function filterTable() {
        const filterVal = productsFilter.value;
        const searchVal = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const productsCount = parseInt(row.dataset.productsCount);
            const searchText = row.dataset.search.toLowerCase();

            const matchesFilter = !filterVal ||
                (filterVal === 'has' && productsCount > 0) ||
                (filterVal === 'empty' && productsCount === 0);
            const matchesSearch = !searchVal || searchText.includes(searchVal);

            row.style.display = matchesFilter && matchesSearch ? '' : 'none';
            if (matchesFilter && matchesSearch) visibleCount++;
        });

        const footer = document.querySelector('.card-footer small');
        if (footer) {
            footer.textContent = `Showing ${visibleCount} categor${visibleCount !== 1 ? 'ies' : 'y'}`;
        }
    }

    productsFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
});
</script>
@endsection
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
