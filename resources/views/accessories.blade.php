@extends('layouts.app')

@section('title', 'Accessories')

@section('content')

<section class="shop-hero">
    <h1 class="shop-title">Accessories</h1>

    <!-- SUCCESS MESSAGE -->
    <div id="cart-message"
         style="display:none; position:fixed; top:20px; right:20px; background:#28a745; color:#fff; padding:12px 18px; border-radius:8px; z-index:9999;">
        Added to cart successfully
    </div>

    <div class="service-cards">
        @forelse($products as $product)
            <div class="shop-card">

                <!-- CLICKABLE PRODUCT -->
                <a href="{{ route('product.show', $product->id) }}"
                   style="text-decoration: none; color: inherit; display:block;">
                   
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">

                    <div style="padding: 10px; text-align: center; color: white;">
                        <p><strong>Rs {{ number_format($product->price, 2) }}</strong></p>
                        <p>{{ $product->description }}</p>
                        <p>Stock: {{ $product->stock }}</p>
                    </div>
                </a>

                <!-- ADD TO CART -->
                <div style="padding: 10px; text-align: center;">
                    @if($product->stock > 0)

                        <form action="{{ route('cart.add.ajax') }}"
                              method="POST"
                              class="add-to-cart-form">

                            @csrf

                            <input type="hidden" name="type" value="product">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit"
                                style="background:#000;color:#fff;padding:10px 18px;border:none;border-radius:8px;cursor:pointer;">
                                Add to Cart
                            </button>

                        </form>

                    @else
                        <button disabled
                            style="background:#777;color:#fff;padding:10px 18px;border:none;border-radius:8px;">
                            Out of Stock
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <p style="text-align:center; color:white;">No accessories available.</p>
        @endforelse
    </div>
</section>

@endsection


@section('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

    $(document).on('submit', '.add-to-cart-form', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let formData = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,

            success: function (response) {

                console.log(response);

                // Update cart count
                if (response.cart_count !== undefined) {
                    $('#cart-count').text(response.cart_count);
                }

                // Show success message
                $('#cart-message').fadeIn().delay(1500).fadeOut();
            },

            error: function (xhr) {
                alert('Error adding to cart');
            }
        });

    });

});
</script>

@endsection