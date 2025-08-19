<script>
    //==================== GLOBAL ====================//
    function updateCartItemCount(count) {
        $('#cartItem').text(count.cartItemCount);
        if ($('.addToCartHeader').hasClass('text--black')) {
            $('.addToCartHeader').addClass('text--base').removeClass('text--black');
        }

        if (count.cartItemCount > 1) {
            $('#cartItem').text(count.cartItemCount);
        } else {
            if (!$('#cartItem').length) {
                $('.addToCartHeader').append(`<span class='cart--stump' id='cartItem'>${count.cartItemCount}</span>`);

            }
        }

        $('.price-details').text(`Price Details (${count.cartItemCount} Items)`);
        if (count.cartItemCount <= 0) {
            $('.checkoutUrlBtn').addClass('disabled');
            $('#cartItemsContainer').html(
                `<div class="text-muted text-center noProduct" colspan="100%" data-label="cart-item">No product added to cart</div>`
            );

            $('#cartItem').remove();
            $('.addToCartHeader').removeClass('text--base').addClass('text--black');

        } else {
            $('.checkoutUrlBtn').removeClass('disabled');
        }
    }

    function updateTotalPrice(checkCoupon = null) {
        let total = 0;

        $('.offcanvas-body .total-amount').each(function() {
            const amount = parseFloat(
                $(this).text()
                .replace("{{ $general->cur_sym }}", '')
                .replace(/\s/g, '')
                .replace(/,/g, '')
            );
            total += amount;
        });

        const formattedTotal = "{{ $general->cur_sym }}" + total.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        let finalAmount = total;

        $('.productFinalAmount').text(
            "{{ $general->cur_sym }}" + truncateToTwo(finalAmount).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );


        let selectedShipping = $('.shippingCharge').find(':selected');
        let shippingCharge = 0;
        if (selectedShipping && selectedShipping.val()) {
            shippingCharge = parseFloat(selectedShipping.data('charge')) || 0;

        }
        let totalFinalAmount = finalAmount + shippingCharge;

        $('.totalFinalAmount').text(
            "{{ $general->cur_sym }}" + truncateToTwo(totalFinalAmount).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );
        $('.coreProductPrice').text(finalAmount);

        $('.paymentPrice').val(
            truncateToTwo(totalFinalAmount)
        );

    }

    function updateQuantity(productId, quantity) {

        $.get("{{ route('cart.update.quantity') }}", {
            productId,
            quantity
        }, function(response) {
            $('.total-amount-' + productId).text("{{ $general->cur_sym }}" + response.totalAmount);
            $('[data-product_id="' + productId + '"]').val(response.quantity);
            updateTotalPrice(response.checkCoupon);
        });
    }

    function renderCartItem(cartItem) {
        const imagePath = "{{ asset('assets/images/backend/product/thumb_') }}" + cartItem.image;
        const totalPrice = (cartItem.quantity * cartItem.price).toFixed(2);
        const disableDecrement = cartItem.quantity <= 1 ? 'disabled' : '';

        return `
            <div class="col-lg-12 itemMainDiv mb-4" data-product_id="${cartItem.id}">
                <div class="cart--item d-flex gap--32">
                    <div class="thumb--wrap radius--8 flex-shrink-0 position-relative">
                    
                        <img class="fit--img radius--8" src="${imagePath}" alt="product-image">
                        <button class="remove--item position-absolute d-flex justify-content-center align-items-center text--danger remove-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                     </div>

                        <div class="content--wrap d-flex flex-column justify-content-between">
                            <div class="title--wrap">
                                <p class="fs--14 fw--500 mb-2">${cartItem.category}</p>
                                <h6 class="fs--18 fw--500">${cartItem.name}</h6>
                            </div>

                        <div class="price--wrap d-flex justify-content-between align-items-center">
                            <div class="quantity_box border--base d-flex justify-content-between align-items-center">
                              
                                <button type="button" class="counter-btn decrement"
                                    data-product_id="${cartItem.id}" ${disableDecrement}>
                                    <i class="fa fa-minus"></i>
                                </button>
                                <input class="count-input count" data-product_id="${cartItem.id}"
                                    type="number" id="quantityInput"
                                    value="${cartItem.quantity}">
                                  <button type="button" class="counter-btn increment"
                                    data-product_id="${cartItem.id}">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                             <h6 class="mb-0 total-amount total-amount-${cartItem.id}">
                                {{ $general->cur_sym }}${totalPrice}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    //==================== EVENT BINDING ====================//
    $(document).on('click', '.addToCart', function() {
        'use strict';
        const productId = $(this).data('product_id');
        const thisObject = $(this);
        if (!productId) return;
        $.get("{{ route('cart.add') }}", {
            productId,
            quantity: 1
        }, function(response) {
            const {
                message,
                data,
                cartItem,
                count,
                cartItemCount,
                checkCoupon
            } = response;


            if (data && data.replaceCart) {
                var modal = $("#otherFoodsModal");
                $("#confirmReplaceCart").attr('data-product-id', productId);
                $("#confirmReplaceCart").attr('data-quantity', 1);
                modal.modal('show');
                return false;

            }
            if (data && !(data.replaceCart)) {
                notify('error', data.message);
            }

            if (message) notify('success', message);

            updateCartItemCount(response);
            $('.noProduct').remove();

            if (cartItem) {
                const productDiv = $(`.itemMainDiv[data-product_id="${cartItem.id}"]`);
                if (productDiv.length > 0) {
                    // Update only quantity and total price
                    productDiv.find('.count').val(cartItem.quantity);

                    productDiv.find(`.total-amount-${cartItem.id}`).text("{{ $general->cur_sym }}" + (
                        cartItem.quantity * cartItem.price).toFixed(2));
                    productDiv.find('.count').siblings('.decrement').prop('disabled', false);
                } else {

                    // Not exist, so append as new
                    $('#cartItemsContainer').prepend(renderCartItem(cartItem));


                    const $img1 = thisObject.find('img').eq(0);
                    const $img2 = thisObject.find('img').eq(1);
                    $img1.toggleClass('d-block d-none');
                    $img2.toggleClass('d-block d-none');
                }
            }

            updateTotalPrice(checkCoupon);
        }).fail(function(xhr) {
            const msg = xhr.status === 400 ?
                'This product is already added to the cart.' :
                (xhr.responseJSON?.error || 'Something went wrong');
            notify('error', msg);

        });
    });

    $(document).on('click', '.increment, .decrement', function() {
        'use strict';
        const counter = $(this).siblings('.count');
        const productId = $(this).data('product_id');
        const isIncrement = $(this).hasClass('increment');


        const currentVal = parseInt(counter.val()) || 0;
        let newVal = isIncrement ? currentVal + 1 : Math.max(currentVal - 1, 1);
        counter.val(newVal);
        const productDivCounter = $(`.count[data-product_id="${productId}"]`);

        productDivCounter.each(function(index, element) {
            $(element).text(newVal);
        })


        const counterContainer = $(this).closest('.counter');
        const decrementBtn = counterContainer.find('.decrement');

        // Enable or disable the decrement button based on new value
        $(`.itemMainDiv[data-product_id="${productId}"]`).each(function() {
            const item = $(this);
            const decrementBtn = item.find('.decrement');

            decrementBtn.prop('disabled', newVal <= 1);

            // যদি total update করতে চান
            const price = parseFloat(item.data('price'));

            if (!isNaN(price)) {
                item.find(`#total-amount-${productId}`).text('$' + (price * newVal).toFixed(2));
            }
        });

        updateQuantity(productId, newVal);
    });

    $(document).on('click', '.remove-btn', function() {
        'use strict';
        const $row = $(this).closest('.itemMainDiv');
        const productId = $row.data('product_id');

        $.get('{{ route('cart.item.remove') }}', {
            productId
        }, function(response) {
            if (response.message) {
                notify('success', response.message);
                $(`.itemMainDiv[data-product_id="${productId}"]`).remove();
                updateTotalPrice(response.checkCoupon);
                updateCartItemCount(response);
            }
        });
    });

    function truncateToTwo(num) {
        return Math.floor(num * 100) / 100;
    }

    //  =============================== When shipping change then add with total price ================================
    $(document).on('change', '.shippingCharge', function() {
        'use strict';
        let charge = parseFloat($(this).find(':selected').data('charge')) || 0;
        let productAmount = parseFloat($('.coreProductPrice').text()) || 0;
        let totalFinalAmount = charge + productAmount;

        // Shipping charge text update
        $('.shipping-charge').text("{{ $general->cur_sym }}" + charge.toFixed(2));

        // Total final amount with truncateToTwo + locale formatting
        $('.totalFinalAmount').text(
            "{{ $general->cur_sym }}" + truncateToTwo(totalFinalAmount).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );

        $('.paymentPrice').val(
            truncateToTwo(totalFinalAmount)
        );

    });

    $(document).ready(function() {
        'use strict';
        let msg = sessionStorage.getItem('cart_success_msg');
        if (msg) {
            notify('success', msg);
            sessionStorage.removeItem('cart_success_msg');
        }
    });

    // Initial trigger
    updateTotalPrice();
