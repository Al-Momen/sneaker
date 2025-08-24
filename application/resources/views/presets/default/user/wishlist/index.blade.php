@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <form action="" method="GET">
                <div class="mb-3 d-flex justify-content-end w-25 ms-auto">
                    <div class="input-group">
                        <input type="text" name="search" class="form--control form-control bg--white"
                            value="{{ request()->search }}" placeholder="@lang('Search by product name')">
                        <button type="submit" class="input-group-text bg--base text-white border-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="base--card bg--white border--none radius--8">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                             <th>@lang('SI')</th>
                            <th class="text-center">@lang('Image')</th>
                            <th class="text-center">@lang('Name')</th>
                            <th class="text-center">@lang('Created At')</th>
                            <th class="text-center">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wishlists as $wishlist)
                            <tr>
                                <td data-label="SI">#{{ $loop->iteration }}</td>
                                <td data-label="Image">
                                    <img style="width:60px;" class="rounded img-thumbnail"
                                        src="{{ getImage(getFilePath('product') . '/' . 'thumb_' . $wishlist->product?->firstImage?->image ?? '') }}"
                                        alt="@lang('Product image')">

                                </td>
                                <td class="text-center" data-label="@lang('Name')">
                                    <span class="fw--500">
                                        <a href="{{ route('product.details', [$wishlist->product->id, slug($wishlist->product->name)]) }}"
                                            class="text--base">
                                            {{ __($wishlist->product->name) }}
                                        </a>
                                    </span>
                                </td>
                               
                                <td class="text-center" data-label="@lang('Created At')">
                                    {{ showDateTime($wishlist->created_at) }}
                                </td>
                                <td class="text-center" data-label="@lang('Action')">
                                    <a href="javascript:void(0)"
                                        class="btn btn--base btn-md action--btn confirmationBtn"
                                        title="@lang('Remove')" data-question="@lang('Did you revieve the order')?"
                                        data-action="{{ route('user.remove.wishlist', $wishlist->id) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td data-label="@lang('Tour Title')" class="text-muted text-center" colspan="100%">
                                    {{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @if ($wishlists->hasPages())
        <div class="row mx-xxl-5 mx-lg-0 my-4">
            <div class="col-lg-12 justify-content-end d-flex">
                {{ $wishlists->links() }}
            </div>
        </div>
    @endif
    <x-confirmation-modal />
@endsection
