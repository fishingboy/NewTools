<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Project') }} CMS System | Register </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Font Awesome -->
  <link href="{{asset('/static/adminui/third-party-include/fontawesome-free-5.14.0-web/css/all.css')}}" rel="stylesheet" type="text/css">
  <!-- core UI -->
  <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css">

  <!-- Google Font -->
  <link href="{{asset('/static/adminui/css/fonts_googleapis_css_source_sans_pro.css')}}" rel="stylesheet" type="text/css">

  <script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.bundle.min.js"></script>

  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->


</head>

<body class="c-app flex-row align-items-center">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card mx-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('register') }}">
          <h1>{{ config('app.name', 'Project') }} CMS System </h1>
          <p class="text-muted">新增帳號</p>
          <div class="input-group mb-3">
            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
</svg></span></div>
            <input class="form-control" type="text" placeholder="Username" id="name">
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
</svg></span></div>
            <input class="form-control" type="text" placeholder="Email" id="email">
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
</svg></span></div>
            <input class="form-control" type="password" placeholder="Password" id="password">
          </div>
          <div class="input-group mb-4">
            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
</svg></span></div>
            <input class="form-control" type="password" placeholder="Repeat password">
          </div>

          <button type="submit" class="btn btn-block btn-success" type="button">註冊</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

</html>
