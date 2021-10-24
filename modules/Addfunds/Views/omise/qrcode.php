<div class="row justify-content-md-center">
    <form id="frm-or" method="post" action="<?= cn($module) ?>">
        <div class="container  justify-content-center">
            <div class="card p-5">
                <div class="card-header d-flex align-items-center">
                    <h1 class="page-title">
                        <div class="d-flex flex-row align-items-center"><i class="fa fa-qrcode text-primary" aria-hidden="true"></i><?=lang("qrcode")?></div>

                    </h1>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <!-- <label for="omise_qrcode" class="form-label">QRCode:</label> -->
                        <img width="100%" src="<?= $qr ?>" alt="">
                        <!-- <input type="text" id="omise_qrcode" class="form-control" name="omise_otp">
                                <button id="submit" class="btn"><b><?= lang("process") ?></b></button> -->

                    </div>
                    <div class="footer animated slow fadeInUp">
                        <p id="timer">
                            
                            <script type="text/javascript">
                                countDown();
                            </script>
                         
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </form>


</div>

<style>
    .full {
        -moz-transform: scale(3);
        -webkit-transform: scale(3);
        transform: scale(3);
    }

    form .row-1 {
        border: 1px solid rgba(0, 0, 0, 0.137);
        padding: 0.5rem;
        outline: none;
        width: 100%;
        min-width: unset;
        border-radius: 5px;
        background-color: rgba(221, 228, 236, 0.301);
        border-color: rgba(221, 228, 236, 0.459);
        margin: 2vh 0;
        overflow: hidden
    }

    .btn {
        width: 100%;
        background-color: rgb(65, 202, 127);
        border-color: rgb(65, 202, 127);
        color: white;
        justify-content: center;
        padding: 2vh 0;
        margin-top: 3vh
    }

    .btn:focus {
        box-shadow: none;
        outline: none;
        box-shadow: none;
        color: white;
        -webkit-box-shadow: none;
        -webkit-user-select: none;
        transition: none
    }

    .btn:hover {
        color: white
    }


    /* Animation */

    .animated {
        -webkit-animation-duration: 1.2s;
        -moz-animation-duration: 1.2s;
        -ms-animation-duration: 1.2s;
        -o-animation-duration: 1.2s;
        animation-duration: 1.2s;
        -webkit-transform: translate3d(0, 0, 0);
        -webkit-backface-visibility: hidden;
    }

    .animated.fast {
        -webkit-animation-duration: 800ms;
        -moz-animation-duration: 800ms;
        -ms-animation-duration: 800ms;
        -o-animation-duration: 800ms;
        animation-duration: 800ms;
    }

    .animated.slow {
        -webkit-animation-duration: 1.4s;
        -moz-animation-duration: 1.4s;
        -ms-animation-duration: 1.4s;
        -o-animation-duration: 1.4s;
        animation-duration: 1.4s;
    }

    @-webkit-keyframes fadeInUp {
        0% {
            opacity: 0;
            -webkit-transform: translateY(20px);
        }

        100% {
            opacity: 1;
            -webkit-transform: translateY(0);
        }
    }

    @-moz-keyframes fadeInUp {
        0% {
            opacity: 0;
            -moz-transform: translateY(20px);
        }

        100% {
            opacity: 1;
            -moz-transform: translateY(0);
        }
    }

    @-o-keyframes fadeInUp {
        0% {
            opacity: 0;
            -o-transform: translateY(20px);
        }

        100% {
            opacity: 1;
            -o-transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fadeInUp {
        -webkit-animation-name: fadeInUp;
        -moz-animation-name: fadeInUp;
        -o-animation-name: fadeInUp;
        animation-name: fadeInUp;
    }
</style>