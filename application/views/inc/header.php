<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
        <meta name="google-site-verification" content="SHQ5mb_BiAqafZ5n4z38_Du_pV8J_ulUJSV3FdDyy-8" />
        <meta name="naver-site-verification" content="6d0d8aa4d64036ed47431c908e9c9c206da7eabd"/>
        <title>플레이벳컴</title>
        <meta name="description" content="O2O 기반 bet commerce. 식음, 패션뷰티, 여가, 생활편의, 여행, 기타.">
        <meta property="og:type" content="website">
        <meta property="og:title" content="플레이벳컴">
        <meta property="og:description" content="O2O 기반 bet commerce. 식음, 패션뷰티, 여가, 생활편의, 여행, 기타.">
        <meta property="og:image" content="http://www.playbetcomm.com/images/header/logo.png">
        <meta property="og:url" content="http://www.playbetcomm.com/">
        <link rel="stylesheet" type="text/css" href="/css/reset.css">
        <link rel="stylesheet" type="text/css" href="/css/jquery.modal.css">
        <link rel="stylesheet" type="text/css" href="/css/swiper.css">
        <link rel="stylesheet" type="text/css" href="/css/common.css">
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <link href="/css/responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">   
        <![if !IE]>
        <link rel="stylesheet" type="text/css"  href="/css/ms.css">
        <![endif]>
        <script src="http://code.jquery.com/jquery-latest.min.js"></script> 
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="/js/jquery.modal.min.js"></script>
        <script src="/js/swiper.js"></script>
        <script src="/js/clipboard.min.js"></script>
        <script src="/js/sly.js"></script>
        <script src="/vendors/moment/moment.js"></script>
        <script>
            $(document).ready(function () {
                $(".menu_link").click(function () {
                    $(".right_hamburger_menu").animate({"right": "0"}, {duration: 400, queue: false});
                    $(".black_wrapper").css("display", "block");
                    $(".black_wrapper").animate({"opacity": "0.7"}, {duration: 100, queue: false});
                });

                $(".black_wrapper").click(function () {
                    $(".right_hamburger_menu").animate({"right": "-100%"}, {duration: 350, queue: false});
                    $(".black_wrapper").animate({"opacity": "0"}, {duration: 100, queue: false});
                    setTimeout(function () {
                        $(".black_wrapper").css("display", "none");
                    }, 355);

                });

                //슬라이 안쓸때 스크립트
                $("nav ul").css("width", 80 * $("nav ul li").length + "px");

            });
        </script>
    </head>
    <body style="background: #f2f2f2;">
        <div class="header_wrapper">
            <header>
                <div class="header_inner_div header_inner_div1">
                    <a href="/index" class="">
                        <img class="logo" src="/images/header/logo.png" alt="">
                    </a>
                </div>
                <div class="header_inner_div header_inner_div2">
                    <div class="responsive_header_area ">
                        <div class="header_search dp_ib">
                            <input type="search" class="optionSearchText" id="search_keyword" value="<?php if ($this->input->get('text') && $this->input->get('text') != 'none') echo $this->input->get('text') ?>" placeholder="지역, 키워드를 입력하세요">
                            <input type="submit" id="search_btn" value="">
                        </div>

                        <div class="dp_ib header_login">
                            <?php if (!$this->session->userdata('MEMBER_IDX')) { ?>
                                <a href="/index/login" class="login_link">
                                    <span>LOGIN</span>
                                </a>
                                <!--  <a href="/index/mypage" class="login_link">
                                     <span>MYPAGE</span>
                                 </a>
                                 <a href="/index.php/dataFunction/logout" class="login_link">
                                     <span>LOGOUT</span>
                                 </a> -->
                            <?php } else { ?>
                                <a class="menu_link">
                                    <img src="/images/common/list.png" alt="">
                                    <?php
                                    $new_cnt = '';
                                    foreach ($write_new_lists as $row) {
                                        $new_cnt .= $row['CNT'] . ',';
                                    }
                                    $new_array = explode(',', substr($new_cnt, 0, -1));

                                    if ((in_array('0', $new_array) && $write_new_lists) || $cupon_new->CNT > 0 || $history_new->CNT > 0) {
                                        ?>
                                        <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                                    <?php } ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </header>
        </div>

        <div class="right_hamburger_menu">
            <ul>
                <li class="hamburger solid_bottom">
                    <h4><strong>MENU</strong></h4>
                </li>
                <a href="/index/mypage" class="login_link">
                    <li class="hamburger solid_bottom">
                        내 정보
                        <?php
                        $new_cnt = '';
                        if ($this->session->userdata('MEMBER_IDX')) {
                            foreach ($write_new_lists as $row) {
                                $new_cnt .= $row['CNT'] . ',';
                            }
                        }
                        $new_array = explode(',', substr($new_cnt, 0, -1));

                        if ($this->session->userdata('MEMBER_IDX')) {
                            if (in_array('0', $new_array) || $cupon_new->CNT > 0 || $history_new->CNT > 0) {
                                ?>
                                <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                                <?php
                            }
                        }
                        ?>
                        <div class="menu_icon float_r">
                            <img src="/images/header/icon_login_mobile.png" alt="">
                        </div>
                    </li>
                </a>

                <a href="/index/write">
                    <li class="hamburger solid_bottom">
                        내기 등록
                        <div class="menu_icon float_r">
                            <img src="/images/header/pen-black.png" alt="">
                        </div>
                    </li>
                </a>

                <a href="/index/premium">
                    <li class="hamburger solid_bottom">
                        프리미엄
                        <div class="menu_icon float_r">
                            <img src="/images/header/premium-badge.png" alt="">
                        </div>
                    </li>
                </a>

                <a href="/index.php/dataFunction/logout" class="login_link">
                    <li class="hamburger solid_bottom">
                        로그아웃
                        <div class="menu_icon float_r">
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="body_wrapper">
            <div class="header_blank_container"></div>
            <div class="nav_contain">
                <div class="nav_wrapper position_r">
                    <nav id="frame">
                        <ul>
                            <li id="category_all" class="category<?php if ($this->input->get('category', true) == 'category_all' || !$this->input->get('category', true)) echo ' nav_select_list'; ?>"><h5>전체</h5></li>
                            <?php foreach ($category_lists as $row) { ?>
                                <li id="<?= $row['CATEGORY_IDX'] ?>" class="category<?php if ($this->input->get('category', true) == $row['CATEGORY_IDX']) echo ' nav_select_list'; ?>"><h5><?= $row['NAME'] ?></h5></li>
                            <?php } ?>
                        </ul>
                    </nav>
                    <img class="gradi2" src="/images/common/gradi2.png" alt="">
                    <img class="gradi1" src="/images/common/gradi1.png" alt="">
                    <img class="next" src="/images/common/next.png" alt="">
                    <img class="prev" src="/images/common/prev.png" alt="">
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {

                    var keydownCnt = 0;

                    $(".next").click(function () {
                        keydownCnt++;
                        if ($(window).width() > 550) {
                            if (keydownCnt >= 1) {
                                keydownCnt = 1;
                            }
                        } else {
                            if (keydownCnt >= 2) {
                                keydownCnt = 2;
                            }
                        }
                        $("#frame").animate({scrollLeft: ($("#frame ul").width() / 3) * keydownCnt}, 200);
                    });

                    $(".prev").click(function () {
                        keydownCnt--;
                        if (keydownCnt <= 0) {
                            keydownCnt = 0;
                        }
                        $("#frame").animate({scrollLeft: ($("#frame ul").width() / 3) * keydownCnt}, 200);
                    });

                    $(".category").click(function () {
                        var category_idx = $(this).attr('id');
                        var search_keyword = $("#search_keyword").val();
                        if (!$.trim(search_keyword)) {
                            search_keyword = 'none';
                        }
                        window.sessionStorage.setItem("navScroll", $("nav").scrollLeft());
//                        location.href = '/?page=0&category=' + category_idx + '&text=' + search_keyword + '';
                        location.href = '/?page=0&category=' + category_idx + '&text=none';
                    });

                });
            </script>