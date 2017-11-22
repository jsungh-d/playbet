<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">   
        <script src="http://code.jquery.com/jquery-latest.min.js"></script> 
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>
    <body>
        <form name="smsform" id="smsForm" method="POST" action="http://link.smsceo.co.kr/sendsms_utf8.php">
            <input type="hidden" name="userkey" value="UDMFN1Y4BGYCbFV8VToDaAUmAjUHdQYmCmcGYAYv">
            <input type="hidden" name="userid" value="jopersie">
            <input type="hidden" name="msg" value="[플레이벳컴] 예약전송 테스트중">
            <input type="hidden" name="phone" value="<?= $info->PHONE ?>">
            <input type="hidden" name="callback" value="0264665050">
            <input type="hidden" name="send_date" value="<?= $time_res->EFFECTIVE_TIME ?>">
        </form>

        <script src="http://malsup.github.com/jquery.form.js"></script>
        <script>
            $(document).ready(function () {
                submit();
            });
            function submit() {
                var data = {
                    userkey: "UDMFN1Y4BGYCbFV8VToDaAUmAjUHdQYmCmcGYAYv",
                    userid: 'jopersie',
                    msg: "[플레이벳컴] 베팅 결과값을 입력해주세요. http://playbetcomm.com/index/mypage/write",
                    phone: '<?= $info->PHONE ?>',
                    callback: '0264665050',
                    send_date: '<?= $time_res->EFFECTIVE_TIME ?>'
                };
                $.ajax({
                    dataType: 'jsonp',
                    url: 'http://link.smsceo.co.kr/sendsms_utf8.php',
                    crossDomain: true,
                    data: data,
                    type: 'POST',
                    success: function (data, status, xhr) {
//                        alert("등록되었습니다.");
//                        location.href = '/';
                    },
                    error: function () {
//                        alert('Failed!');
                    }
                });

                alert("등록되었습니다.");
                location.href = '/';
            }
        </script>
    </body>
</html>