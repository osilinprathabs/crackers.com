<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

<!-- Fonts Icons -->
@vite(['resources/assets/vendor/fonts/iconify/iconify.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" crossorigin="anonymous">

<!-- BEGIN: Vendor CSS-->
@vite(['resources/assets/vendor/libs/node-waves/node-waves.scss'])

@if ($configData['hasCustomizer'])
  @vite(['resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endif

<!-- Core CSS -->
@vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss'])

<!-- Vendor Styles -->
@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss'])
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')

<!-- app CSS -->
@vite(['resources/css/app.css'])
<!-- END: app CSS-->

<!-- Custom Normal Solid Green for Admin & Storefront -->
<style>
  .bg-label-success, 
  [data-bs-theme=light] .bg-label-success,
  [data-bs-theme=dark] .bg-label-success {
      background-color: #16a34a !important;
      color: #ffffff !important;
      border: 1px solid #15803d !important;
      font-weight: 700 !important;
  }
  .badge.bg-success, .badge.bg-label-success {
      background-color: #16a34a !important;
      color: #ffffff !important;
      font-weight: 700 !important;
  }
  .text-success {
      color: #16a34a !important;
      font-weight: 700 !important;
  }
  .alert-success {
      background-color: #16a34a !important;
      border-left: 5px solid #14532d !important;
      color: #ffffff !important;
  }
  .alert-success .btn-close, .alert-success a, .alert-success span, .alert-success div, .alert-success strong {
      color: #ffffff !important;
  }
</style>
