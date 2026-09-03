@php
  $containerFooter =
      isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
          ? 'container-xxl'
          : 'container-fluid';
  $companyName = \App\Models\CrackersSetting::getSettings()->company_name ?: 'S.R. TRADERS';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="mb-2 mb-md-0 text-nowrap">
        &#169; {{ date('Y') }}
        <a href="{{ url('/') }}"
          target="_blank"
          class="footer-link fw-bold text-primary">{{ $companyName }}</a>
          <span> - All rights reserved.</span>
      </div>
    </div>
  </div>
</footer>
<!-- / Footer -->
