
<div class="mypage_nav">
    <ul>
        <li class="premium_nav select_mypage_nav">
            <h5>
                <!-- <img src="/images/common/clock1.png" alt="" style="width:12px">  -->
                프리미엄
                <!-- <?php if ($history_new->CNT > 0) { ?>
                <img src="/images/new.png" class="new_text" style="margin-left: 3px; width:15px;">
                <?php } ?> -->
            </h5>
        </li>
        <li class="premium_nav">
            <h5>
                <!-- <img src="/images/common/record.png" alt="" style="width:12px">  -->
                지역광고
            </h5>
        </li>
        <li class="premium_nav">
            <h5>
                <!-- <img src="/images/common/pen.png" alt="" style="width:12px">  -->
                온라인광고
            </h5>
        </li>
    </ul>
</div>
<section class="main mypage_main">
    <div class="premium_contents">
        <div class="premium_top_text">
            <h4>프리미엄 서비스 안내</h4>
            <div class="premium_top_box_area">
                <div class="premium_top_box">
                    <h1 class="premium_top_box_title"><strong>Basic<br>Members</strong></h1>
                    <h4 class="premium_top_box_subtitle">베이직 등급 회원</h4>
                    <span class="big_price_text">&#8361; 0 </span><span>/ 월</span>
                    <table>
                        <tr>
                            <td class="left_td"><h5>내기 딜 등록</h5></td>
                            <td class="right_td"><h5>딜 종료 7일 후</h5></td>
                        </tr>
                        <tr>
                            <td class="left_td"><h5>베팅 제한시간</h5></td>
                            <td class="right_td"><h5>5일 이하</h5></td>
                        </tr>
                        <tr>
                            <td class="left_td"><h5>파일 첨부</h5></td>
                            <td class="right_td"><h5>사진 4장 이하</h5></td>
                        </tr>
                    </table>
                </div>
                <div class="premium_top_box">
                    <h1 class="premium_top_box_title"><strong><span class="text_lightred">Pre</span><span class="text_blue">mi</span><span class="text_yellow">um</span><br>Members</strong></h1>
                    <h4 class="premium_top_box_subtitle"><span class="text_lightred">프리</span><span class="text_blue">미엄</span> <span class="text_yellow">등급</span> 회원</h4>
                    <span class="big_price_text">&#8361; 5,000 </span><span>/ 월</span>
                    <table>
                        <tr>
                            <td class="left_td"><h5>내기 딜 등록</h5></td>
                            <td class="right_td"><h5>무제한</h5></td>
                        </tr>
                        <tr>
                            <td class="left_td"><h5>베팅 제한시간</h5></td>
                            <td class="right_td"><h5>무제한</h5></td>
                        </tr>
                        <tr>
                            <td class="left_td"><h5>파일 첨부</h5></td>
                            <td class="right_td"><h5>사진 무제한</h5></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="right_td"><h5>동영상 100MB 이하</h5></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="mypage_nav_contents">
            <div class="contents pl15 pr15 pt15 pb15">
                <h4 class="premium_table_title">프리미엄 서비스 신청</h4>
                <?php if($info->TYPE == 'I'){?>
                <h5 class="premium_table_subtitle">회원님은 현재 <span class="text_blue">입금 확인 중</span>입니다.</h5>
                <?php }else if($info->TYPE == 'Y') {?>
                <h5 class="premium_table_subtitle">회원님은 현재 <span class="text_blue"><?=$info->PREMIUM_DATE?></span>까지 프리미엄 서비스 이용 가능합니다.</h5>
                <?php }else {?>
                <h5 class="premium_table_subtitle">회원님은 현재 <span class="text_blue">베이직 등급 회원</span>입니다. 프리미엄 서비스를 만나보세요.</h5>
                <?php }?>
                
                <form action="/index.php/dataFunction/insPremium" method="post">
                    <table class="premium_table">
                        <!-- <colgroup><col width="20%"><col width="30%"><col width="20%"><col width="30%"></colgroup> -->
                        <tbody>
                            <tr>
                                <td class="left_td">
                                    <h4>은 행</h4>            
                                </td>
                                <td class="right_td">
                                    <h4>기업은행</h4>            
                                </td>
                                <td class="left_td">
                                    <h4>신청기간</h4>            
                                </td>
                                <td class="right_td">
                                    <select id="priceSelect" name="date">
                                        <option value="3">3개월</option>
                                        <option value="6">6개월</option>
                                        <option value="12">12개월</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="left_td">
                                    <h4>계좌번호</h4>            
                                </td>
                                <td class="right_td">
                                    <h4>078-157249-01-013</h4>            
                                </td>
                                <td class="left_td">
                                    <h4>금 액</h4>
                                </td>
                                <td class="right_td">
                                    <h4 id="price">15000원</h4>
                                </td>
                            </tr>

                            <tr>
                                <td class="left_td">
                                    <h4>예금주</h4>            
                                </td>
                                <td class="right_td">
                                    <h4>마이프렌드폰</h4>            
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if($info->TYPE == 'I'){?>
                    <div class="premium_ins_btn_area">
                        <button type="button" class="premium_ins_btn red_btn">입금확인중</button>  
                        <button type="button" class="premium_del_btn blue_btn">취소</button>  
                    </div>
                    <?php }else if($info->TYPE == 'Y') {?>
                    <div class="premium_ins_btn_area">
                        <button type="button" class="premium_ins_btn blue_btn">프리미엄 이용중</button>  
                    </div>      
                    <?php }else {?>
                    <div class="premium_ins_btn_area">
                        <button type="submit" class="premium_ins_btn yellow_btn">신청하기</button>  
                    </div>      
                    <?php }?>
                </form>
            </div>
        </div>
    </div>
    <div class="premium_contents">
        <div class="mypage_nav_contents advertisement1">
        </div>
    </div>
    <div class="premium_contents">
        <div class="mypage_nav_contents advertisement2">
        </div>
    </div>
    <div class="black_wrapper">
        <div class="click_close"></div>
        <div class="black"></div>
    </div>

    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".betting_modal .cancle_btn").click(function () {
                $(".close-modal").trigger("click");
            });

            $(".mypage_nav_contents").css("display", "block")

            $(".mypage_nav li").click(function () {
                var premium_contents = $(this).index(".mypage_nav li");

                if(premium_contents == 1 || premium_contents == 2){
                    alert("준비중입니다.");
                    return false;
                }
                $(".premium_contents").css("display", "none");
                $(".premium_contents").eq(premium_contents).css("display", "block");

                $(".mypage_nav li").removeClass("select_mypage_nav");
                $(this).addClass("select_mypage_nav");

            });

            $("#priceSelect").change(function(){
                var price = $(this).select().val()*5000;
                $("#price").text(price + "원");
            });

            $(".premium_del_btn").click(function(){
                if(confirm("프리미엄 신청을 취소하시겠습니까?")){
                    $.ajax({
                        url:"/index.php/dataFunction/delPremium",
                        dataType:"text",
                        success: function(result){
                            $("body").append(result);
                        }
                    });
                }
            });

        });
    </script>
</section>
