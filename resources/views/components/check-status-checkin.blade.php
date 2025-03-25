<div>
    @if ($status)
        <a href="#" class="btn btn-danger me-4" data-bs-toggle="modal" data-bs-target="#CheckoutModal">Checkout</a>
    @else
        <a href="{{ route('qrcode-checkin') }}" class="btn btn-success me-4" type="submit">checkin</a>
    @endif
</div>

