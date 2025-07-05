<div class="col-md-3 mb-4">
    <div class="card stat-card border-{{ $color ?? 'primary' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $title }}</h6>
                    <h3 class="card-title mb-0">{{ $value }}</h3>
                </div>
                <div class="icon-circle bg-{{ $color ?? 'primary' }}-light">
                    <i class="bi bi-{{ $icon }} text-{{ $color ?? 'primary' }}"></i>
                </div>
            </div>
            @isset($footer)
            <div class="mt-2">
                <small class="text-{{ $footerColor ?? 'success' }}">
                    <i class="bi bi-{{ $footerIcon ?? 'arrow-up' }} me-1"></i> {{ $footer }}
                </small>
            </div>
            @endisset
        </div>
    </div>
</div>