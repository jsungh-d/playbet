<!-- 베팅 모달 -->
<div id="betting" class="align_c playbet_modal" style="display:none;">
    <h2 class="pt15 pb15">
        <span id="betting_modal_contents">비온다 > 전 메뉴 25% 할인</span>에 <br>
        <strong>베팅하시겠습니까?</strong>
    </h2>
    <div class="btn_area pb15">
        <button type="button" class="color_btn cancle_btn default_btn">취소</button>
        <button type="button" class="color_btn darkblue_btn" id="betting_submit_btn">확인</button>
        <input type="hidden" id="betting_board_idx" value="">
        <input type="hidden" id="betting_item_idx" value="">
        <input type="hidden" id="betting_member_idx" value="">
        <input type="hidden" id="betting_item_name" value="">
    </div>
</div>


<!-- 공유 모달 -->
<div id="share" class="align_c playbet_modal" style="display:none;">
    <img class="modal_title_img" src="/images/common/share.png" alt="">
    <h2 class="modal_title_h2">
        <strong>공유해주세요</strong>
    </h2>
    <div class="share_social_btn">
        <div class="button_wrapper dp_ib">
            <button class="mb15 kakao_btn" id="kakao-link-btn" type="button" onclick="sendKakaoTalk();">
                <img src="/images/common/kakao_mini.png" alt=""> 
            </button> 
            <h5>카카오톡</h5>
        </div>
        <div class="button_wrapper dp_ib">
            <button class="mb15 facebook_btn" type="button" onclick="facebookShare();">

                <img src="/images/common/facebook_mini.png" alt="">    
            </button>
            <h5>페이스북</h5>
        </div>
        <div class="button_wrapper dp_ib">
            <button class= "twitter_btn" type="button" onclick="twitterShare();">
                <a href="" id="tweet" data-count="horizontal" data-url="" target="_blank"></a>
                <img src="/images/common/twitter_mini.png" alt="">
            </button>
            <h5>트위터</h5>
        </div>
        <div class="button_wrapper dp_ib">
            <button class= "copy_url_btn" id="" type="button"  onclick="copyUrl();">
                <img src="/images/common/copy_mini.png" alt=""> 
            </button> 
            <h5>URL복사</h5>
        </div>
    </div>
    <div class="mt15">

    </div>

    <input id="clip_target" type="text" value="" style="position:absolute;top:-9999em;"/>

    <div class="input_all_wrapper input_modal_wrapper">
        <label class="scan_label">
            <input type="hidden" class="href_url" name="" value="">
            <input type="hidden" id="share_title" value="">
        </label>
    </div>

    <div class="btn_area">
        <button type="button" class="color_btn default_btn cancle_btn">닫기</button>
    </div>
</div>


<!-- 쿠폰 사용 모달 -->
<div id="coupon" class="align_c playbet_modal" style="display:none;">
    <h2 class="pt15 pb15">
        <span id="cupon_send_user_name">USER1</span> >> <span id="cupon_name">피자 1+1 쿠폰</span><br>
        <strong style="display: inline-block; margin: 3px 0;"><span id="cupon_contents"></span></strong><br>
        옵션을 사용하시겠습니까?<br>
        <h4 id="cupon_time">유효시간 : 2018-06-28</h4>
    </h2>
    <div class="btn_area pt30 pb15">
        <button type="button" class="color_btn cancle_btn default_btn">취소</button>

        <button type="button" class="color_btn darkblue_btn" onclick="openCoupon2();">확인</button>

        <input type="hidden" id="cupon_item_info_idx" value="">
    </div>
</div>

<!-- 쿠폰 사용 확인 모달 -->
<div id="coupon_confirm" class="align_c playbet_modal" style="display:none;">
    <h2 class="pt15 pb15">
        <strong>옵션을 사용하시겠습니까?</strong><br>
        <strong>한번 사용하면 다시 사용할 수 없습니다.</strong>
    </h2>
    <div class="btn_area pt30 pb15">
        <button type="button" class="color_btn cancle_btn default_btn">취소</button>
        <button type="button" class="color_btn darkblue_btn" id="cupon_use_btn">확인</button>
    </div>
