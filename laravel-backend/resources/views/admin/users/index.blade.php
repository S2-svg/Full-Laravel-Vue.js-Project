@extends('admin.layout')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(102,126,234,0.1); color: #667eea; font-size: 22px;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Total Users</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $totalUsers }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); color: #10b981; font-size: 22px;">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">New This Month</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $newThisMonth }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245,158,11,0.1); color: #f59e0b; font-size: 22px;">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">With Orders</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $withOrders }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 22px;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.3px;">Admins</div>
                        <div class="fw-bold" style="font-size: 24px; color: #1e1e2f;">{{ $adminCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table Card --}}
    <div class="card card-modern">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-list-columns text-primary"></i>
                All Users
                <span class="badge rounded-pill" style="background: #eef2ff; color: #667eea; font-size: 11px; font-weight: 600;">{{ $totalUsers }} total</span>
            </span>
            <div class="d-flex gap-2">
                {{-- Role filter --}}
                <select id="role-filter" class="form-select form-select-sm" style="width: auto; border-radius: 8px; font-size: 13px; min-width: 130px;">
                    <option value="">All roles</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;">
                        <i class="bi bi-search" style="font-size: 12px; color: #9ca3af;"></i>
                    </span>
                    <input type="text" id="user-search" class="form-control border-start-0 ps-0" placeholder="Search name or email..." style="border-radius: 0 8px 8px 0; font-size: 13px;">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern" id="users-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th style="width: 100px;">Role</th>
                            <th style="width: 100px;">Orders</th>
                            <th style="width: 120px;">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="user-row" data-role="{{ $user->role }}" data-search="{{ $user->name }} {{ $user->email }}">
                                <td class="text-muted" style="font-size: 13px;">#{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: {{ $user->role === 'admin' ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#eef2ff' }}; color: {{ $user->role === 'admin' ? '#fff' : '#667eea' }}; font-size: 14px; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size: 14px; color: #1e1e2f;">{{ $user->name }}</div>
                                            @if($user->phone)
                                                <div class="text-muted" style="font-size: 11px;">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="color: #6b7280; font-size: 13px;">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-envelope" style="font-size: 12px; color: #9ca3af;"></i>
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-custom d-inline-flex align-items-center gap-1" style="background: #eef2ff; color: #667eea;">
                                            <i class="bi bi-shield-check" style="font-size: 11px;"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="badge badge-custom d-inline-flex align-items-center gap-1" style="background: #f3f4f6; color: #6b7280;">
                                            <i class="bi bi-person" style="font-size: 11px;"></i>
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->orders_count > 0)
                                        <span class="fw-semibold" style="color: #1e1e2f; font-size: 14px;">{{ $user->orders_count }}</span>
                                        <small class="text-muted" style="font-size: 11px;">order{{ $user->orders_count !== 1 ? 's' : '' }}</small>
                                    @else
                                        <span class="text-muted" style="font-size: 13px;">—</span>
                                    @endif
                                </td>
                                <td style="color: #6b7280; font-size: 13px;">
                                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <div style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h6 style="color: #6b7280; font-weight: 600;">No users yet</h6>
                                        <p class="text-muted mb-0" style="font-size: 13px;">Users will appear here once they register.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->count() > 0)
            <div class="card-footer bg-white border-top-0 px-3 py-2 d-flex justify-content-between align-items-center" style="border-radius: 0 0 16px 16px;">
                <small class="text-muted">Showing {{ $users->count() }} user{{ $users->count() !== 1 ? 's' : '' }}</small>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleFilter = document.getElementById('role-filter');
    const searchInput = document.getElementById('user-search');
    const rows = document.querySelectorAll('.user-row');

    function filterTable() {
        const roleVal = roleFilter.value.toLowerCase();
        const searchVal = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const role = row.dataset.role;
            const searchText = row.dataset.search.toLowerCase();
            const matchesRole = !roleVal || role === roleVal;
            const matchesSearch = !searchVal || searchText.includes(searchVal);
            row.style.display = matchesRole && matchesSearch ? '' : 'none';
            if (matchesRole && matchesSearch) visibleCount++;
        });

        const footer = document.querySelector('.card-footer small');
        if (footer) {
            footer.textContent = `Showing ${visibleCount} user${visibleCount !== 1 ? 's' : ''}`;
        }
    }

    roleFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
});
</script>
@endsection
