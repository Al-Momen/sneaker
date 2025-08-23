@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    @guest
        <div class="container">
        @endguest
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="base--card bg--white border--none radius--8">
                    <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="text-white mt-0">
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        @if ($myTicket->status != 3 && $myTicket->user)
                            <button class="btn btn--danger close-button btn-sm confirmationBtn" type="button"
                                data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i
                                    class="fa fa-lg fa-times-circle"></i>
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($myTicket->status != 4)
                            <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control form--control" placeholder="@lang('Message')" rows="4">{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="javascript:void(0)" class="btn btn--lg btn--base mb-2 mt-4 pills addFile">
                                        <i class="fa fa-plus"></i>
                                        @lang('Add New')
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">@lang('Attachments')</label> <small
                                        class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is')
                                        {{ ini_get('upload_max_filesize') }}</small>
                                    <input type="file" name="attachments[]" class="form-control form--control">
                                    <div id="fileUploadsContainer"></div>
                                    <p class="my-2 ticket-attachments-message text-muted">
                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                        .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                    </p>
                                </div>
                                <button type="submit" class="btn btn--base btn--lg pills w-100">
                                    <i class="fa fa-reply"></i>
                                    @lang('Reply')
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="base--card bg--white border--none radius--8 mt-4">
                    @foreach ($messages as $message)
                        @php
                            $isAdmin = $message->admin_id != 0;
                            $name = $isAdmin ? $message->admin->username : $message->ticket->name;
                            $align = $isAdmin ? 'ms-auto text-end' : 'me-auto text-start';
                            $bubbleClass = $isAdmin ? 'chat-bubble-admin' : 'chat-bubble-user';
                            $avatarBg = $isAdmin ? 'bg--base' : 'bg--base';
                            $initials = $isAdmin ? 'Admin' : 'Me';
                            $isRight = $isAdmin;
                        @endphp

                        <div class="d-flex {{ $isRight ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="d-flex {{ $isRight ? 'flex-row-reverse' : '' }} align-items-end mb-2"
                                style="max-width: 80%;">
                                <div class="avatar rounded-circle text-white {{ $avatarBg }} d-flex align-items-center justify-content-center me-2 ms-2"
                                    style="width: 40px; height: 40px;">
                                    {{ $initials }}
                                </div>

                                <div class="chat-bubble {{ $bubbleClass }}">
                                    <div class="mb-1">
                                        {{ $name }}
                                        @if ($isAdmin)
                                            <span class="ms-1">(@lang('Staff'))</span>
                                        @else
                                            <span class="ms-1">(@lang('You'))</span>
                                        @endif
                                    </div>
                                    <div>{{ $message->message }}</div>

                                    @if ($message->attachments->count() > 0)
                                        <div class="attachments mt-2 d-flex flex-wrap gap-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-paperclip me-1"></i> @lang('Attachment')
                                                    {{ $k + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="timestamp small mt-1">
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeachl
                </div>
            </div>
        </div>
      @guest
    </div>
        @endguest
    <x-confirmation-modal></x-confirmation-modal>
@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required >
                        <button class="input-group-text btn--danger remove-btn"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
