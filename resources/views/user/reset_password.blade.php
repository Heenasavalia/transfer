<!DOCTYPE html>
<html>

<head>


    <style>
        img.tranfer_logo {
            margin-left: 95px;
        }

        .form-group {
            padding: 3px !important;
        }

        input {
            width: 300px;
            padding: 9px;
        }

        .btn {
            background-color: dodgerblue;
            color: white;
            padding: 15px 20px;
            border: none;
            cursor: pointer;
            width: 65%;
            opacity: 0.9;
        }

        .btn_mail {
            background-color: #d21eff;
            color: white;
            padding: 15px 20px;
            border: none;
            cursor: pointer;
            width: 65%;
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <form class="form-horizontal" role="form" method="POST" action="{{ url('reset-password-submit') }}" id="reguser" style="max-width:500px;margin:auto">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{$token}}">
        <img src="http://3.17.228.42//images/default.png" alt="tranfer-logo" class="tranfer_logo" style="height: 170px;">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <!-- <label for="email" class="col-md-12 control-label">E-Mail Address</label> -->

            <div>
                <input id="email" type="email" placeholder="Email" class="form-control new" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <!-- <label for="password" class="col-md-12 control-label">Password</label> -->

            <div>
                <input id="password" type="text" placeholder="Password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <!-- <label for="password-confirm" class="col-md-12 control-label">Confirm Password</label> -->

            <div>
                <input id="password-confirm" type="text" placeholder="Confirm Password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn">
                    Reset Password
                </button>
            </div>
        </div>

        </br>


    </form>


</body>

</html>