</div>





<!-- 결과보기 모달 -->
<div id="result" class="align_c playbet_modal result_modal" style="display:none;">
    <img class="modal_title_img" src="/images/common/result.png" alt="">
    <h2 class="modal_title_h2">
        <strong>베팅 결과값을 입력해주세요</strong>
    </h2>
    <div class="result_modal_nav">
        <div class="btn_area result_btn_area align_c">

        </div>

    </div>

    <h3 class="pt15 pb15">결과값을 선택하면 <span style="color:#2d4375;">&ensp;내기 옵션</span>이 전달됩니다</h3>

    <div class="pt10 btn_area inline_btn_area">
        <button type="button" class="color_btn default_btn cancle_btn">취소</button>
        <input type="hidden" id="cupon_board_idx" value="">
        <input type="hidden" id="cupon_item_idx" value="">
    </div>
</div>


<footer>
    <h6>
        서울시 양천구 남부순환로 31길 36-2 101호<br>
        사업자등록번호 109-10-14703 &ensp; | &ensp;
        <a href="mailto:kiss_699@naver.com" style="color:#3498db; text-decoration: underline;">메일보내기</a> <br>
        제 2014-서울양천-0639호<br>
        Copyright ⓒ 2017 Playbetcomm All rights reserved
    </h6>
</footer>

