<html>
<style>
    .containers {
        border-radius: .5em;
        padding: 10px;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: flex-end;
    }

    .set_in_center {
        padding: 10px;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: center;
    }

    img {
        width: 100%;
    }

    h1.message {
        text-align: center;
        font-weight: 600;
    }

    .logoimage {
        display: flex;
        text-align: center;
        justify-content: center;
        width: auto;
        margin: 0 auto;
    }

    @media (max-width:500px) and (min-width:411px) {
        h1.message {
            font-size: 18px;
            text-align: center;
            font-weight: normal;
        }

        .set_background {
            background-image: url("{{asset('images/dummy.jpg')}}");
            background-size: 76% 100%;
            background-repeat: no-repeat;
            background-position: right;
        }

        .side_image {
            display: none;
        }

    }

    @media (max-width : 410px) {
        h1.message {
            font-size: 12px;
            text-align: center;
            font-weight: normal;
        }

        .set_background {
            background-image: url("{{asset('images/dummy.jpg')}}");
            background-size: 69% 85%;
            background-repeat: no-repeat;
            background-position: right;
        }

        .side_image {
            display: none;
        }

        .logoimage {
            width: 90px;
        }
    }
</style>
<div class="containers">
    <div class="row set_background">
        <div class="col-md-6 col-sm-4 col-5">
            <div class='set_in_center'>
                <div class="logoimage">
                    <img src="http://3.17.228.42//images/default.png" alt="tranfer-logo" class="tranfer_logo" style="height: 170px;">
                    <!-- this is logo -->
                </div>
                @if($type == 'success')
                <h1 class='message'><span>Hurray,</span><br> {{$message}}</h1>
                <p></p>
                @else
                <h1 class='message'>{{$message}}</h1>
                @endif
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-7">
            <img src='{{asset('images/dummy.jpg')}}' class='side_image'>
        </div>
    </div>
</div>

</html>