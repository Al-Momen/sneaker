  <div class="modal fade" id="bidAuctionProduct" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg custom--modal">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabelLiveAuctionBid">@lang('Would you like to participate in the')"<span
                          class="live_auction_product_title"></span>" @lang('bidding competition')?</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ route('user.bid') }}" method="post">
                  @csrf

                  <div class="modal-body">
                      <input type="hidden" id="live_auction_product" name="product_id">
                      <div class="row">

                          <div class="col-12">
                              <div class="form-group mb-3">
                                  <label class="mb-2 form--label">@lang('Bid Amount')</label>
                                  <input type="number" class="form--control" name="price"
                                      placeholder="@lang('Enter Your Price')" step="any" min="1" value=""
                                      required>
                              </div>
                          </div>

                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="reset" class="btn btn--dark text--white pills"
                          data-bs-dismiss="modal">@lang('Close')</button>
                      <button type="submit" id="recaptcha"
                          class="btn btn--md btn--base pills">@lang('BID NOW')</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
@push('script')
    <script>
        $(document).ready(function() {
            'use strict'
            $(".bidNow").on('click', function() {
                var modal = $('#bidAuctionProduct');
                var productTitle = $(this).data('product-title');
                var productId = $(this).data('product-id');
                var productPrice = $(this).data('product-price');
                modal.find('.live_auction_product_title').text(productTitle);
                modal.find('input[name="product_id"]').val(productId);
                modal.find('input[name="price"]').attr('placeholder',
                    "Highest Bid {{ $general->cur_sym }}" + productPrice);
                modal.modal('show');
            })
        });
    </script>
@endpush