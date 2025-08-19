@forelse($items as $user)
    <tr>
        <td class="user--td">
            <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                <div class="form--check">
                    <input class="form-check-input action--check" type="checkbox"
                        value="{{ $user->id }}">
                </div>
                <div class="user--info d-flex gap-3 flex-shrink-0 align-items-center flex-wrap flex-md-nowrap">
                    <div class="user--thumb-two">
                        @if(!empty($user->image))
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image ) }}" alt="@lang('Image')">
                        @else
                            <img src="{{ getImage('assets/images/general/avatar.png') }}" alt="@lang('Image')">
                        @endif
                    </div>
                    <div class="user--content">
                        <a class="text-start" href="{{ route('admin.users.detail', $user->id) }}">
                            {{ $user->fullname }}
                        </a>
                        <p class="text-start">{{ $user->username }}</p>
                    </div>
                </div>
            </div>
        </td>

        <td>
            {{ $user->email }}
        </td>



        <td>
            {{ showDateTime($user->created_at) }}
        </td>


        <td>
            <span class="fw-bold">

                {{ $general->cur_sym }}{{ showAmount($user->balance) }}
            </span>
        </td>

        <td>
            <a title="@lang('User Profile')"
                href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>

            @if(request()->routeIs('admin.users.kyc.pending'))
            <a title="@lang('KYC Data')"
                href="{{ route('admin.users.kyc.details', $user->id) }}" class="btn btn-sm">
                <i class="fa-solid fa-user-shield"></i>
            </a>
            @endif
        </td>

    </tr>
@empty
    <tr>
        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
    </tr>
@endforelse
