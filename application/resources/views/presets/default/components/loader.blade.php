@php
     $siteName = strtoupper(gs()->site_name);
    $letters = str_split($siteName);
@endphp
<!--==================== Preloader Start ====================-->
<div id="preloader">
    <div id="text">
        @foreach($letters as $index => $letter)
            <p class="{{ $index == count($letters) - 1 ? 'active' : '' }}">{{ $letter }}</p>
        @endforeach
    </div>
</div>
<div class="sidebar-overlay"></div>
<!--==================== Preloader End ====================-->






