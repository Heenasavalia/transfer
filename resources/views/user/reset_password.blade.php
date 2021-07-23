<html>


<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap" rel="stylesheet">
<style>
    nav.navbar.navbar-default.navbar-static-top {
        display: none;
    }

    body {
        background-color: #fff;
        font-family: 'Roboto', sans-serif;
    }

    .form-group {
        width: 100%;
        float: left;
        margin-bottom: 20px;
    }

    .container {
        border-radius: .5em;
        padding: 10px;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: flex-end;
    }

    .logoimage {
        display: flex;
        text-align: center;
        justify-content: center;
        width: auto;
        margin: 0 auto;
        margin-bottom: 30px;
    }

    .panel-body {
        padding: 0;
    }

    label.col-md-12.control-label {
        font-size: 12px;
        margin: 9px 0 0 0;
    }

    input {
        line-height: 20px !important;
        font-size: 16px !important;
        padding: 5px !important;
        height: 28px !important;
        border: 1px solid #a7a7a7 !important;
        background-color: #fff !important;
    }

    button.btn.button_send {
        width: 100%;
        font-size: 15px;
        padding: 5px;
        line-height: 20px;
        color: black;
        font-weight: 600;
        background-color: #f0d3cf;
        margin: 12px 0;
    }

    .panel-default {
        border-color: #a5a5a5 !important;
        border-width: 2px !important;
    }

    form#reset_password_submit .form-group label {
        font-size: 13px;
        color: #e72225;
        font-weight: 500;
        margin-bottom: 7px;
        text-transform: uppercase;
        /*letter-spacing: 1px;*/
    }

    form#reset_password_submit .form-group input {
        padding: 17px 0px !important;
        font-size: 14px !important;
        border: none !important;
        box-shadow: none;
        border-bottom: 2px solid #d9d9d9 !important;
        border-radius: 0px !important;
    }

    .form-control:focus,
    .form-control button:focus {
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: none !important;
    }

    form#reset_password_submit .form-group label {
        font-size: 13px;
        color: #e72225;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 0px;
    }

    .submit_button {
        text-align: center;
    }

    .submit_button button.btn.button_send {
        width: 100%;
        font-size: 15px;
        padding: 10px;
        line-height: 20px;
        color: #fff;
        font-weight: 600;
        background-color: #e72225;
        margin: 40px 0px 0px 0px;
        max-width: 75%;
        border-radius: 50px;
    }

    .resetpassword_form {
        -ms-transform: translate(0px, 100px);
        transform: translate(0px, 100px);
    }

    @media only screen and (max-width: 767px) {
        .resetpassword_form {
            -ms-transform: translate(0px, 20px);
            transform: translate(0px, 20px);
        }
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-12 col-md-offset-4">
            <div class="resetpassword_form">
                <div class='logoimage'>

                    <img src="default.png" alt="transfer" style="height: 150px;">
                </div>
                <form name="reset_password_submit" action="{{url('reset-password-submit')}}" method="POST" id="reset_password_submit">
                    <div class="panel-body">
                        <input type="hidden" name="token" value="{{$token}}">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-12 control-label">E-Mail</label>
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" value="{{$email}}" autofocus="off" readonly="true">
                            </div>
                            <p class="text-danger">{{ $errors->first('email') }}</p>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-12 control-label">Password</label>
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                            <p class="text-danger col-md-12">{{ $errors->first('password') }}</p>
                        </div>
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-12 control-label">Confirm Password</label>
                            <div class="col-md-12">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                            </div>
                            <p class="text-danger col-md-12">{{$errors->first('password_confirmation')}}</p>
                        </div>
                        <div class="form-group submit_button">
                            <div class="col-md-12">
                                <button type="submit" class="btn button_send">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>