</script>


<script>
    $(document).on('click', '.addToWishlist', function() {
        "use strict";
        var elementObject = $(this);
        var isAddingToWishlist = false;
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        if (!isAddingToWishlist && isLoggedIn) {
            isAddingToWishlist = true;
            var productId = $(this).data('product_id');
            var url = $(this).data('url');

            const favIcon = {
                empty: "{{ asset('assets/images/frontend/icon/fav-w.png') }}",
                filled: "{{ asset('assets/images/frontend/icon/fav-w-fill.png') }}"
            };

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    productId: productId,
                },
                complete: function() {
                    isAddingToWishlist = false;
                },
                success: function(response) {
                    if (response.hasOwnProperty('message')) {
                        if (elementObject.find('img').length > 0) {
                            if (response.message.includes('Added')) {
                                elementObject.html(
                                    `<img class="d-block" src="${favIcon.filled}" alt="image">`
                                );
                            } else if (response.message.includes('Removed')) {
                                elementObject.html(
                                    `<img class="d-block" src="${favIcon.empty}" alt="image">`
                                );
                            }
                        }
                        notify('success', response.message);
                    } else {
                        notify('warning', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'Error occurred while updating the wishlist.';
                    notify('error', errorMessage);
                }
            });
        } else if (!isLoggedIn) {
            window.location.href = "{{ route('user.login') }}";

        }
    });

    $(document).on("click", "#confirmReplaceCart", function() {
        let quantity = 1;
        $.ajax({
            url: "{{ route('cart.replace') }}",
            method: "GET",
            data: {
                _token: "{{ csrf_token() }}",
                quantity: quantity,
                replace: true
            },
            success: function(res) {
                if (res.status) {
                    window.location.reload();
                    sessionStorage.setItem('cart_success_msg', res.message);
                    window.location.reload();
                }
            }
        });
    });
</script>


{{-- remove other restaurant food --}}
<div id="otherFoodsModal" class="modal fade fade-in-scale" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Alert!')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="question">
                    @lang('You currently have items from another Vendor in your cart. Adding items from this vendor will replace those already selected. Would you like to proceed?')
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn--md btn--dark text--white pills"
                    data-bs-dismiss="modal">@lang('No')</button>


                <button type="button" id="confirmReplaceCart" class="btn btn--md btn--base pills" data-product-id=""
                    data-quantity="">@lang('Yes')</button>
            </div>
        </div>
    </div>
</div>
