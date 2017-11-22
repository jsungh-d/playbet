<section>
    <div class="align_c login_text_contain pt45 pb30">
        <img src="/images/common/lock1.png" alt="">
        <h2><strong>로그인</strong></h2>
        <h4>소셜네트워크서비스를 이용하여 간편하게 로그인하세요</h4>   
    </div>
    <div class="social_login align_c">
        <div class="social_login_wrapper">

            <button class="mb15 social_login_btn kakao_btn" onclick="loginWithKakao();">
                <h5>카카오톡으로 로그인</h5>
                <img src="/images/common/kakao_mini.png" alt=""> 
            </button>

            <button class="mb15 social_login_btn naver_btn" type="button" onclick='loginNaver()'>
                <h5>네이버로 로그인</h5>
                <img src="/images/common/naver_mini.png" alt="">
            </button>

            <button class="mb15 social_login_btn facebook_btn" type="button" onclick="fbLogin();">
                <h5>페이스북으로 로그인</h5>
                <img src="/images/common/facebook_mini.png" alt="">    
            </button>
            <script src="https://apis.google.com/js/api:client.js"></script>
            <script type="text/javascript">
                //구글로그인
                var googleUser = {};
                var startApp = function () {
                    gapi.load('auth2', function () {
                        auth2 = gapi.auth2.init({
                            client_id: '577473026777-u1ffjc0v2m3s5l4utp54rrt5ob7pocfi.apps.googleusercontent.com',
                            cookiepolicy: 'single_host_origin',
                            scope: 'profile email'
                        });
                        attachSignin(document.getElementById('customBtn'));
                    });
                };

                function attachSignin(element) {
                    auth2.attachClickHandler(element, {},
                            function (googleUser) {
                                var data = {id: googleUser.getBasicProfile().getId(), pick: googleUser.getBasicProfile().getImageUrl(), nickName: googleUser.getBasicProfile().getName(), email: googleUser.getBasicProfile().getEmail(), join_root: 'GOOGLE'};

                                $.ajax({
                                    dataType: 'text',
                                    url: '/index.php/dataFunction/snsMemberLogin',
                                    data: data,
                                    type: 'POST',
                                    success: function (data, status, xhr) {
                                        if (data == 'SUCCESS') {
                                            location.href = '/';
                                        } else {
                                            alert('로그인에 실패 하였습니다.');
                                        }
                                    }
                                });

                            }, function (error) {
                        alert(JSON.stringify(error, undefined, 2));
                    });
                }
            </script>
            <script type="text/javascript">startApp();</script>
            <button class="mb15 social_login_btn google_btn" type="button" id="customBtn">
                <h5>구글로 로그인</h5>
                <img src="/images/common/google_mini.png" alt="">
            </button>
            <button class="mb30 social_login_btn twitter_btn" type="button" onclick="location.href = '/index.php/dataFunction/twitterLogin'">
                <h5>트위터로 로그인</h5>
                <img src="/images/common/twitter_mini.png" alt="">
            </button>

        </div>

        <!--        <form method="post" action="/index.php/dataFunction/login">
                    <div class="login_input mt15">
                        <label>
                            <input type="email" name="email" placeholder="이메일" required>
                        </label>
                        <label>
                            <input type="password" name="pwd" placeholder="비밀번호" required>
                        </label>
                        <div class="btn_area">
                            <button class="darkblue_btn color_btn" type="submit">로그인</button>
                        </div>
                    </div>
                </form>-->

    </div>
</section>
</div>

<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>

<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/naverLogin.js"></script>
<script type="text/javascript">
                !function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "http://platform.twitter.com/anywhere.js?id=amQU4G5lGwB61q3vkIqZuaVisagMQ2ZniGuC8ylu0N1alxjXje";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", 'twitter-anywhere');
//describe the login actions  
                twttr.anywhere(function (T) {
                    T.bind("authComplete", function (e, user) {
                        var token = user.attributes._identity;
                        //define the login function on your client through Twitter  
                    });
                });
//function we link to the click on the custom login button through Twitter  
                function doTWSignIn() {
                    twttr.anywhere(function (T) {
                        T.signIn();
                    });
                }
</script>  
<script type="text/javascript">
    $(document).ready(function () {

        twttr.widgets.load()

        $(".betting_modal .cancle_btn").click(function () {
            $(".close-modal").trigger("click");
        });
    });

    //<![CDATA[
