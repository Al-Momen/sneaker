@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 pb-4">
        <div class="col-xl-6 col-md-12">
            <div class="base--card bg--white radius--12">
                <h5 class="mb-20">@lang('Customer Info')</h5>
                <ul class="list-group gap--12">
                   
                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Full Name'):
                        <span class="fw--600">{{ $order->user?->fullname }}</span>
                    </li>

                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Email'): <span class="fw--600">{{ $order->user?->email }}</span>
                    </li>

                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Phone'):
                        <span class="fw--600">{{ $order->mobile }}</span>
                    </li>

                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Country'):
                        <span class="fw--600">{{ $order->user?->address?->country }}</span>
                    </li>


                    <li class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent border-0">
                        @lang('Address'):
                        @php
                            $address = $order->user?->address;
                            $fullAddress = collect([
                                $address?->address,
                                $address?->state,
                                $address?->zip,
                                $address?->city,
                                $address?->country,
                            ])
                                ->filter()
                                ->implode(', ');
                        @endphp
                        <span class="fw--600"> {{ $fullAddress }}</span>
                    </li>
                </ul>
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    @if ($order->status == 5)
                        <button class="btn btn--base btn--md mt-3 confirmationBtn"
                            data-action="{{ route('user.orders.vendor.status.change', [6, $order->id]) }}"
                            data-question="@lang('Are you sure to change this order completed?')">@lang('Completed')</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12">
            <div class="base--card bg--white radius--12">
                <h5 class="mb-20">@lang('Order Details')</h5>
                <ul class="list-group gap--12">
                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Order Number')
                        <span class="fw--600 badge badge--success">{{ $order->order_number }}</span>
                    </li>

                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Date')
                        <span class="fw--600">{{ showDateTime($order->created_at) }}</span>
                    </li>

                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Shipping Charge')

                        <span class="fw--600">{{ $general->cur_sym . showAmount($order?->shipping?->charge ?? 0) }}</span>
                    </li>
                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Total Price') <span
                            class="fw--600">{{ $general->cur_sym . showAmount($order->total_price) }}</span>
                    </li>


                    <li
                        class="list-group-item p-0 border-0 d-flex justify-content-between align-items-center bg--transparent">
                        @lang('Status')
                        <span class="fw--600">@php echo $order->statusBadge($order->status) @endphp</span>
                    </li>
                </ul>

                
            </div>
        </div>

        <div class="col-lg-12">
            <div class="base--card bg--white radius--12">
                <div class="tbl-wrap">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('SI')</th>
                                <th>@lang('Author Name')</th>
                                <th>@lang('Product Name')</th>
                                <th>@lang('Quantity')</th>
                                <th>@lang('Price')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr>
                                    <td data-label="SI">#{{ $loop->iteration }}</td>
                                    <td data-label="Author Name">{{ $product->author_name }}</td>
                                    <td data-label="Product Name"><a class="text--base"
                                            href="{{ route('product.details', ['slug' => slug($product->name), 'id' => $product->id]) }}">{{ $product->name }}</a>
                                    </td>
                                    <td data-label="Quantity">{{ $product->pivot->quantity }}</td>
                                    <td data-label="Price">
                                        {{ $general->cur_sym . $product->pivot->price * $product->pivot->quantity }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-end">@lang('Shipping Charge'):
                                    {{ $general->cur_sym . showAmount($order->shipping->charge ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end">@lang('Total Price'):
                                    {{ $general->cur_sym . showAmount($order->total_price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- table end -->
                </div>
            </div>
        </div>
    </div>
        <x-confirmation-modal></x-confirmation-modal>
@endsection
