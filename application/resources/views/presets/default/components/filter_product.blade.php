@forelse($products as $product)
    <div class="col-xl-4 col-md-6">
        @include($activeTemplate . 'components.product')
    </div>
@empty
    <h4 class="text-center">@lang('No product found')</h4>
@endforelse