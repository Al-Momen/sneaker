@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="cookie-section py-100">
        <div class="container">
            <div class="row justify-content-center">
                <h3 class="section-title">{{ __($pageTitle) }}</h3>
                <div class="col-lg-12">
                    <div class="coockie-wrap wyg">
                        @php
                            echo $cookie->data_values->description;
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .wyg h1,
        h2,
        h3,
        h4 {
            color: #383838;
        }

        .wyg strong {
            color: #383838
        }

        .wyg p {
            color: #666666
        }

        .wyg ul {
            margin-left: 40px
        }

        .wyg ul li {
            list-style-type: disc;
            color: #666666
        }

        .section-title {
            font-size: 30px;
            margin-bottom: 0;
        }
    </style>
@endpush
