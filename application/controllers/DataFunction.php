<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataFunction
 *
 * @author dev_piljae
 */
class DataFunction extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->PLAYBAT = $this->load->database('PLAYBAT', TRUE);

        $this->load->helper(array('url', 'date', 'form', 'alert'));
        $this->load->model('Db_m');
        $this->load->library('session');
        $this->load->library('uploader');
    }

    function upload_receiver_from_ck() {
        $this->load->library('upload');
        $url_path = "/boardUpFile";
        $upload_config = Array(
            'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
            'allowed_types' => 'gif|jpg|jpeg|png|bmp',
            'max_size' => '512000',
            'encrypt_name' => TRUE
        );
        $this->upload->initialize($upload_config);
        if (!$this->upload->do_upload("upload")) {
            echo "<script type='text/javascript'>alert('업로드에 실패 하였습니다. " . $this->upload->display_errors('', '') . "')</script>";
        } else {

            $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
            $data = $this->upload->data();
            $fileName = $data['file_name'];
            $url = "/boardUpFile/" . $fileName;

            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('" . $CKEditorFuncNum . "', '" . $url . "', '전송에 성공 했습니다.')</script>";
        }
    }

    function adminLogin() {
        //sql 인젝션 방지
        $id = $this->PLAYBAT->escape($this->input->post('adminId', TRUE));
        $pwd = $this->PLAYBAT->escape($this->input->post('adminPw', TRUE));

        $sql = "SELECT
                    ADMIN_IDX,
                    ID 
                FROM 
                    ADMIN 
                WHERE
                    ID = $id AND
                    PWD = $pwd";
        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');
        if ($res) {
            //세션 생성
            $newdata = array(
                'ADMIN_IDX' => $res->ADMIN_IDX
            );
            $this->session->set_userdata($newdata);
            alert('로그인 되었습니다.', '/admin/main');
        } else {
            alert('아이디나 비밀번호를 확인해주세요', '/admin');
        }
    }

    function adminModfiy() {
        $updateArray = array(
            'PWD' => $this->input->post('admin_pwd', true)
        );

        $updateWhere = array(
            'ID' => $this->input->post('admin_id', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('ADMIN', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/admin/main');
        } else {
            alert('수정 되었습니다.', '/admin/main');
        }
    }

    function adminLogout() {
        $this->session->sess_destroy();
        echo "<script language='javascript'>";
        echo "alert('로그아웃 되었습니다.');";
        echo "location.href='/admin'";
        echo "</script>";
    }

    function addCategory() {

        if ($this->input->post('show_level', true) == '') {
            $show_level = 0;
        } else if ($this->input->post('show_level', true) >= 0) {
            $show_level = $this->input->post('show_level', true);
        }

        $insArray = array(
            'PNUM' => $this->input->post('pnum', true),
            'NAME' => $this->input->post('name', true),
            'SHOW_LEVEL' => $show_level,
            'USE_YN' => $this->input->post('use_yn', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->insData('CATEGORY', $insArray, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/admin/categoryConfig');
        } else {
            alert('등록 되었습니다.', '/admin/categoryConfig');
        }
    }

    function modCategory() {
        if ($this->input->post('show_level', true) == '') {
            $show_level = 0;
        } else if ($this->input->post('show_level', true) >= 0) {
            $show_level = $this->input->post('show_level', true);
        }

        $updateArray = array(
            'PNUM' => $this->input->post('pnum', true),
            'NAME' => $this->input->post('name', true),
            'SHOW_LEVEL' => $show_level,
            'USE_YN' => $this->input->post('use_yn', true)
        );

        $updateWhere = array(
            'CATEGORY_IDX' => $this->input->post('category_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('CATEGORY', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/admin/categoryConfig');
        } else {
            alert('수정 되었습니다.', '/admin/categoryConfig');
        }
    }

    function login() {
        //sql 인젝션 방지
        $email = $this->PLAYBAT->escape($this->input->post('email', TRUE));
        $pwd = $this->PLAYBAT->escape($this->input->post('pwd', TRUE));

        $sql = "SELECT
                    MEMBER_IDX, 
                    NAME 
                FROM 
                    MEMBER 
                WHERE 
                    EMAIL = $email AND
                    PWD = $pwd";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if (!$res) {
            alert("계정 정보가 없습니다.", '/index/login');
        } else if ($res) {
            //세션 생성
            $newdata = array(
                'MEMBER_IDX' => $res->MEMBER_IDX,
                'NAME' => $res->NAME
            );
            $this->session->set_userdata($newdata);
            alert('로그인 되었습니다.', '/');
        }
    }

    function snsMemberLogin() {
        $sql = "SELECT
                    MEMBER_IDX, 
                    NAME
                FROM 
                    MEMBER 
                WHERE 
                EMAIL = '" . $this->input->post('email', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res) {
            //세션 생성
            $newdata = array(
                'MEMBER_IDX' => $res->MEMBER_IDX,
                'NAME' => $res->NAME
            );
            $this->session->set_userdata($newdata);
            echo 'SUCCESS';
        } else {
            $insArray = array(
                'JOIN_ROOT' => $this->input->post('join_root', true),
                'NAME' => $this->input->post('nickName', true),
                'EMAIL' => $this->input->post('email', true),
                'PROFILE_IMG' => $this->input->post('pick', true),
                'PWD' => $this->input->post('id', true),
                'TYPE' => 'N'
            );

            $this->PLAYBAT->trans_start(); // Query will be rolled back

            $this->Db_m->insData('MEMBER', $insArray, 'PLAYBAT');

            $this->PLAYBAT->trans_complete();

            if ($this->PLAYBAT->trans_status() === FALSE) {
                alert('데이터 처리오류!!', '/index/login');
            } else {

                $sql = "SELECT
                    MEMBER_IDX, 
                    NAME
                FROM 
                    MEMBER 
                WHERE 
                EMAIL = '" . $this->input->post('email', true) . "'";

                $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

                //세션 생성
                $newdata = array(
                    'MEMBER_IDX' => $res->MEMBER_IDX,
                    'NAME' => $res->NAME
                );
                $this->session->set_userdata($newdata);
                echo 'SUCCESS';
            }
        }
    }

    function logout() {
        $this->session->sess_destroy();
        echo "<script language='javascript'>";
        echo "alert('로그아웃 되었습니다.');";
        echo "location.href='/'";
        echo "</script>";
    }

    function indexScroll() {

        $add_where = "";
        if ($this->input->get('category', true) != 'category_all') {

            $sql = "SELECT
                        COUNT(*) CNT 
                    FROM 
                        CATEGORY 
                    WHERE 
                        PNUM = '" . urldecode($this->input->get('category', true)) . "'";
            $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

            if ($res->CNT == 0) {
                $add_where .= "AND C.CATEGORY_IDX = '" . urldecode($this->input->get('category', true)) . "' ";
            } else {
                if ($this->input->get('category_sub', true)) {
                    $add_where .= "AND C.CATEGORY_IDX = '" . urldecode($this->input->get('category_sub', true)) . "' ";
                }
                $add_where .= "AND C.PNUM = '" . urldecode($this->input->get('category', true)) . "' ";
            }
        }

        if ($this->input->get('text', true) != 'none') {
            $add_where .= "AND (MATCH(AO.SIDO, AO.SIGUNGU, AO.DONG) AGAINST('" . urldecode($this->input->get('text', true)) . "' IN BOOLEAN MODE) OR 
            MATCH(B.TIME_LINE, B.TITLE, B.HASH_TAG) AGAINST('" . urldecode($this->input->get('text', true)) . "' IN BOOLEAN MODE)) ";
        }

        $perPage = 5;

        if (!empty($this->input->get("page"))) {

            $start = ceil($this->input->get("page") * $perPage);
            $limit = $perPage;
        } else {
            $start = 0;
            $limit = $perPage;
        }

//        IF(
//                        (SELECT 
//                            PNUM 
//                        FROM 
//                            CATEGORY
//                        WHERE
//                            CATEGORY_IDX = B.CATEGORY_IDX
//                        ) = 0, 'AREA', 
//                        (
//                            SELECT 
//                                NAME 
//                            FROM 
//                                CATEGORY
//                            WHERE
//                                CATEGORY_IDX = B.CATEGORY_IDX
//                        )
//                    ) CATEGORY_TYPE,
//                    IF(
//                        (SELECT 
//                            PNUM 
//                        FROM 
//                            CATEGORY
//                        WHERE
//                            CATEGORY_IDX = B.CATEGORY_IDX
//                        ) = 0, 'AREA', B.CATEGORY_IDX
//                    ) CATEGORY_IDX,
//                    IF(
//                        (SELECT 
//                            PNUM 
//                        FROM 
//                            CATEGORY
//                        WHERE
//                            CATEGORY_IDX = B.CATEGORY_IDX
//                        ) = 0, 'AREA', 
//                        (
//                            SELECT 
//                                PNUM 
//                            FROM 
//                                CATEGORY
//                            WHERE
//                                CATEGORY_IDX = B.CATEGORY_IDX
//                        )
//                    ) CATEGORY_PNUM,

        $sql = "SELECT 
                    B.BOARD_IDX,
                    M.NAME,
                    M.BUSINESS_NAME,
                    CONCAT(AO.SIDO, '&nbsp;&nbsp;', AO.SIGUNGU) LOCATION,
                    IF(
                        B.CATEGORY_IDX = 1, 'AREA', 
                        B.HASH_TAG
                    ) CATEGORY_TYPE,
                    AO.SIGUNGU,
                    B.TITLE,
                    B.TIME_LINE,
                    B.EFFECTIVE_TIME,
                    B.LINK_URL,
                    M.MEMBER_IDX,
                    NOW() NOW
                FROM
                    BOARD B, MEMBER M, ADDRESS_ORG AO, CATEGORY C
                WHERE 
                    B.MEMBER_IDX = M.MEMBER_IDX AND
                    B.CATEGORY_IDX = C.CATEGORY_IDX AND
                    B.ADDRESS_ORG_IDX = AO.ADDRESS_ORG_IDX AND
                    B.DEL_YN ='N' ";
        $sql .= $add_where;
        $sql .= "ORDER BY B.TIMESTAMP DESC LIMIT $start, $limit";

        $data['lists'] = $this->Db_m->getList($sql, 'PLAYBAT');

        foreach ($data['lists'] as $row) {
            $item_sql = "SELECT
                            I.ITEM_IDX, 
                            I.NAME,
                            (
                                SELECT 
                                    COUNT(*) CNT 
                                FROM 
                                    ITEM_INFO II 
                                WHERE 
                                    II.ITEM_IDX = I.ITEM_IDX
                            ) CNT,
                            CASE WHEN 
                              (
                                SELECT 
                                    II.CUPON_RECEPTION
                                FROM 
                                    ITEM_INFO II
                                WHERE 
                                    II.ITEM_IDX = I.ITEM_IDX AND
                                    II.BOARD_IDX = I.BOARD_IDX AND
                                    II.CUPON_RECEPTION = 'Y'
                                    LIMIT 0, 1
                              ) = 'Y'
                              THEN 'Y'
                               WHEN 
                              (
                                SELECT 
                                    II.CUPON_RECEPTION
                                FROM 
                                    ITEM_INFO II
                                WHERE 
                                    II.ITEM_IDX = I.ITEM_IDX AND
                                    II.BOARD_IDX = I.BOARD_IDX AND
                                    II.CUPON_RECEPTION = 'N'
                                    LIMIT 0, 1
                              ) = 'N'
                              THEN 'N'
                              ELSE 'NO'
                            END MY_CHK
                        FROM 
                            ITEM I
                        WHERE 
                        I.BOARD_IDX = '" . $row['BOARD_IDX'] . "'";

            $data['item_lists' . $row['BOARD_IDX']] = $this->Db_m->getList($item_sql, 'PLAYBAT');
        }

        $result = $this->load->view('load_view/index', $data);
//        echo $sql;exit;
        echo json_encode($result);
    }

    function guLists() {
        $sql = "SELECT
                    ADDRESS_ORG_IDX, 
                    SIGUNGU 
                FROM 
                    ADDRESS_ORG 
                WHERE
                    SIDO = '" . $this->input->post('si', true) . "'
                    GROUP BY SIGUNGU ORDER BY ADDRESS_ORG_IDX ASC";

        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        $data = '<option value="">선택</option>';
        if ($res) {
            foreach ($res as $row) {
                if ($this->input->post('infoVal', true) == $row['SIGUNGU']) {
                    $selcted = "selected";
                } else {
                    $selcted = "";
                }
                $data .= '<option value="' . $row['SIGUNGU'] . '" ' . $selcted . '>' . $row['SIGUNGU'] . '</option>';
            }
        }

        print_r($data);
    }

    function gugunLists() {
        $sql = "SELECT
                    ADDRESS_ORG_IDX, 
                    DONG 
                FROM 
                    ADDRESS_ORG 
                WHERE
                    SIGUNGU = '" . $this->input->post('gu', true) . "'
                    GROUP BY DONG ORDER BY ADDRESS_ORG_IDX ASC";

        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        $data = '<option value="">선택</option>';
        if ($res) {
            foreach ($res as $row) {
                if ($this->input->post('infoVal', true) == $row['ADDRESS_ORG_IDX']) {
                    $selcted = "selected";
                } else {
                    $selcted = "";
                }
                $data .= '<option value="' . $row['ADDRESS_ORG_IDX'] . '" ' . $selcted . '>' . $row['DONG'] . '</option>';
            }
        }

        print_r($data);
    }

    function sendSms() {

        $sql = "SELECT
                    PHONE 
                FROM 
                    MEMBER
                WHERE 
                PHONE = '" . $this->input->post('phone', true) . "'";
        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res) {

            echo "DUPLE";
            exit;
        } else {

            $accessKey = rand(111111, 999999);

            $userkey = "UDMFN1Y4BGYCbFV8VToDaAUmAjUHdQYmCmcGYAYv";
            $userid = "jopersie";
            $phone = $this->input->post('phone', true);
            $callback = "0264665050";
            $msg = "[플레이벳컴] 인증번호는 " . $accessKey . " 입니다.";
            $send_date = "2011-01-11 00:00:00"; // 예약메세지일 경우 사용하시기 바랍니다.

            $url = "http://link.smsceo.co.kr/sendsms_utf8.php?userkey=" . $userkey;
            $url .= "&userid=" . $userid;
            $url .= "&phone=" . $phone;
            $url .= "&callback=" . $callback;
            $url .= "&msg=" . urlencode($msg);
            $url .= "&send_date=" . $send_date;

            $result = array();
            $result = $this->smsRes($url); // 결과 출력형식을 참고하세요.
//        print_r($result);
//        if (@$result[result_code] == "1") { // 전송성공
//            echo "결과코드 : " . @$result[result_code];
//            echo "메세지 : " . @iconv('euc-kr', 'utf-8', $result[result_msg]);
//            echo "총 접수건수 : " . @$result[total_count];
//            echo "성공건수 : " . @$result[succ_count];
//            echo "실패건수 : " . @$result[fail_count];
//            echo "잔액 : " . @$result[money];
//        } else {
//            echo "결과코드 : " . @$result[result_code];
//            echo "메세지 : " . @$result[result_msg];
//        }
            //본인 인증 이미 인증요청 체크
            $dupleAccessChk = "SELECT
                                    PHONE_NUMBER 
                               FROM 
                                    AUTHENTICATION_TMP 
                               WHERE 
                               PHONE_NUMBER = '" . $this->input->post('phone', true) . "'";

            $dupleAccessRes = $this->Db_m->getInfo($dupleAccessChk, 'PLAYBAT');

            if (@$result[result_code] == "1") { // 전송성공
                //본인 인증 완료전에 다시 문자 전송을 눌렀다면 기존 키를 update해줌.
                if ($dupleAccessRes) {
                    $updateArray = array(
                        'ACCESS_NUMBER' => $accessKey
                    );

                    $updateWhere = array(
                        'PHONE_NUMBER' => $this->input->post('phone', true)
                    );

                    $this->Db_m->update('AUTHENTICATION_TMP', $updateArray, $updateWhere, 'PLAYBAT');
                } else {//처음 전송을 했을시는 insert
                    $insArray = array(
                        'PHONE_NUMBER' => $this->input->post('phone', true),
                        'ACCESS_NUMBER' => $accessKey
                    );

                    $this->Db_m->insData('AUTHENTICATION_TMP', $insArray, 'PLAYBAT');
                }

                echo "SUCCESS";
            } else {
                echo "FAILED";
            }
        }
    }

    function smsRes($url) {
        $result = file_get_contents($url);
        $result = trim($result);
        parse_str($result, $result_var);

        return $result_var;
    }

    function smsAccessChk() {
        $sql = "SELECT
                    PHONE_NUMBER, 
                    ACCESS_NUMBER 
                FROM 
                    AUTHENTICATION_TMP 
                WHERE 
                    PHONE_NUMBER = '" . $this->input->post('phone', true) . "' AND 
                    ACCESS_NUMBER = '" . $this->input->post('accessKey', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res) {

            $delWhere = array(
                'PHONE_NUMBER' => $this->input->post('phone', true)
            );

            $this->Db_m->delete('AUTHENTICATION_TMP', $delWhere, 'PLAYBAT');

            $updateArray = array(
                'PHONE' => $this->input->post('phone', true)
            );

            $updateWhere = array(
                'MEMBER_IDX' => $this->input->post('idx')
            );

            $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

            echo "SUCCESS";
        } else {

            echo "FAILED";
        }
    }

    function modMember() {

        if (!$this->input->post('dong', true)) {
            $dong = NULL;
        } else {
            $dong = $this->input->post('dong', true);
        }

        $sql = "SELECT
                    PROFILE_IMG 
                FROM 
                    MEMBER 
                WHERE 
                MEMBER_IDX = '" . $this->input->post('idx', true) . "'";

        $img_res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if (@$_FILES['profile_img']['name']) {

            $this->load->library('upload');

            $url_path = "/member_img";
            $upload_config = Array(
                'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                'encrypt_name' => TRUE,
                'max_size' => '512000'
            );
            $this->upload->initialize($upload_config);
            $upfile = $_FILES['profile_img']['name'];
            if (!$this->upload->do_upload('profile_img')) {
                echo $this->upload->display_errors();
            }
            $info = $this->upload->data();
            $file['memberImg'] = $url_path . "/" . $info['file_name'];
        } else {
            $file['memberImg'] = $img_res->PROFILE_IMG;
        }

        $updateArray = array(
            'NAME' => $this->input->post('name', true),
//            'PHONE' => $this->input->post('phone', true),
            'ADDRESS_ORG_IDX' => $dong,
            'PROFILE_IMG' => $file['memberImg'],
            'BUSINESS_NAME' => $this->input->post('business_name', true),
            'BUSINESS_NUMBER' => $this->input->post('business_number', true),
            'ADDR' => $this->input->post('addr', true),
            'DETAIL_ADDR' => $this->input->post('detail_addr', true),
            'BUSINESS_PHONE' => $this->input->post('business_phone', true),
            'HOME_PAGE' => $this->input->post('home_page', true)
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insBoard() {

        if ($this->input->post('category_idx2', true)) {
            $category = $this->input->post('category_idx2', true);
        } else {
            $category = $this->input->post('category_idx', true);
        }

        if (@$_FILES['video']['name']) {

            $this->load->library('upload');

            $url_path = "/boardUpFile/video";
            $upload_config = Array(
                'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                'allowed_types' => 'mp4|avi|wmv|mpg|mpeg|mov|MOV',
                'encrypt_name' => TRUE,
                'max_size' => '102400'
            );
            $this->upload->initialize($upload_config);
            $upfile = $_FILES['video']['name'];
            if (!$this->upload->do_upload('video')) {
                echo $this->upload->display_errors();
            }
            $info = $this->upload->data();
            $file['video'] = $url_path . "/" . $info['file_name'];
        } else {
            $file['video'] = '';
        }

        $insArray = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true),
            'TIME_LINE' => $this->input->post('time_line', true),
            'TITLE' => $this->input->post('title', true),
            'ITEM_CNT' => $this->input->post('item_cnt', true),
            'ADDRESS_ORG_IDX' => $this->input->post('dong', true),
            'EFFECTIVE_TIME' => $this->input->post('effective_time', true),
            'CUPON_TIME' => $this->input->post('cupon_time', true),
            'CONTENTS' => $this->input->post('contents', true),
            'LINK_URL' => $this->input->post('link_url', true),
            'CATEGORY_IDX' => $category,
            'HASH_TAG' => $this->input->post('hash_tag', true),
            'COMP_YN' => 'N',
            'VIDEO' => $file['video']
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->insData('BOARD', $insArray, 'PLAYBAT');
        $ins_id = $this->PLAYBAT->insert_id();

        $insHistoryArray = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true),
            'BOARD_IDX' => $ins_id,
            'CONTENTS' => $this->input->post('title', true) . ' 내기를 등록하셨습니다.',
            'TYPE' => 'WB'
        );

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        for ($i = 0; $i < $this->input->post('item_cnt', true); $i++) {
            $insItemArray[] = array(
                'BOARD_IDX' => $ins_id,
                'NAME' => $this->input->post('item_name', true)[$i]
            );
        }
        $this->Db_m->insMultiData('ITEM', $insItemArray, 'PLAYBAT');



        if (@$_FILES['file']) {

            $this->load->library('upload');

            $uploaded_files = $_FILES;
            $url_path = "/boardUpFile";
            $count = count($_FILES['file']['name']);
            for ($i = 0; $i < $count; $i++) {
//                echo "$i" . "<br>";
                if ($uploaded_files['file']['name'][$i] == null)
                    continue;
                unset($_FILES);
                $_FILES['file']['name'] = $uploaded_files['file']['name'][$i];
                $_FILES['file']['type'] = $uploaded_files['file']['type'][$i];
                $_FILES['file']['tmp_name'] = $uploaded_files['file']['tmp_name'][$i];
                $_FILES['file']['error'] = $uploaded_files['file']['error'][$i];
                $_FILES['file']['size'] = $uploaded_files['file']['size'][$i];

                $upload_config1 = Array(
                    'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                    'allowed_types' => 'gif|jpg|jpeg|png|bmp|PNG',
                    'encrypt_name' => TRUE,
                    'max_size' => '512000'
                );
                $this->upload->initialize($upload_config1);
                if (!$this->upload->do_upload('file')) {
                    echo $this->upload->display_errors();
                }
                $info = $this->upload->data();

                $stamp_file['file'] = $url_path . "/" . $info['file_name'];
                $stamp_file['origin_name'] = $info['orig_name'];

                $ins_file_array[] = array(
                    'BOARD_IDX' => $ins_id,
                    'ORG_NAME' => $stamp_file['origin_name'],
                    'LOCATION' => $stamp_file['file']
                );
            }
            $this->Db_m->insMultiData('BOARD_FILE', $ins_file_array, 'PLAYBAT');
        }

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/write');
        } else {
            $sql = "SELECT
                    PHONE 
                FROM 
                    MEMBER
                WHERE 
                    MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";
            $data['info'] = $this->Db_m->getInfo($sql, 'PLAYBAT');

            $time_sql = "SELECT
                        EFFECTIVE_TIME 
                     FROM 
                        BOARD 
                     WHERE 
                        BOARD_IDX = $ins_id";

            $data['time_res'] = $this->Db_m->getInfo($time_sql, 'PLAYBAT');

            $this->load->view('sms_send', $data);

//            $this->bookingSendSms($this->input->post('member_idx', true), $ins_id);
//            alert('등록 되었습니다.', '/');
        }
    }

    function modBoard() {

        $sql = "SELECT
                    COUNT(*) CNT 
                FROM 
                    ITEM_INFO II 
                WHERE 
                    II.BOARD_IDX = '" . $this->input->post('board_idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($this->input->post('category_idx2', true)) {
            $category = $this->input->post('category_idx2', true);
        } else {
            $category = $this->input->post('category_idx', true);
        }

        if (@$_FILES['video']['name']) {

            $this->load->library('upload');

            $url_path = "/boardUpFile/video";
            $upload_config = Array(
                'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                'allowed_types' => 'mp4|avi|wmv|mpg|mpeg',
                'encrypt_name' => TRUE,
                'max_size' => '102400'
            );
            $this->upload->initialize($upload_config);
            $upfile = $_FILES['video']['name'];
            if (!$this->upload->do_upload('video')) {
                echo $this->upload->display_errors();
            }
            $info = $this->upload->data();
            $file['video'] = $url_path . "/" . $info['file_name'];
        } else {
            $file['video'] = '';
        }

        if ($this->input->post('video_location', true)) {
            $file['video'] = $this->input->post('video_location', true);
        }

        $updateArray = array(
            'TIME_LINE' => $this->input->post('time_line', true),
            'TITLE' => $this->input->post('title', true),
            'ADDRESS_ORG_IDX' => $this->input->post('dong', true),
            'EFFECTIVE_TIME' => $this->input->post('effective_time', true),
            'CUPON_TIME' => $this->input->post('cupon_time', true),
            'CONTENTS' => $this->input->post('contents', true),
            'LINK_URL' => $this->input->post('link_url', true),
            'HASH_TAG' => $this->input->post('hash_tag', true),
            'CATEGORY_IDX' => $category,
            'VIDEO' => $file['video']
        );

        $updateWhere = array(
            'BOARD_IDX' => $this->input->post('board_idx', true)
        );

        $insHistoryArray = array(
            'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX'),
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'CONTENTS' => $this->input->post('title', true) . ' 내기가 수정되었습니다.',
            'TYPE' => 'MB'
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        if ($res->CNT == 0) {

            $this->Db_m->delete('ITEM', $updateWhere, 'PLAYBAT');

            for ($i = 0; $i < $this->input->post('item_cnt', true); $i++) {
                $insItemArray[] = array(
                    'BOARD_IDX' => $this->input->post('board_idx', true),
                    'NAME' => $this->input->post('item_name', true)[$i]
                );
            }

            $this->Db_m->insMultiData('ITEM', $insItemArray, 'PLAYBAT');
        }

        $this->Db_m->update('BOARD', $updateArray, $updateWhere, 'PLAYBAT');

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        $this->Db_m->delete('BOARD_FILE', $updateWhere, 'PLAYBAT');

        if ($this->input->post('location', true)) {
            for ($i = 0; $i < count($this->input->post('location', true)); $i++) {
                $ins_file_array1[] = array(
                    'BOARD_IDX' => $this->input->post('board_idx', true),
                    'LOCATION' => $this->input->post('location')[$i],
                    'ORG_NAME' => $this->input->post('org_name')[$i]
                );
            }
            $this->Db_m->insMultiData('BOARD_FILE', $ins_file_array1, 'PLAYBAT');
        }

        if (@$_FILES['file']) {
            $this->load->library('upload');
            $uploaded_files = $_FILES;
            $url_path = "/boardUpFile";
            $count = count($_FILES['file']['name']);

            for ($i = 0; $i < $count; $i++) {
                if ($uploaded_files['file']['name'][$i] == null)
                    continue;
//                unset($_FILES);

                $_FILES['file']['name'] = $uploaded_files['file']['name'][$i];
                $_FILES['file']['type'] = $uploaded_files['file']['type'][$i];
                $_FILES['file']['tmp_name'] = $uploaded_files['file']['tmp_name'][$i];
                $_FILES['file']['error'] = $uploaded_files['file']['error'][$i];
                $_FILES['file']['size'] = $uploaded_files['file']['size'][$i];

                $upload_config = Array(
                    'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                    'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                    'encrypt_name' => TRUE,
                    'max_size' => '512000'
                );

                $this->upload->initialize($upload_config);

                if (!$this->upload->do_upload('file')) {
                    echo $this->upload->display_errors();
                }

                $info = $this->upload->data();

                $stamp_file['file'] = $url_path . "/" . $info['file_name'];
                $stamp_file['origin_name'] = $info['orig_name'];

                $ins_file_array[] = array(
                    'BOARD_IDX' => $this->input->post('board_idx', true),
                    'LOCATION' => $stamp_file['file'],
                    'ORG_NAME' => $stamp_file['origin_name']
                );
            }

            $this->Db_m->insMultiData('BOARD_FILE', $ins_file_array, 'PLAYBAT');
        }

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/modify/' . $this->input->post('board_idx', true) . '');
        } else {
            alert('내기가 수정 되었습니다.', '/index/view/' . $this->input->post('board_idx', true) . '');
        }
    }

    function delBoard() {

        $updateArray = array(
            'DEL_YN' => 'Y'
        );

        $updateWhere = array(
            'BOARD_IDX' => $this->input->post('board_idx', true)
        );

        $sql = "SELECT
                    TITLE 
                FROM 
                    BOARD 
                WHERE 
                BOARD_IDX = '" . $this->input->post('board_idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        $insHistoryArray = array(
            'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX'),
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'CONTENTS' => $res->TITLE . ' 내기가 삭제되었습니다.',
            'TYPE' => 'DB'
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('BOARD', $updateArray, $updateWhere, 'PLAYBAT');

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insBetting() {
        $insArray = array(
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'ITEM_IDX' => $this->input->post('item_idx', true),
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $insHistoryArray = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true),
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'CONTENTS' => '<strong>' . $this->input->post('item_name', true) . '</strong> 에 베팅 하셨습니다.',
            'TYPE' => 'BC'
        );

        $betting_cnt_sql = "SELECT
                                BETTING_CNT 
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";

        $betting_cnt_res = $this->Db_m->getInfo($betting_cnt_sql, 'PLAYBAT');

        $updateArray = array(
            'BETTING_CNT' => $betting_cnt_res->BETTING_CNT + 1
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->insData('ITEM_INFO', $insArray, 'PLAYBAT');

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function delBetting() {
        $delWhere = array(
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'ITEM_IDX' => $this->input->post('item_idx', true),
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $insHistoryArray = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true),
            'BOARD_IDX' => $this->input->post('board_idx', true),
            'CONTENTS' => '<strong>' . $this->input->post('item_name', true) . '</strong> 에 베팅을 취소하셨습니다.',
            'TYPE' => 'BD'
        );

        $betting_cnt_sql = "SELECT
                                BETTING_CNT 
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";

        $betting_cnt_res = $this->Db_m->getInfo($betting_cnt_sql, 'PLAYBAT');

        $updateArray = array(
            'BETTING_CNT' => $betting_cnt_res->BETTING_CNT - 1
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->delete('ITEM_INFO', $delWhere, 'PLAYBAT');

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        if ($betting_cnt_res->BETTING_CNT > 0) {
            $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');
        }

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function shareUpdate() {
        $updateArray = array(
            'BETTING_CNT' => 0
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function chkBetting() {
        $sql = "SELECT
                    COUNT(*) CNT 
                FROM 
                    ITEM_INFO 
                WHERE 
                    BOARD_IDX = '" . $this->input->post('board_idx', true) . "' AND
                    MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        $sql2 = "SELECT
                    COUNT(*) CNT 
                FROM 
                    ITEM_INFO 
                WHERE 
                    BOARD_IDX = '" . $this->input->post('board_idx', true) . "' AND
                    ITEM_IDX = '" . $this->input->post('item_idx', true) . "' AND
                    MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";

        $res2 = $this->Db_m->getInfo($sql2, 'PLAYBAT');

        $betting_cnt_sql = "SELECT
                                BETTING_CNT 
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->input->post('member_idx', true) . "'";

        $betting_cnt_res = $this->Db_m->getInfo($betting_cnt_sql, 'PLAYBAT');

        if ($betting_cnt_res->BETTING_CNT >= 3) {
            echo 'BETTING_DIS';
            exit;
        }

        if ($res2->CNT > 0) {
            echo 'CHK_DUPLE';
            exit;
        }

        if ($res->CNT == 0) {
            echo 'SUCCESS';
            exit;
        } else if ($res->CNT > 0) {
            echo 'DUPLE';
            exit;
        }
    }

    function insComment() {

        if (!$this->input->post('comment_idx')) {
            $insArray = array(
                'MEMBER_IDX' => $this->input->post('member_idx', true),
                'BOARD_IDX' => $this->input->post('board_idx', true),
                'CONTENTS' => $this->input->post('contents', true)
            );
        } else if ($this->input->post('comment_idx')) {
            $insArray = array(
                'MEMBER_IDX' => $this->input->post('member_idx', true),
                'BOARD_IDX' => $this->input->post('board_idx', true),
                'CONTENTS' => $this->input->post('contents', true),
                'PNUM' => $this->input->post('comment_idx')
            );
        }

//        $insHistoryArray = array(
//            'MEMBER_IDX' => $this->input->post('member_idx', true),
//            'BOARD_IDX' => $this->input->post('board_idx', true),
//            'CONTENTS' => $this->input->post('title', true) . '에, 댓글을 작성하셨습니다.',
//            'TYPE' => 'CI'
//        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->insData('COMMENT', $insArray, 'PLAYBAT');

//        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/view/' . $this->input->post('board_idx', true) . '/comment');
        } else {
            alert('등록되었습니다.', '/index/view/' . $this->input->post('board_idx', true) . '/comment');
        }
    }

    function delComment() {
        $delWhere = array(
            'COMMENT_IDX' => $this->input->post('idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->delete('COMMENT', $delWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function modComment() {

        $updateArray = array(
            'CONTENTS' => $this->input->post('contents', true)
        );

        $updateWhere = array(
            'COMMENT_IDX' => $this->input->post('comment_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('COMMENT', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function result_page() {
        $sql = "SELECT
                        II.ITEM_INFO_IDX, 
                        M.PROFILE_IMG, 
                        M.NAME 
                    FROM 
                        ITEM_INFO II, MEMBER M 
                    WHERE 
                        II.MEMBER_IDX = M.MEMBER_IDX AND
                        II.CUPON_RECEPTION = 'N' AND
                        II.BOARD_IDX = '" . $this->input->post('board_idx', true) . "' AND
                        II.ITEM_IDX = '" . $this->input->post('item_idx', true) . "'";

        $data['lists'] = $this->Db_m->getList($sql, 'PLAYBAT');

        $this->load->view('result_page', $data);
    }

    function chkResult() {
        $sql = "SELECT
                    I.ITEM_IDX, 
                    I.NAME,
                    (
                        SELECT 
                            COUNT(*) CNT 
                        FROM 
                            ITEM_INFO II 
                        WHERE 
                            II.ITEM_IDX = I.ITEM_IDX
                    ) CNT,
                    (
                        SELECT 
                            COUNT(*) CNT 
                        FROM 
                            ITEM_INFO II 
                        WHERE 
                            II.BOARD_IDX = I.BOARD_IDX
                    ) TOT_CNT
                FROM 
                    ITEM I
                WHERE 
                I.BOARD_IDX = '" . $this->input->post('board_idx', true) . "'";

        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        $i = 0;
        $data = '';
        if (count($res) > 1) {
            foreach ($res as $row) {
                if ($i == 0) {
                    $class = "yellow_btn";
                } else if ($i == 1) {
                    $class = "darkblue_btn";
                } else {
                    $class = "red_btn";
                }
                $data .= '<button id="' . $row['ITEM_IDX'] . '" class="color_btn red_btn ' . $class . '" value="item_' . $row['ITEM_IDX'] . '"><h5><strong>' . $row['NAME'] . ' ' . number_format($row['CNT']) . '</strong></h5></button>';
                $i++;
            }
            // $data .= '<button id="all" class="color_btn black_btn2" value="all_' . $this->input->post('board_idx', true) . '"><h5><strong>전체 ' . number_format($row['TOT_CNT']) . '</strong></h5></button>';
        } else if (count($res) == 1) {
            foreach ($res as $row) {
                if ($i == 0) {
                    $class = "yellow_btn";
                } else {
                    $class = "darkblue_btn";
                }
                $data .= '<button id="' . $row['ITEM_IDX'] . '" class="color_btn ' . $class . '" value="item_' . $row['ITEM_IDX'] . '"><h5><strong>' . $row['NAME'] . ' ' . number_format($row['CNT']) . '</strong></h5></button>';
                $i++;
            }
        }

        print_r($data);
    }

    function chkSendCupon() {
        $sql = "SELECT
                    COUNT(*) CNT 
                FROM 
                    ITEM_INFO 
                WHERE 
                    BOARD_IDX = '" . $this->input->post('board_idx', true) . "' AND
                    CUPON_RECEPTION = 'Y'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res->CNT > 0) {
            echo 'DUPLE';
            exit;
        } else if ($res->CNT == 0) {
            echo 'SUCCESS';
            exit;
        }
    }

    function sendCupon() {

        $updateArray = array(
            'CUPON_RECEPTION' => 'Y',
            'CUPON_SEND_TIME' => date("Y-m-d H:i:s", time())
        );

        if ($this->input->post('type', true) === 'all') {
            $updateWhere = array(
                'BOARD_IDX' => $this->input->post('idx', true)
            );

            $sql = "SELECT
                        M.MEMBER_IDX,
                        M.EMAIL,
                        M.NAME
                    FROM 
                        ITEM_INFO II, MEMBER M 
                    WHERE
                        II.MEMBER_IDX = M.MEMBER_IDX AND
                        II.BOARD_IDX = '" . $this->input->post('idx', true) . "'";

            $res = $this->Db_m->getList($sql, 'PLAYBAT');

            $title_sql = "SELECT
                            B.BOARD_IDX, 
                            B.TITLE 
                          FROM 
                            BOARD B 
                          WHERE 
                            B.BOARD_IDX = '" . $this->input->post('idx', true) . "'";

            $title_res = $this->Db_m->getInfo($title_sql, 'PLAYBAT');
        }

        if ($this->input->post('type', true) === 'item') {
            $updateWhere = array(
                'ITEM_IDX' => $this->input->post('idx', true)
            );

            //당첨자
            $sql = "SELECT
                        M.MEMBER_IDX,
                        M.EMAIL,
                        M.NAME,
                        II.BOARD_IDX
                    FROM 
                        ITEM_INFO II, MEMBER M 
                    WHERE
                        II.MEMBER_IDX = M.MEMBER_IDX AND
                        II.ITEM_IDX = '" . $this->input->post('idx', true) . "'";

            $res = $this->Db_m->getList($sql, 'PLAYBAT');

            //미당첨자
            $sql2 = "SELECT
                        M.MEMBER_IDX,
                        M.EMAIL,
                        M.NAME,
                        II.BOARD_IDX
                    FROM 
                        ITEM_INFO II, MEMBER M, BOARD B
                    WHERE
                        II.BOARD_IDX = B.BOARD_IDX AND
                        II.MEMBER_IDX = M.MEMBER_IDX AND
                        B.BOARD_IDX = '" . $this->input->post('board_idx') . "' AND
                        II.ITEM_IDX <> '" . $this->input->post('idx', true) . "'";

            $res2 = $this->Db_m->getList($sql2, 'PLAYBAT');

            if ($res) {
                $title_sql = "SELECT
                            B.BOARD_IDX, 
                            B.TITLE 
                          FROM 
                            BOARD B 
                          WHERE 
                            B.BOARD_IDX = '" . $res[0]['BOARD_IDX'] . "'";

                $title_res = $this->Db_m->getInfo($title_sql, 'PLAYBAT');
            }
        }

        $updateArray2 = array(
            'COMP_YN' => 'Y'
        );

        $updateWhere2 = array(
            'BOARD_IDX' => $this->input->post('board_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        if ($res) {
            $this->load->library('email'); //이메일 라이브러리 로딩

            foreach ($res as $row) {

                $data['NAME'] = $row['NAME'];
                $data['TITLE'] = $title_res->TITLE;
                $data['TYPE'] = 'SUCCESS';

                $mail_text = $this->load->view('mailing', $data, TRUE);

                $config = array();
                $config['mailtype'] = 'html';
                $config['protocol'] = 'smtp';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['smtp_host'] = 'ssl://smtp.naver.com';
                $config['smtp_user'] = 'kiss_699';
                $config['smtp_pass'] = 'Rla910914';
                $config['smtp_port'] = 465;
                $config['smtp_timeout'] = 10;
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
                $this->email->from('kiss_699@naver.com', '플레이벳컴'); //보내는사람 이메일 세팅
                $this->email->to($row['EMAIL']); //받는사람 이메일주소 세팅
                $this->email->subject('플레이벳컴 알림'); //이메일 제목 설정
                $this->email->message($mail_text); //메일 내용 작성
                $this->email->set_alt_message('옵션을 획득하셨습니다.'); //html 메일 못받는 사용자 내용 작성

                if (!$this->email->send()) {
//                echo $this->email->print_debugger() . "전송실패.";
                    echo "FAILED";
                    exit;
                } else {
                    $this->email->clear(TRUE); //메일 전송 초기화 (TRUE)<- 설정시 첨부파일까지 초기화
                }
            }

            //쿠폰수령 히스토리
            foreach ($res as $row) {
                $insHistoryArray[] = array(
                    'MEMBER_IDX' => $row['MEMBER_IDX'],
                    'BOARD_IDX' => $title_res->BOARD_IDX,
                    'CONTENTS' => $title_res->TITLE . ' 베팅에 성공하셨습니다. 옵션을 확인해주세요',
                    'TYPE' => 'CR'
                );
            }

            $this->Db_m->insMultiData('HISTORY', $insHistoryArray, 'PLAYBAT');

            //쿠폰발송 히스토리
            $sendCuponArray = array(
                'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX'),
                'BOARD_IDX' => $title_res->BOARD_IDX,
                'CONTENTS' => $title_res->TITLE . ' 옵션을 ' . number_format(count($res)) . '명에게 전달하였습니다.',
                'TYPE' => 'SC'
            );

            $this->Db_m->insData('HISTORY', $sendCuponArray, 'PLAYBAT');
        }

        if ($res2) {
            $this->load->library('email'); //이메일 라이브러리 로딩

            foreach ($res2 as $row) {

                $data['NAME'] = $row['NAME'];
                $data['TITLE'] = $title_res->TITLE;
                $data['TYPE'] = 'FAILED';

                $mail_text = $this->load->view('mailing', $data, TRUE);

                $config = array();
                $config['mailtype'] = 'html';
                $config['protocol'] = 'smtp';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['smtp_host'] = 'ssl://smtp.naver.com';
                $config['smtp_user'] = 'kiss_699';
                $config['smtp_pass'] = 'Rla910914';
                $config['smtp_port'] = 465;
                $config['smtp_timeout'] = 10;
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
                $this->email->from('kiss_699@naver.com', '플레이벳컴'); //보내는사람 이메일 세팅
                $this->email->to($row['EMAIL']); //받는사람 이메일주소 세팅
                $this->email->subject('플레이벳컴 알림'); //이메일 제목 설정
                $this->email->message($mail_text); //메일 내용 작성
                $this->email->set_alt_message('옵션 획득에 실패하셨습니다.'); //html 메일 못받는 사용자 내용 작성

                if (!$this->email->send()) {
//                echo $this->email->print_debugger() . "전송실패.";
                    echo "FAILED";
                    exit;
                } else {
                    $this->email->clear(TRUE); //메일 전송 초기화 (TRUE)<- 설정시 첨부파일까지 초기화
                }
            }

            //쿠폰수령 히스토리
            foreach ($res2 as $row) {
                $insHistoryArray2[] = array(
                    'MEMBER_IDX' => $row['MEMBER_IDX'],
                    'BOARD_IDX' => $title_res->BOARD_IDX,
                    'CONTENTS' => $title_res->TITLE . ' 베팅에 실패하셨습니다.',
                    'TYPE' => 'BF'
                );
            }

            $this->Db_m->insMultiData('HISTORY', $insHistoryArray2, 'PLAYBAT');
        }

        $this->Db_m->update('ITEM_INFO', $updateArray, $updateWhere, 'PLAYBAT');
        $this->Db_m->update('BOARD', $updateArray2, $updateWhere2, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function keywordkAutoComplete() {
        $sql = "SELECT
                  SIDO
                FROM
                  ADDRESS_ORG 
                WHERE 
                  MATCH(SIDO, SIGUNGU, DONG) AGAINST('" . urldecode($this->input->get('query', true)) . "' IN BOOLEAN MODE)
                  GROUP BY SIDO";
        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        $sql2 = "SELECT
                  SIGUNGU
                FROM
                  ADDRESS_ORG 
                WHERE 
                  MATCH(SIDO, SIGUNGU, DONG) AGAINST('" . urldecode($this->input->get('query', true)) . "' IN BOOLEAN MODE)
                  GROUP BY SIGUNGU";
        $res2 = $this->Db_m->getList($sql2, 'PLAYBAT');

        $sql3 = "SELECT
                  DONG
                FROM
                  ADDRESS_ORG 
                WHERE 
                  MATCH(SIDO, SIGUNGU, DONG) AGAINST('" . urldecode($this->input->get('query', true)) . "' IN BOOLEAN MODE)
                  GROUP BY DONG";
        $res3 = $this->Db_m->getList($sql3, 'PLAYBAT');

        $result = array();
        foreach ($res as $row) {
            $result[] = array(
                'name' => $row['SIDO']
            );
        }

        foreach ($res2 as $row) {
            $result[] = array(
                'name' => $row['SIGUNGU']
            );
        }

        foreach ($res3 as $row) {
            $result[] = array(
                'name' => $row['DONG']
            );
        }

        echo json_encode($result);
    }

    function useCupon() {
        $updateArray = array(
            'CUPON_USE_YN' => 'Y'
        );

        $updateWhere = array(
            'ITEM_INFO_IDX' => $this->input->post('item_info_idx', true)
        );

        $sql = "SELECT
                    B.BOARD_IDX,
                    B.TITLE,
                    M.MEMBER_IDX 
                FROM 
                    ITEM_INFO II, MEMBER M, BOARD B
                WHERE 
                    II.MEMBER_IDX = M.MEMBER_IDX AND 
                    II.BOARD_IDX = B.BOARD_IDX AND
                    II.ITEM_INFO_IDX = '" . $this->input->post('item_info_idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        $insHistoryArray = array(
            'MEMBER_IDX' => $res->MEMBER_IDX,
            'BOARD_IDX' => $res->BOARD_IDX,
            'CONTENTS' => $res->TITLE . ' 옵션을 사용하셨습니다.',
            'TYPE' => 'CU'
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('ITEM_INFO', $updateArray, $updateWhere, 'PLAYBAT');

        $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function twitterLogin() {
        $this->load->library('lib/twitterOauth');
        $consumer_key = "KiXOdWC7PUywvzQyS6t2fn1YB";
        $consumer_secret = "693XBKgZDJoqGHWm3V2ogoo4I9N1ooexZJDfGZF5NsD8PbHCn6";

        // TwitterOAuth object 생성
        $connection = new TwitterOAuth($consumer_key, $consumer_secret);

        // request token 발급
        $request_token = $connection->getRequestToken();

        // request token은 사용자 인증이 보내질 페이지다. 아래와 같은 방식으로 기술하여서도 해결이 가능하다.
        // $domain = "http://" . $_SERVER['HTTP_HOST'] . "/";
        // $request_token = $connection->getRequestToken($domain . "wicked_home/twitter_access_token.php");
        // 결과 확인
        switch ($connection->http_code) {

            case 200 :

                // 성공, token 저장
                $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

                // 인증 url 확인
                $url = $connection->getAuthorizeURL($token);

                // 인증 url (로그인 url) 로 redirect
                header("Location: " . $url);

                break;

            default:

                echo "Could not connect to Twitter. Refresh the page or try again later.";

                break;
        }
    }

    function twitterAccessToken() {

        $this->load->library('lib/twitterOauth');
        $consumer_key = "KiXOdWC7PUywvzQyS6t2fn1YB";
        $consumer_secret = "693XBKgZDJoqGHWm3V2ogoo4I9N1ooexZJDfGZF5NsD8PbHCn6";

        // Request token 을 포함한 TwitterOAuth object 생성
        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        // 토큰 수령
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
        $token = $access_token['oauth_token'];
        $token_secret = $access_token['oauth_token_secret'];

        $params = array('include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true');

        $data = $connection->get("account/verify_credentials", $params);

        if (!$data->email) {
            alert("트위터 계정에서 email을 확인해 주세요.", '/index/login');
        } else {
            $sql = "SELECT
                        MEMBER_IDX, 
                        NAME
                    FROM 
                        MEMBER 
                    WHERE 
                    EMAIL = '" . $data->email . "'";

            $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

            if ($res) {
                //세션 생성
                $newdata = array(
                    'MEMBER_IDX' => $res->MEMBER_IDX,
                    'NAME' => $res->NAME
                );
                $this->session->set_userdata($newdata);
                alert("로그인 되었습니다.", '/');
            } else {
                $insArray = array(
                    'JOIN_ROOT' => 'TWITTER',
                    'NAME' => $data->name,
                    'EMAIL' => $data->email,
                    'PROFILE_IMG' => str_replace('\\/', '/', json_encode($data->profile_image_url)),
                    'PWD' => $data->id,
                    'TYPE' => 'N'
                );

                $this->PLAYBAT->trans_start(); // Query will be rolled back

                $this->Db_m->insData('MEMBER', $insArray, 'PLAYBAT');

                $this->PLAYBAT->trans_complete();

                if ($this->PLAYBAT->trans_status() === FALSE) {
                    alert('데이터 처리오류!!', '/index/login');
                } else {

                    $sql = "SELECT
                                MEMBER_IDX, 
                                NAME
                            FROM 
                                MEMBER 
                            WHERE 
                            EMAIL = '" . $data->email . "'";

                    $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

                    //세션 생성
                    $newdata = array(
                        'MEMBER_IDX' => $res->MEMBER_IDX,
                        'NAME' => $res->NAME
                    );
                    $this->session->set_userdata($newdata);
                    alert("로그인 되었습니다.", '/');
                }
            }
        }

//        $twt_id = $data->id; //twitter user id
//        $twt_email = $data->email; //twitter user email
//        echo str_replace('\\/', '/', json_encode($data->profile_image_url));
    }

    function reply() {
        $this->load->view('reply');
    }

    function mailing() {
        $this->load->view('mailing');
    }

    function histoyRead() {
        $updateArray = array(
            'READ_YN' => 'Y'
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('member_idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('HISTORY', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function wonList() {
        $sql = "SELECT 
                      I.NAME,
                      COUNT(*) CNT 
                    FROM 
                      BOARD B, ITEM_INFO II, ITEM I
                    WHERE 
                      B.BOARD_IDX = I.BOARD_IDX AND
                      II.ITEM_IDX = I.ITEM_IDX AND
                      B.BOARD_IDX = II.BOARD_IDX AND 
                      II.CUPON_RECEPTION = 'Y' AND
                      B.BOARD_IDX = '" . $this->input->post('board_idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        $result = array();
        if ($res) {
            $result = array(
                'RESULT' => 'SUCCESS',
                'NAME' => $res->NAME,
                'CNT' => $res->CNT
            );
        } else {
            $result = array(
                'RESULT' => 'FAILED'
            );
        }

        print_r(json_encode($result));
    }

    function memberTypeChange() {
        $updateArray = array(
            'TYPE' => $this->input->post('value', true),
            'PREMIUM_MONTH' => null,
            'PREMIUM_DATE' => '0000-00-00 00:00:00'
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insPremium() {

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $sql = "SELECT 
                    TYPE
                FROM 
                    MEMBER
                WHERE 
                    MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res->TYPE === 'Y') {
            alert('이미 프리미엄 이용중입니다.', '/index/premium');
            exit;
        }

        if ($res->TYPE === 'I') {
            alert('프리미엄 신청대기 중입니다.', '/index/premium');
            exit;
        }

        $updateArray = array(
            'TYPE' => 'I',
            'PREMIUM_MONTH' => $this->input->post("date", true),
            'PREMIUM_DATE' => date("Y-m-d")
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX')
        );

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/premium');
        } else {
            alert('신청 되었습니다.', '/index/premium');
        }
    }

    function delPremium() {

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $sql = "SELECT 
                    TYPE
                FROM 
                    MEMBER
                WHERE 
                    MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $res = $this->Db_m->getInfo($sql, 'PLAYBAT');

        if ($res->TYPE === 'Y') {
            alert('이미 프리미엄 이용중입니다.', '/index/premium');
            exit;
        }

        $updateArray = array(
            'TYPE' => 'N'
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX')
        );

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/premium');
        } else {
            alert('프리미엄 신청이 취소되었습니다.', '/index/premium');
        }
    }

    function premiumOk() {

        $kss['date'] = date('Y-m-d');

        $end_date = date("Y-m-d", strtotime($kss['date'] . ' + ' . $this->input->post("month", true) . 'month'));

        $updateArray = array(
            'TYPE' => 'Y',
            'PREMIUM_MONTH' => $this->input->post("month", true),
            'PREMIUM_DATE' => $end_date
        );

        $updateWhere = array(
            'MEMBER_IDX' => $this->input->post('idx', true)
        );

        $this->PLAYBAT->trans_start(); // Query will be rolled back

        $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

        $this->PLAYBAT->trans_complete();

        if ($this->PLAYBAT->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function memExcelDown() {
        ini_set('memory_limit', '-1');

        $sql = "SELECT 
                    M.NAME,
                    M.EMAIL,
                    DATE_FORMAT(II.TIMESTAMP, '%Y%m%d%H%i%S') II_DATE
                    FROM 
                      BOARD B, ITEM_INFO II, ITEM I, MEMBER M 
                    WHERE 
                      B.BOARD_IDX = I.BOARD_IDX AND
                      II.MEMBER_IDX = M.MEMBER_IDX AND
                      II.ITEM_IDX = I.ITEM_IDX AND
                      B.BOARD_IDX = II.BOARD_IDX AND 
                      II.CUPON_RECEPTION = 'Y' AND
                      B.BOARD_IDX = '" . $this->input->get('board_idx', true) . "'";

        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        header("Content-type: application/vnd.ms-excel");
        header("Content-type: application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename = 당첨자확인.xls");
        header("Content-Description: PHP4 Generated Data");

        echo " 
            <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
            <TABLE border='1'>
                <TR>
                    <TD>일련번호</TD>
                    <TD>회원명</TD>
                    <TD>쿠폰번호</TD>
                </TR>";
        $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
        $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
        $i = 1;
        foreach ($res as $row) {
            echo " 
                <TR>
                    <TD>$i</TD>
                    <TD>$row[NAME]</TD>
                    <TD style='$number'>$row[II_DATE]</TD>
                </TR>";
            $i++;
        }
        echo "</TR> 
            </TABLE>";
    }

    function subCategoryLists() {
        $sql = "SELECT
                    CATEGORY_IDX,
                    NAME
                  FROM
                    CATEGORY
                  WHERE
                    USE_YN = 'Y' AND
                    PNUM = '" . $this->input->post('idx') . "'
                    ORDER BY SHOW_LEVEL = 0, SHOW_LEVEL";

        $res = $this->Db_m->getList($sql, 'PLAYBAT');

        if (!$res) {
            echo '<option value="">하위가 없습니다.</option>';
        } else {
            $data = '';
            foreach ($res as $row) {
                if ($row['CATEGORY_IDX'] == $this->input->post('idx2')) {
                    $select = 'selected';
                } else {
                    $select = '';
                }
                $data .= '<option value="' . $row['CATEGORY_IDX'] . '" ' . $select . '>' . $row['NAME'] . '</option>';
            }

            echo $data;
        }
    }

    function compBoard() {
        $updateArray = array(
            'COMP_YN' => 'Y'
        );

        $updateWhere = array(
            'BOARD_IDX' => $this->input->post('board_idx', true)
        );

        $this->Db_m->update('BOARD', $updateArray, $updateWhere, 'PLAYBAT');

        echo 'SUCCESS';
    }

}
