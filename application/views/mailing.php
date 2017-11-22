<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
        <meta name="google-site-verification" content="SHQ5mb_BiAqafZ5n4z38_Du_pV8J_ulUJSV3FdDyy-8" />
    </head>
    <body>
        <section style="padding: 20px; max-width: 800px;">
            <div>
                <img src="http://playbetcomm.com/images/header/logo2.png" alt="">
            </div>

            <div style="padding:20px;">
                <h2 style="margin:0; padding:15px 0; font-size: 16px; line-height: 1.5; font-weight: 300; "><strong>Playbetcomm 알림</strong></h2>
                <hr>
                <h4 style="margin:0; margin-top: 15px; font-size: 13px;
                    line-height: 1.6;
                    font-weight: 300;">안녕하십니까, <span style="color: #3498db;"><?= $NAME ?></span> 님</h4>
                <h4 style="margin:0; margin-bottom: 30px; font-size: 13px;
                    line-height: 1.6;
                    font-weight: 300;">Playbetcomm에 접속하여 알림을 확인하세요.</h4>

                <div style="text-align: center; background: #f2f2f2; padding:40px 0">
                    <?php if ($TYPE == 'SUCCESS') { ?>
                        <h4 style="margin:0; font-size: 13px; line-height: 1.6; font-weight: 300;"><?= $TITLE ?> 의 베팅에 성공하셨습니다.</h4>
                        <h4 style="margin:0; font-size: 13px; line-height: 1.6; font-weight: 300;">옵션을 확인해주세요.</h4>
                    <?php } ?>
                    <?php if ($TYPE == 'FAILED') { ?>
                        <h4 style="margin:0; font-size: 13px; line-height: 1.6; font-weight: 300;"><?= $TITLE ?> 의 베팅에 실패하셨습니다.</h4>
                    <?php } ?>
                    <a style="display: block; padding: 6px 30px; border: none; color: #fff; border-radius: 3px; margin: 15px  auto 0; width: 115px; box-shadow: 0 1px 5px #dcdcdc; background: #23345c; text-decoration: none;" href="http://playbetcomm.com/index/mypage/write" target="_blank"><h2 style="margin:0; font-size: 15px;"><strong>확인하기</strong></h2>
                    </a>
                </div>
                <h4 style="margin-top:30px; margin-bottom: 30px; color:#848484; font-size: 13px;
                    line-height: 1.6; font-weight: 300;">본 메일은 Playbetcomm 회원가입에 의해 발송된 발송 전용 메일입니다.</h4>
                <hr>
            </div>
            <div style="padding:0 20px;">
                <h4 style="display:inline-block; margin:0; margin-bottom: 15; color:#848484; font-size: 13px; line-height: 1.6; font-weight: 300;">
                    서울시 양천구 남부순환로 31길 36-2, 101호. 마이프렌드폰.<br>
                    사업자등록번호 109-10-14703.
                </h4>
                <img src="http://playbetcomm.com/images/header/logo.png" style="float:right;" alt="">
            </div>


        </section>
    </body>
</html>