<!-- typeahead -->
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="/js/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript" src="/js/social-share-callback.js"></script>
<script type="text/javascript">
            $(document).ready(function () {
                if (window.sessionStorage.getItem("navScroll")) {
                    $("nav").scrollLeft(window.sessionStorage.getItem("navScroll"));
                }
                // $("nav").scrollLeft($(".nav_select_list ").offset().left);

                $(".playbet_modal .cancle_btn").click(function () {
                    $(".close-modal").trigger("click");
                });

                $("#betting_submit_btn").click(function () {
                    var board_idx = $("#betting_board_idx").val();
                    var item_idx = $("#betting_item_idx").val();
                    var member_idx = $("#betting_member_idx").val();
                    var item_name = $("#betting_item_name").val();

                    var data = {
                        board_idx: board_idx,
                        item_idx: item_idx,
                        member_idx: member_idx,
                        item_name: item_name
                    };

                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/insBetting',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
                                alert('베팅에 참여하셨습니다.');
                                location.href = "/index/view/" + board_idx + "/?scroll";
                                return false;
                            } else {
                                alert('데이터 처리오류!!');
                                return false;
                            }
                        }
                    });
                });

                // 댓글 버튼 눌렀을때 스크립트
                $(".comment_btn").click(function () {
                    if ($(this).parents().parents().children(".comment_area").css("display") == "none") {
                        $(this).parents().parents().children(".comment_area").show();
                        $(this).parents().parents().children(".view_detail_area").hide();
                    } else {
                        $(this).parents().parents().children(".comment_area").hide();
                    }
                });

                $(".view_link_btn").click(function () {
                    if ($(this).parents().parents().children(".view_detail_area").css("display") == "none") {
                        $(this).parents().parents().children(".view_detail_area").show();
                        $(this).parents().parents().children(".comment_area").hide();
                    } else {
                        $(this).parents().parents().children(".view_detail_area").hide();
                    }
                });

                $("#search_keyword").keydown(function (key) {

                    var search_keyword = $("#search_keyword").val();
                    if (!$.trim(search_keyword)) {
                        search_keyword = 'none';
                    }

                    if (key.keyCode == 13) {
                        location.href = '/?page=0&category=category_all&text=' + search_keyword + '';
                    }
                });

                $("#search_btn").click(function () {
                    var category_idx = 'category_all';
                    var search_keyword = $("#search_keyword").val();
                    if (!$.trim(search_keyword)) {
                        search_keyword = 'none';
                    }

                    $("#frame ul li.category").each(function (index) {
                        if ($("#frame ul li.category:eq(" + index + ")").hasClass('nav_select_list')) {
                            category_idx = $(this).attr('id');
                        }
                    });

                    location.href = '/?page=0&category=' + category_idx + '&text=' + search_keyword + '';
                });

                $('input.optionSearchText').typeahead({
                    source: function (query, process) {
                        return $.get('/index.php/dataFunction/keywordkAutoComplete', {query: query}, function (data) {
                            data = $.parseJSON(data);
                            return process(data);
                        });
                    }
                });

                $("#cupon_use_btn").click(function () {
                    var item_info_idx = $("#cupon_item_info_idx").val();
                    var data = {item_info_idx: item_info_idx};
                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/useCupon',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
                                alert('옵션을 사용하셨습니다.');
                                location.href = '/index/mypage/cupon';
                                return false;
                            } else {
                                alert('데이터 처리오류!!');
                                return false;
                            }
                        }
                    });
                });
            });

            function locationSearch(search_keyword) {
                var category_idx = 'category_all';

                $("#frame ul li.category").each(function (index) {
                    if ($("#frame ul li.category:eq(" + index + ")").hasClass('nav_select_list')) {
                        category_idx = $(this).attr('id');
                    }
                });

                location.href = '/?page=0&category=' + category_idx + '&text=' + search_keyword + '';
            }

            function subCategorySearch(category_sub_idx, category_pidx) {

                location.href = '/?page=0&category=' + category_pidx + '&text=none&category_sub=' + category_sub_idx + '';
            }


            function openShare(title) {
                $("#share").modal({fadeDuration: 400, fadeDelay: 0.30});

                $(".scan_label input").val(location.href);

                $(".href_url_link").text($(".scan_label input").val());

                console.log($(".scan_label input").val(location.href));

                $("#share_title").val(title);

                var url = $(".scan_label input").val();
                var agent = navigator.userAgent.toLowerCase();

                var clipboard = new Clipboard('.copy_url_btn', {
                    text: function () {
                        return url;
                    }
                });

                clipboard.on('success', function (e) {
                    alert("복사되었습니다.");
                    return false;
                });

                clipboard.on('error', function (e) {
                    var inputString = prompt("아래 주소를 길게 눌러 복사해주세요.", url);
                    return false;
                });
            }

            function copyUrl() {
                var url = $(".scan_label input").val();
                var agent = navigator.userAgent.toLowerCase();

                var trb = $(".scan_label input").val();
                var memIdx = '<?= $this->session->userdata('MEMBER_IDX') ?>';
                if (memIdx) {
                    var data = {member_idx: memIdx};
                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/shareUpdate',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
//                                                alert('배팅횟수 초기화 되었습니다.');
                            } else {
                                alert('데이터 처리오류!!');
                                return false;
                            }
                        }
                    });
                }
            }



            function facebookShare() {
                var url = $(".scan_label input").val();
                var title = $("#share_title").val();

                FB.ui(
                        {
                            method: 'share',
                            href: url,
                            quote: "베팅에 참여하세요, " + title
                        },
                        function (response) {
                            if (response && !response.error_code) {
                                alert('페이스북에 공유되었습니다.');
                                var memIdx = '<?= $this->session->userdata('MEMBER_IDX') ?>';
                                if (memIdx) {
                                    var data = {member_idx: memIdx};
                                    $.ajax({
                                        dataType: 'text',
                                        url: '/index.php/dataFunction/shareUpdate',
                                        data: data,
                                        type: 'POST',
                                        success: function (data, status, xhr) {
                                            if (data == 'SUCCESS') {
//                                                alert('배팅횟수 초기화 되었습니다.');
                                            } else {
                                                alert('데이터 처리오류!!');
                                                return false;
                                            }
                                        }
                                    });
                                }

                            } else {
                                alert('페이스북 공유에 실패하였습니다.');
                            }
                        }
                );
            }

            Kakao.init('8eb14b8933fa3356932f73a2d1d9c13b');
            // 카카오톡 공유하기
            function sendKakaoTalk() {
                var url = $(".scan_label input").val();
                var title = $("#share_title").val();
                var img = 'http://playbetcomm.com/images/header/logo.png';

                var memIdx = '<?= $this->session->userdata('MEMBER_IDX') ?>';
                if (memIdx) {
                    var data = {member_idx: memIdx};
                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/shareUpdate',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
//                                                alert('배팅횟수 초기화 되었습니다.');
                            } else {
                                alert('데이터 처리오류!!');
                                return false;
                            }
                        }
                    });
                }

                Kakao.Link.createTalkLinkButton({
                    container: '#kakao-link-btn',
                    label: title,
                    image: {
                        src: img,
                        width: '300',
                        height: '200'
                    },
                    webButton: {
                        text: title,
                        url: url // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
                    }
                });
            }

            // Performant asynchronous method of loading widgets.js
            window.twttr = (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0],
                        t = window.twttr || {};
                if (d.getElementById(id))
                    return t;
                js = d.createElement(s);
                js.id = id;
                js.src = "https://platform.twitter.com/widgets.js";
                fjs.parentNode.insertBefore(js, fjs);

                t._e = [];
                t.ready = function (f) {
                    t._e.push(f);
                };

                return t;
            }(document, "script", "twitter-wjs"));

            function twitterShare() {
                var url = $(".scan_label input").val();
                var title = $("#share_title").val();

                var memIdx = '<?= $this->session->userdata('MEMBER_IDX') ?>';
                if (memIdx) {
                    var data = {member_idx: memIdx};
                    $.ajax({
                        dataType: 'text',
                        url: '/index.php/dataFunction/shareUpdate',
                        data: data,
                        type: 'POST',
                        success: function (data, status, xhr) {
                            if (data == 'SUCCESS') {
//                                    alert('배팅횟수 초기화 되었습니다.');
                            } else {
                                alert('데이터 처리오류!!');
                                return false;
                            }
                        }
                    });
                }

                window.open("https://twitter.com/share?text=" + "베팅에 참여하세요, " + title + "&url=" + url);
            }


            function openCoupon(item_info_idx, name, title, cupon_time, cupon_msg) {
                $("#cupon_send_user_name").html(name);
                $("#cupon_name").html(title);
                $("#cupon_contents").html(cupon_msg);
                $("#cupon_time").html('유효시간 : ' + cupon_time);
                $("#cupon_item_info_idx").val(item_info_idx);
                $("#coupon").modal({fadeDuration: 400, fadeDelay: 0.30});
            }

            function openCoupon2() {
                $("#coupon_confirm").modal({fadeDuration: 400, fadeDelay: 0.30});
            }




            function openBetting(name, title, board_idx, item_idx, member_idx, writer_idx, invert) {

                if (!member_idx) {
                    alert("로그인 후 이용가능합니다.");
                    return false;
                }

                if (parseInt(invert) > parseInt(0)) {
                    alert("종료된 게시물입니다.");
                    return false;
                }

                // if (member_idx == writer_idx) {
                //     alert("자신의 글에는 참여할수 없습니다.");
                //     return false;
                // }

                var data = {board_idx: board_idx, item_idx: item_idx, member_idx: member_idx};

                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/chkBetting',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        if (data == 'SUCCESS') {

                            $("#betting_modal_contents").html(name + '>' + title);
                            $("#betting_board_idx").val(board_idx);
                            $("#betting_item_idx").val(item_idx);
                            $("#betting_member_idx").val(member_idx);
                            $("#betting_item_name").val(name);
                            $("#betting").modal({fadeDuration: 400, fadeDelay: 0.30});
                            return false;

                        } else if (data == 'DUPLE') {

                            alert('이미 참여한 내기입니다.');
                            return false;

                        } else if (data == 'CHK_DUPLE') {
                            if (confirm("베팅을 취소 하시겠습니까?") == true) {    //확인
                                var data_ajax = {item_name: name, board_idx: board_idx, item_idx: item_idx, member_idx: member_idx};
                                $.ajax({
                                    dataType: 'text',
                                    url: '/index.php/dataFunction/delBetting',
                                    data: data_ajax,
                                    type: 'POST',
                                    success: function (data, status, xhr) {
                                        if (data == 'SUCCESS') {
                                            alert("베팅이 취소 되었습니다.");
                                            location.reload();
                                        }

                                        if (data == 'FAILED') {
                                            alert("데이터 처리오류!!");
                                            return false;
                                        }
                                    }

                                });
                            } else {
                                return false;
                            }
                        } else if (data == 'BETTING_DIS') {
                            alert("SNS로 공유하시면 계속 베팅 가능합니다.");
                            return false;
                        }
                    }
                });
            }


            function openImgView(index) {
                $("#imgView").modal();

                setTimeout(function () {
                    var modal_swiper = new Swiper('.modal_swiper-container', {
                        pagination: '.modal_swiper-pagination',
                        nextButton: '.modal_swiper-button-next',
                        prevButton: '.modal_swiper-button-prev',
                        paginationClickable: true
                    });

                    $(".modal_swiper-pagination .swiper-pagination-bullet").eq(index).trigger("click");

                }, 450);
                setTimeout(function () {
                    $("#imgView").parent(".blocker").css({"background-color": "rgba(0,0,0,1)", "padding": "0"});
                }, 0);


            }


            function openResult(board_idx, item_idx) {

                var data = {board_idx: board_idx};
                $("#cupon_board_idx").val(board_idx);
                $("#cupon_item_idx").val(item_idx);

                $.ajax({
                    dataType: 'text',
                    url: '/index.php/dataFunction/chkSendCupon',
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
                        if (data == 'DUPLE') {
                            alert("이미 옵션을 발송하셨습니다.");
                            return false;
                        }

                        if (data == 'SUCCESS') {
                            var data2 = {board_idx: board_idx};
                            $.ajax({
                                dataType: 'text',
                                url: '/index.php/dataFunction/chkResult',
                                data: data2,
                                type: 'POST',
                                success: function (data, status, xhr) {

                                    $("#result").modal();
                                    $(".result_modal_nav .btn_area").html(data);

                                    // 컨텐츠 불러오기
                                    var data_idx = {item_idx: item_idx, board_idx: board_idx};
                                    $.post("/index.php/DataFunction/result_page", data_idx, function (data) {
                                        $(".result_page_contain").html(data);
                                    });

                                    $(".result_modal_nav button").click(function () {
                                        var idx = $(this).val().split('_');
                                        var data = {type: idx[0], idx: idx[1], board_idx: board_idx};

                                        if (confirm("최종 결과값을 선택하시겠습니까?") === true) {
                                            $.ajax({
                                                dataType: 'text',
                                                url: '/index.php/dataFunction/sendCupon',
                                                data: data,
                                                type: 'POST',
                                                success: function (data, status, xhr) {
                                                    if (data == 'SUCCESS') {
                                                        alert("옵션 발송 및 결과처리가 완료되었습니다.");
                                                        $("#result").modal('hide');
                                                        $(".cancle_btn").trigger("click");
                                                        location.href = '/index/mypage/write';
                                                    }

                                                    if (data == 'FAILED') {
                                                        alert("데이터 처리오류!!");
                                                        return false;
                                                    }
                                                }

                                            });
                                        } else {
                                            return false;
                                        }
                                    });

                                }
                            });
                        }
                    }

                });
            }

            var getParameters = function (paramName) {
                // 리턴값을 위한 변수 선언
                var returnValue;

                // 현재 URL 가져오기
                var url = location.href;

                // get 파라미터 값을 가져올 수 있는 ? 를 기점으로 slice 한 후 split 으로 나눔
                var parameters = (url.slice(url.indexOf('?') + 1, url.length)).split('&');

                // 나누어진 값의 비교를 통해 paramName 으로 요청된 데이터의 값만 return
                for (var i = 0; i < parameters.length; i++) {
                    var varName = parameters[i].split('=')[0];
                    if (varName.toUpperCase() == paramName.toUpperCase()) {
                        returnValue = parameters[i].split('=')[1];
                        return decodeURIComponent(returnValue);
                    }
                }
            };

</script>


<script type="text/javascript">
    $(document).ready(function () {
        $("nav li").click(function () {
            $("nav li").removeClass("nav_select_list");
            $(this).addClass("nav_select_list");
        });

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

    });
</script>
</body>

</html>