// 사용할 앱의 JavaScript 키를 설정해 주세요.
    Kakao.init('8eb14b8933fa3356932f73a2d1d9c13b');
    function loginWithKakao() {
        // 로그인 창을 띄웁니다.
        Kakao.Auth.login({
            success: function (authObj) {
                // 로그인 성공시, API를 호출합니다.
                Kakao.API.request({
                    url: '/v1/user/me',
                    success: function (res) {
//                  console.log(JSON.stringify(res));
                        var id = res.id;
                        var pick = res.properties.profile_image;
                        var nickName = res.properties.nickname;
                        var email = res.kaccount_email;

                        var data = {id: id, pick: pick, nickName: nickName, email: email, join_root: 'KAKAO'};
//                                    console.log(data);return false;
                        $.ajax({
                            dataType: 'text',
                            url: '/index.php/dataFunction/snsMemberLogin',
                            data: data,
                            type: 'POST',
                            success: function (data, status, xhr) {
                                if (data == 'SUCCESS') {
                                    location.href = '/';
                                } else {
                                    alert('로그인에 실패 하였습니다.');
                                }
                            }
                        });

                    },
                    fail: function (error) {
                        console.log(error);
                        alert('로그인에 실패하였습니다.');
                    }
                });
            },
            fail: function (err) {
                console.log(err);
                alert('api 로딩 실패');
            }
        });
    }

    //페이스북 로그인
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1955695061330568',
            xfbml: true,
            version: 'v2.9'
        });
        FB.AppEvents.logPageView();
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/ko_KR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    function fbLogin() {
        FB.login(function (response) {
//                        console.log(JSON.stringify(response));
            FB.api('/me', {fields: 'email, name'}, function (response) {
                if (!response.email) {
                    alert("이메일 정보가 올바르지 않습니다.");
                    FB.logout(function (response) {
                        // Person is now logged out
                    });
                    return false;
                }

                var data = {id: response.id, pick: "http://graph.facebook.com/" + response.id + "/picture?type=large", nickName: response.name, email: response.email, join_root: 'FACEBOOK'};
                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/snsMemberLogin',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        if (data == 'SUCCESS') {
                            location.href = '/';
                        } else {
                            alert('로그인에 실패 하였습니다.');
                        }
                    }
                });
            });
        }, {scope: 'email'});
    }


    //네이버 로그인
    function generateState() {
        // CSRF 방지를 위한 state token 생성 코드
        // state token은 추후 검증을 위해 세션에 저장 되어야 합니다.
        var oDate = new Date();
        return oDate.getTime();
    }
    function saveState(state) {
        $.removeCookie("state_token");
        $.cookie("state_token", state);
    }

    var naver = NaverAuthorize({
        client_id: "WsvE7ybiqJEuIoAkDNX9",
        redirect_uri: "http://playbetcomm.com/index/login",
        client_secret: "viOmzl3FK1"
    });

    function loginNaver() {
        var state = generateState();
        saveState(state);
        naver.login(state);
    }

    window.onload = function () {
// callback이 오면 checkLoginState()함수를 호출한다.
        checkLoginState();
    }
    var tokenInfo = {access_token: "", refresh_token: ""};
    function checkLoginState() {
        var state = $.cookie("state_token");
        if (naver.checkAuthorizeState(state) === "connected") {

//정상적으로 Callback정보가 전달되었을 경우 Access Token발급 요청 수행
            naver.getAccessToken(function (data) {
                var response = data._response.responseJSON;
                if (response.error === "fail") {
//access token 생성 요청이 실패하였을 경우에 대한 처리
                    return;
                }
                tokenInfo.access_token = response.access_token;
                tokenInfo.refresh_token = response.refresh_token;

//sonsole.log에 나온다.
                console.log("success to get access token", response);

                naver.api('/me', tokenInfo.access_token, function (data) {
                    var response = data._response.responseJSON;
//                                console.log("success to get user info", response);
                    var email = response.response.email;
                    var id = response.response.id;
                    var nickName = response.response.nickname;
                    var pic = response.response.profile_image;

                    var data = {id: id, pick: pic, nickName: nickName, email: email, join_root: 'NAVER'};
//                                    console.log(data);return false;
                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/snsMemberLogin',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
                                location.href = '/';
                            } else {
                                alert('로그인에 실패 하였습니다.');
                            }
                        }
                    });

                });

                var surl = 'https://openapi.naver.com/v1/nid/me?access_token=' + response.access_token;

            });
        } else {
//Callback으로 전달된 데이터가 정상적이지 않을 경우에 대한 처리
            return;
        }
    }
//]]>
</script>

<script type="text/javascript">
    $(".mypage_nav li").click(function () {
        var mypage_nav_index = $(this).index(".mypage_nav li");

        $(".mypage_nav_contents").css("display", "none");
        $(".mypage_nav_contents").eq(mypage_nav_index).css("display", "block")

        $(".mypage_nav li").removeClass("select_mypage_nav");
        $(this).addClass("select_mypage_nav");
    });

    $(".result_modal_nav ul li").click(function () {
        $(".result_modal_nav ul li").removeClass("result_modal_nav_select");
        $(this).addClass("result_modal_nav_select");
    });


    $("#cAll").click(function () {
        if ($("#cAll").prop("checked")) {
            $(".check_box").prop("checked", true);
        } else {
            $(".check_box").prop("checked", false);
        }
    });
</script>


