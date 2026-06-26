<<<<<<< HEAD
=======
@extends('admin.layout')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1); color: #667eea; font-size: 22px;">
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
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Low Stock (≤5)</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $lowStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 22px;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Out of Stock</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $outOfStock }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-collection"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Categories</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $categoriesCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border: none; border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea, #764ba2); box-shadow: 0 4px 12px rgba(102,126,234,0.3); font-weight: 600; font-size: 14px;">
            <i class="bi bi-plus-lg"></i> Create Product
        </a>
    </div>

    <div class="card card-modern">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-list-columns text-primary"></i>
                All Products
                <span class="badge rounded-pill" style="background: #eef2ff; color: #667eea; font-size: 11px; font-weight: 600;">{{ $totalProducts }} total</span>
            </span>
            <div class="d-flex gap-2">
                {{-- Category filter --}}
                <select id="category-filter" class="form-select form-select-sm" style="width: auto; border-radius: 8px; font-size: 13px; min-width: 150px;">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                {{-- Stock filter --}}
                <select id="stock-filter" class="form-select form-select-sm" style="width: auto; border-radius: 8px; font-size: 13px; min-width: 130px;">
                    <option value="">All stock</option>
                    <option value="low">Low Stock (≤5)</option>
                    <option value="out">Out of Stock</option>
                    <option value="in">In Stock</option>
                </select>
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                        <i class="bi bi-search" style="font-size: 12px; color: #9ca3af;"></i>
                    </span>
                    <input type="text" id="product-search" class="form-control border-start-0 ps-0" placeholder="Search products..." style="border-radius: 0 8px 8px 0; font-size: 13px;">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern" id="products-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 70px;">Image</th>
                            <th>Product</th>
                            <th style="width: 130px;">Category</th>
                            <th style="width: 120px;">Price</th>
                            <th style="width: 100px;">Stock</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stockStatus = $product->stock === 0 ? 'out' : ($product->stock <= 5 ? 'low' : 'in');
                                $stockColors = [
                                    'out' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'dot' => '#ef4444', 'label' => 'Out of Stock'],
                                    'low' => ['bg' => '#fef3c7', 'text' => '#92400e', 'dot' => '#f59e0b', 'label' => 'Low Stock'],
                                    'in'  => ['bg' => '#d1fae5', 'text' => '#065f46', 'dot' => '#10b981', 'label' => 'In Stock'],
                                ];
                                $sc = $stockColors[$stockStatus];
                            @endphp
                            <tr class="product-row"
                                data-category="{{ $product->category_id ?? '' }}"
                                data-stock="{{ $stockStatus }}"
                                data-search="{{ $product->name }} {{ $product->category->name ?? '' }}">
                                <td class="text-muted" style="font-size: 13px;">#{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-3" style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #f0f2f5;">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #f8f9fc; color: #d1d5db; font-size: 18px; border: 1px solid #f0f2f5;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 14px; color: #1e1e2f;">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="text-muted" style="font-size: 11px; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ Str::limit($product->description, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-custom" style="background: #f0f4ff; color: #4f6af5;">
                                        <i class="bi bi-tag me-1" style="font-size: 10px;"></i>
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-bold" style="font-size: 15px;">
                                    @if($product->has_discount)
                                        <span style="color: #9ca3af; text-decoration: line-through; font-size: 12px; font-weight: 500;">${{ number_format($product->price, 2) }}</span>
                                        <span class="d-block" style="color: #ef4444;">${{ number_format($product->final_price, 2) }}</span>
                                        <span class="badge rounded-pill" style="background: #fee2e2; color: #dc2626; font-size: 10px; font-weight: 600;">-{{ $product->discount_percent }}%</span>
                                        <span class="badge rounded-pill" style="background: #d1fae5; color: #065f46; font-size: 10px; font-weight: 600;"><i class="bi bi-check"></i> Active</span>
                                    @elseif($product->discount_status === 'scheduled')
                                        <span style="color: #1e1e2f;">${{ number_format($product->price, 2) }}</span>
                                        <span class="badge rounded-pill d-block mt-1" style="background: #dbeafe; color: #1e40af; font-size: 10px; font-weight: 600;">
                                            <i class="bi bi-clock"></i> Starts {{ $product->discount_start_at->format('M d, H:i') }}
                                        </span>
                                    @elseif($product->discount_status === 'expired')
                                        <span style="color: #1e1e2f;">${{ number_format($product->price, 2) }}</span>
                                        <span class="badge rounded-pill d-block mt-1" style="background: #f3f4f6; color: #9ca3af; font-size: 10px; font-weight: 600;">
                                            <i class="bi bi-clock-history"></i> Expired
                                        </span>
                                    @else
                                        <span style="color: #1e1e2f;">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-custom d-inline-flex align-items-center gap-1" style="background: {{ $sc['bg'] }}; color: {{ $sc['text'] }};">
                                        <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background: {{ $sc['dot'] }};"></span>
                                        {{ $sc['label'] }}
                                        <span style="font-weight: 700; margin-left: 2px;">{{ $product->stock }}</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary border-0" title="Edit Product" style="border-radius: 8px; padding: 6px 12px; color: #667eea;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary border-0" title="Delete Product" style="border-radius: 8px; padding: 6px 12px; color: #ef4444;" onclick="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-5">
                                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <h6 style="color: #6b7280; font-weight: 600;">No products yet</h6>
                                        <p class="text-muted mb-0" style="font-size: 13px;">Get started by creating your first product.</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary mt-3" style="border-radius: 8px;">
                                            <i class="bi bi-plus-lg"></i> Create Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($products->count() > 0)
            <div class="card-footer bg-white border-top-0 px-3 py-2 d-flex justify-content-between align-items-center" style="border-radius: 0 0 16px 16px;">
                <small class="text-muted">Showing {{ $products->count() }} product{{ $products->count() !== 1 ? 's' : '' }}</small>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const stockFilter = document.getElementById('stock-filter');
    const searchInput = document.getElementById('product-search');
    const rows = document.querySelectorAll('.product-row');

    function filterTable() {
        const catVal = categoryFilter.value;
        const stockVal = stockFilter.value;
        const searchVal = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const category = row.dataset.category;
            const stock = row.dataset.stock;
            const searchText = row.dataset.search.toLowerCase();

            const matchesCat = !catVal || category === catVal;
            const matchesStock = !stockVal || stock === stockVal;
            const matchesSearch = !searchVal || searchText.includes(searchVal);

            row.style.display = matchesCat && matchesStock && matchesSearch ? '' : 'none';
            if (matchesCat && matchesStock && matchesSearch) visibleCount++;
        });

        const footer = document.querySelector('.card-footer small');
        if (footer) {
            footer.textContent = `Showing ${visibleCount} product${visibleCount !== 1 ? 's' : ''}`;
        }
    }

    categoryFilter.addEventListener('change', filterTable);
    stockFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
});
</script>
@endsection
>>>>>>> 270228540f02abaf2f4f0faeff3c16802c8a4e67
