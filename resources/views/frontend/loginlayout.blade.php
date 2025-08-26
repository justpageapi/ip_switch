<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body class="login-screen">
    <div class="login-header desktop-header">
      <div class="row">
        <div class="col-md-6">
          <a href="index.php"><img class="desktop-logo" src="img/desktop-logo.svg"></a>
        </div>
        <div class="col-md-6 text-right">
          <a class="btn signup-btn" href="signup.php">{{ __('app.sign_up') }}</a>
        </div>
      </div>
    </div>
    <div class="login-header mobile-header">
      <div class="row">
        <div class="col-sm-6">
          <a href="index.php"><img class="mobile-logo" src="img/mobile-logo.svg"></a>
        </div>
        <div class="col-sm-6 text-right">
          <a class="btn signup-btn" href="signup.php">{{ __('app.sign_up') }}</a>
        </div>
      </div>
    </div>
    <div class="container-fluid login-flow">
      <div class="container login-main">
        <form action="" method="">
        <h2 class="form-head login-head">Sign In</h2>
        <p class="form-para">Welcome back to Brightflixx!</p>
          <div class="box">
            <div class="input-wrapper">
              <input type="text" id="input" class="form-control" placeholder="Email or Phone Number">
              <label for="input" class="control-label">Email or Phone Number</label>
            </div>
          </div>
          <div class="box">
            <div class="input-wrapper">
              <input type="password" id="input" class="form-control" placeholder="Password">
              <label for="input" class="control-label">Password</label>
            </div>
          </div>
          <p class="text-right">
            <button data-toggle="modal" data-target=".bs-example-modal-sm" class="forget-title">Forget Password?</button>
            <div class="modal bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <button class="btn text-right"><img src="img/close.svg"></button>
                  <div class="modal-body">Forgot Password</div>
                  <div class="modal-footer text-center">
                    <p class="modal-para">Please enter email address to receive password reset link</p>
                    <div class="form-group">
                      <input class="forget-pass-input" type="email" name="email" autocomplete="off" required placeholder="Enter Email">
                    </div>
                    <span><a href="javascript:;" id="forget-send" class="btn btn-primary">Send</a></span>
                  </div>
                </div>
              </div>
            </div>
          </p>

          <div class="form-group text-center"><button class="btn btn-submit" type="submit" value="signin">Sign in</button></div>
        </form>
      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
