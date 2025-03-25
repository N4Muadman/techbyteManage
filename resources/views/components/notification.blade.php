<li class="dropdown pc-h-item"><a class="pc-head-link dropdown-toggle arrow-none me-0"
    data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
    aria-expanded="false"><svg class="pc-icon">
        <use xlink:href="#custom-notification"></use>
    </svg> <span class="badge bg-success pc-h-badge">{{ $notification->get()->count() }}</span></a>
<div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
    <div class="dropdown-header d-flex align-items-center justify-content-between">
        <h5 class="m-0">Thông báo</h5><a href="#!"
            class="btn btn-link btn-sm">Đánh đấu đã đọc tất cả</a>
    </div>
    <div class="dropdown-body text-wrap header-notification-scroll position-relative"
        style="max-height: calc(100vh - 215px)">
        <p class="text-span">Gần đây</p>
        @forelse ($notification->get() as $it)
        <div class="card mb-2">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0"><svg class="pc-icon text-primary">
                            <use xlink:href="#custom-sms">{{ $it->created_at }}</use>
                        </svg></div>
                    <div class="flex-grow-1 ms-3"><span class="float-end text-sm text-muted"></span>
                        <h5 class="text-body mb-2">{{ $it->name }}</h5>
                        <p class="mb-0">{{ $it->discription }}</p>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="empty-notice">Không có thông báo nào</div>
        @endforelse
    </div>
    <div class="text-center py-2"><a href="#!" class="link-danger">Xóa tất cả thông báo</a>
    </div>
</div>
</li>
