<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TIMING ADMIN</title>

        <!-- Bootstrap -->
        <link href="/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="/vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- iCheck -->
        <link href="/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
        <!-- bootstrap-progressbar -->
        <link href="/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
        <!-- JQVMap -->
        <link href="/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
        <!-- bootstrap-daterangepicker -->
        <link href="/vendors/datepicker/bootstrap-datepicker3.css" rel="stylesheet">
        <!-- Datatables -->
        <link href="/vendors/datatables.net-bs/css/dataTables.bootstrap.css" rel="stylesheet">
        <link href="/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
        <!-- bootstrap-daterangepicker -->
        <link href="/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <!-- bootstrap-wysiwyg -->
        <link href="/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
        <!-- Select2 -->
        <link href="/build/css/select2.min.css" rel="stylesheet">
        <!-- Switchery -->
        <link href="/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
        <!-- starrr -->
        <link href="/vendors/starrr/dist/starrr.css" rel="stylesheet">
        <!--crop-->
        <link href="/vendors/cropper/dist/cropper.min.css" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="/build/css/custom.css" rel="stylesheet">
        <link href="/build/css/responsive.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="/vendors/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="/js/select2.min.js"></script>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="pull-left left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="/index/main" class="site_title">
                                <img class="" src="/images/common/logo.png" style="width: 90%">
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <?php
                                $menu_exp = explode('|', $my_menu_info->menu_seq_list);
                                ?>
                                <ul class="nav side-menu">
                                    <?php if (in_array('2', $menu_exp) || in_array('3', $menu_exp)) { ?>
                                        <li>
                                            <a>
                                                <i class="fa fa-home"></i>회원관리
                                                <span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <?php if (in_array('2', $menu_exp)) { ?>
                                                    <li><a href="/index/user_list">가입회원</a></li>
                                                <?php } ?>
                                                <?php if (in_array('3', $menu_exp)) { ?>
                                                    <li><a href="/index/user_drop">탈퇴회원</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array('5', $menu_exp) || in_array('6', $menu_exp) || in_array('7', $menu_exp) || in_array('8', $menu_exp) || in_array('9', $menu_exp) || in_array('10', $menu_exp) || in_array('11', $menu_exp) || in_array('13', $menu_exp) || in_array('14', $menu_exp) || in_array('15', $menu_exp) || in_array('16', $menu_exp)) { ?>
                                        <li>
                                            <a>
                                                <i class="fa fa-home"></i>컨텐츠 관리
                                                <span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <?php if (in_array('5', $menu_exp)) { ?>
                                                    <li><a href="/index/business">업종관리</a></li>
                                                <?php } ?>
                                                <?php if (in_array('6', $menu_exp)) { ?>
                                                    <li><a href="/index/option">종목관리</a></li>
                                                <?php } ?>
                                                <?php if (in_array('7', $menu_exp) || in_array('8', $menu_exp) || in_array('9', $menu_exp) || in_array('10', $menu_exp) || in_array('11', $menu_exp)) { ?>
                                                    <li>
                                                        <a onclick="">
                                                            뉴스관리
                                                            <span class="fa fa-chevron-down"></span>
                                                        </a>
                                                        <ul class="nav child_menu">
                                                            <?php if (in_array('7', $menu_exp)) { ?>
                                                                <li class="sub_menu">
                                                                    <a href="/index/news_object">종목</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('8', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/news_disclosure">공시</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('9', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/news_online">온라인</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('10', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/news_article">신문</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('11', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/news_broadcast">방송</a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                <?php } ?>
                                                <?php if (in_array('13', $menu_exp) || in_array('14', $menu_exp) || in_array('15', $menu_exp) || in_array('16', $menu_exp)) { ?>
                                                    <li>
                                                        <a onclick="">
                                                            키워드관리
                                                            <span class="fa fa-chevron-down"></span>
                                                        </a>
                                                        <ul class="nav child_menu">
                                                            <?php if (in_array('13', $menu_exp)) { ?>
                                                                <li class="sub_menu">
                                                                    <a href="/index/keyword_all">2차 분류어</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('14', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/keyword_kospi">메트릭스</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('15', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/keyword_synonym">유의어</a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('16', $menu_exp)) { ?>
                                                                <li>
                                                                    <a href="/index/keyword_prev">사전등록</a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </li>
                                                <?php } ?>
                                                <?php if (in_array('17', $menu_exp)) { ?>
                                                    <li><a href="/index/push">푸쉬관리</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array('19', $menu_exp) || in_array('20', $menu_exp) || in_array('21', $menu_exp)) { ?>
                                        <li>
                                            <a>
                                                <i class="fa fa-home"></i>앱 관리
                                                <span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <?php if (in_array('19', $menu_exp)) { ?>
                                                    <li><a href="/index/notice">공지사항</a></li>
                                                <?php } ?>
                                                <?php if (in_array('20', $menu_exp)) { ?>
                                                    <li><a href="/index/question">문의하기</a></li>
                                                <?php } ?>
                                                <?php if (in_array('21', $menu_exp)) { ?>
                                                    <li><a href="/index/popup">팝업관리</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array('23', $menu_exp) || in_array('24', $menu_exp) || in_array('25', $menu_exp)) { ?>
                                        <li>
                                            <a>
                                                <i class="fa fa-home"></i>통계
                                                <span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <?php if (in_array('23', $menu_exp)) { ?>
                                                    <li><a href="/index/member">회원 수</a></li>
                                                <?php } ?>
                                                <?php if (in_array('24', $menu_exp)) { ?>
                                                    <li><a href="/index/uvpv">UV/PV</a></li>
                                                <?php } ?>
                                                <?php if (in_array('25', $menu_exp)) { ?>
                                                    <li><a href="/index/rank">컨텐츠 랭킹</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array('27', $menu_exp) || in_array('28', $menu_exp)) { ?>
                                        <li>
                                            <a>
                                                <i class="fa fa-home"></i>관리자
                                                <span class="fa fa-chevron-down"></span>
                                            </a>
                                            <ul class="nav child_menu">
                                                <?php if (in_array('27', $menu_exp)) { ?>
                                                    <li><a href="/index/admin">관리자 계정</a></li>
                                                <?php } ?>
                                                <?php if (in_array('28', $menu_exp)) { ?>
                                                    <li><a href="/index/work_history">작업 히스토리</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>						
                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Lock">
                                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="/index.php/dataFunction/logout">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <img src="images/img.jpg" alt=""><?= $this->session->userdata('admin_name') ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><a href="javascript:;"> Profile</a></li>
                                        <li>
                                            <a href="javascript:;">
                                                <span class="badge bg-red pull-right">50%</span>
                                                <span>Settings</span>
                                            </a>
                                        </li>
                                        <li><a href="javascript:;">Help</a></li>
                                        <li><a href="/index.php/dataFunction/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- /top navigation -->