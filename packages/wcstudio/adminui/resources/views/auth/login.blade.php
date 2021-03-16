<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ASUS MiddleWare | Log in</title>
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

<body class="c-app flex-row align-items-center  pace-done">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
              <h1>@lang("login_title")</h1>
              <p class="text-muted">@lang("signin_to_start")</p>
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="cil-envelope-closed"></i>
                    </span>
                  </div>
                  <!-- <input class="form-control" type="text" placeholder="Username"> -->
                  <input id="email" type="email" placeholder="Email" aria-label="Email" aria-describedby="basiaddon1" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="fas fa-key"></i>
                    </span>
                  </div>
                  <input id="password" type="password" placeholder="Password" aria-label="Password" aria-describedby="basiaddon2" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="input-group mb-3">
{{--                   <div class="input-group-prepend">--}}
{{--                    <div class="input-group-text">--}}
{{--                      <input class="btn" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                      <label class="form-check-label" for="remember">--}}
{{--                        @lang("remember_me")--}}
{{--                      </label>--}}
{{--                    </div>--}}
{{--                  </div>--}}
                      <button type="submit" class="btn btn-primary px-4">@lang("sign_in")</button>
                  <div class="col-6 text-right">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none">
            <div class="card-body text-center">
              <div>
                <h2>@lang("middleware")</h2>
                <p>@lang("contract_notice")</p>
                <button class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



</body>

</html>
