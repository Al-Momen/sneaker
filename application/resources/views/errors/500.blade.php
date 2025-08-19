<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> {{ $general->siteName(__('500')) }}</title>
    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
</head>

<body>
    <section class="error">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error__main py-60">
                        <img src="{{ getImage(getFilePath('error') . '500.png') }}" alt="image">
                        <h2>500</h2>
                        <h4>@lang('Page Not Found')</h4>
                        <p>@lang("The page you are looking for might have been removed had its name changed or is temporarily
                                                                            unavailable.")</p>
                        <a href="{{ route('home') }}" class="btn btn--base btn--lg pill"><i
                                class="las la-arrow-left me-1"></i>@lang('Back to Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
