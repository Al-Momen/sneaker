@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 mb-4">
        <div class="col-lg-12">
            <div class="text-end">
                <a href="{{ route('ticket.open') }}" class="btn btn--base btn--lg pill mb-4 mt-4">
                    <i class="fa-solid fa-ticket me-2"></i> 
                    @lang('Open Ticket')
                </a>
            </div>
            <div class="base--card bg--white border--none radius--8">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('Subject')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Priority')</th>
                            <th>@lang('Last Reply')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supports as $support)
                            <tr>
                                <td>
                                    <a href="{{ route('ticket.view', $support->ticket) }}">
                                        [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} 
                                    </a>
                                </td>
                                <td>
                                    @php echo $support->statusBadge; @endphp
                                </td>
                                <td>
                                    @if ($support->priority == 1)
                                        <span class="badge badge--dark">@lang('Low')</span>
                                    @elseif($support->priority == 2)
                                        <span class="badge badge--success">@lang('Medium')</span>
                                    @elseif($support->priority == 3)
                                        <span class="badge badge--success">@lang('High')</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                <td>
                                    <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--base btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $supports->links() }}
        </div>
    </div>
@endsection
