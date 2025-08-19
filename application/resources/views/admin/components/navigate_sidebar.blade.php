@adminHas('settings')
    <div class="card bg--white br--solid radius--base p-16">
        <h5 class="mb-3">@lang('Navigate To')</h5>
        <ul class="general-navigate--bar d-flex flex-column justify-content-start">
            <li>
                <a href="{{ route('admin.setting.index') }}"
                    class="{{ menuActive('admin.setting.index') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-solid fa-sliders"></i>
                    <h6>@lang('Basic Settings')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.setting.logo.icon') }}"
                    class="{{ menuActive('admin.setting.logo.icon') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-solid fa-images"></i>
                    <h6>@lang('Logo & Favicon')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.setting.notification.templates') }}"
                    class="{{ menuActive('admin.setting.notification.templates') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-regular fa-bell"></i>
                    <h6>@lang('Email & Notification')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.seo') }}" class="{{ menuActive('admin.seo') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-solid fa-diagram-project"></i>
                    <h6>@lang('SEO')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.setting.cookie') }}"
                    class="{{ menuActive('admin.setting.cookie') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    <h6>@lang('CDPR Policy')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.setting.custom.css') }}"
                    class="{{ menuActive('admin.setting.custom.css') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-regular fa-file-code"></i>
                    <h6>@lang('Custom CSS')</h6>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.setting.maintenance') }}"
                    class="{{ menuActive('admin.setting.maintenance') }} d-flex align-items-center justify-content-start gap-2">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <h6>@lang('Maintenance')</h6>
                </a>
            </li>
        </ul>
    </div>
@endadminHas
