@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-6 col-md-6 mb-30">
            <div class="card p-16 bg--white radius--base br--solid overflow-hidden">
                <h5 class="mb-20">@lang('Customer Info')</h5>
                <ul class="list-group pt-3">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Full Name'):
                        <span class="fw--600">{{ $order->user?->fullname }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Email'): <span class="fw--600">{{ $order->user?->email }}</span>
                    </li>


                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Phone'):
                        <span class="fw--600">{{ $order->mobile }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Country'):
                        <span class="fw--600">{{ $order->user?->address->country }}</span>
                    </li>


                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
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
                @php
                    $products = $order
                        ->products()
                        ->where('author_id', auth('admin')->id())
                        ->where('author_type', 1)
                        ->with('adminAuthor')
                        ->get();
                @endphp

                @if ($products->isNotEmpty())
                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        @if ($order->status == 1)
                            <button class="btn btn--base btn--md mt-3 confirmationBtn text--white"
                                data-action="{{ route('admin.orders.vendor.status.change', [4, $order->id]) }}"
                                data-question="@lang('Are you sure to change this order processing?')">@lang('Processing')</button>
                            <button class="btn btn--base btn--md mt-3 confirmationBtn text--white"
                                data-action="{{ route('admin.orders.vendor.status.change', [3, $order->id]) }}"
                                data-question="@lang('Are you sure to change this order canceled?')">@lang('Canceled')</button>
                        @elseif($order->status == 4)
                            <button class="btn btn--base btn--md mt-3 confirmationBtn text--white"
                                data-action="{{ route('admin.orders.vendor.status.change', [5, $order->id]) }}"
                                data-question="@lang('Are you sure to change this order delivered?')">@lang('Delivered')</button>
                        @endif
                    </div>
                @endif

            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-30">
            <div class="card p-16 bg--white radius--base br--solid overflow-hidden">
                <h5 class="mb-20">@lang('Order Details')</h5>
                <ul class="list-group pt-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Order Number')
                        <span class="fw--600 badge badge--success">{{ $order->order_number }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Date')
                        <span class="fw--600">{{ showDateTime($order->created_at) }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Shipping Charge')

                        <span class="fw--600">{{ $general->cur_sym . showAmount($order?->shipping?->charge ?? 0) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Price') <span
                            class="fw--600">{{ $general->cur_sym . showAmount($order->total_price) }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                        @lang('Status')
                        <span class="fw--600">@php echo $order->statusBadge($order->status) @endphp</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-md-12 mb-30">
                <div class="card b-radius--10">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light style--two custom-data-table">
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
                                            <td data-label="Author Name">
                                                @if ($product->author_type == 1)
                                                    {{ $product->author_name }}
                                                @else
                                                    <a
                                                        href="{{ route('admin.users.detail', $product->author_id) }}">{{ '@' . $product->author_name }}</a>
                                                @endif
                                            </td>
                                            <td data-label="Title"><a
                                                    href="{{ route('product.details', ['slug' => slug($product->name), 'id' => $product->id]) }}">{{ $product->name }}</a>
                                            </td>
                                            <td data-label="Quantity">{{ $product->pivot->quantity }}</td>
                                            <td data-label="Price">
                                                {{ $general->cur_sym . $product->pivot->price * $product->pivot->quantity }}
                                            </td>
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
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>
@endsection
