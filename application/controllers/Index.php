<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->PLAYBAT = $this->load->database('PLAYBAT', TRUE);

        $this->load->helper(array('url', 'date', 'form', 'alert'));
        $this->load->model('Db_m');
        $this->load->library('session');
    }

    function _remap($method) {

        $category_sql = "SELECT
                            CATEGORY_IDX,
                            NAME
                         FROM
                            CATEGORY
                         WHERE
                            USE_YN = 'Y' AND
                            PNUM = 0
                            ORDER BY SHOW_LEVEL = 0, SHOW_LEVEL";
        $data['category_lists'] = $this->Db_m->getList($category_sql, 'PLAYBAT');

        if ($this->session->userdata('MEMBER_IDX')) {

            $premium_chk = "SELECT
                                TYPE, 
                                PREMIUM_DATE,
                                NOW() NOW
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

            $premium_chk_res = $this->Db_m->getInfo($premium_chk, 'PLAYBAT');

            if ($premium_chk_res->TYPE === 'Y') {
                $date1 = new DateTime($premium_chk_res->NOW);
                $date2 = new DateTime($premium_chk_res->PREMIUM_DATE);
                $diff = date_diff($date1, $date2);
                if ($diff->invert != 0) {
                    $updateArray = array(
                        'TYPE' => 'N',
                        'PREMIUM_MONTH' => null,
                        'PREMIUM_DATE' => '0000-00-00 00:00:00'
                    );

                    $updateWhere = array(
                        'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX')
                    );

                    $this->PLAYBAT->trans_start(); // Query will be rolled back

                    $this->Db_m->update('MEMBER', $updateArray, $updateWhere, 'PLAYBAT');

                    $this->PLAYBAT->trans_complete();

                    if ($this->PLAYBAT->trans_status() === FALSE) {
                        alert("프리미엄 처리오류!!", '/');
                    } else {
                        alert("프리미엄 사용기간이 종료되었습니다.", '/index/premium');
                    }
                }
            }


            if ($this->uri->segment(2) == 'mypage' && !$this->uri->segment(3)) {
                $updateArray = array(
                    'READ_YN' => 'Y'
                );

                $updateWhere = array(
                    'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX')
                );

                $this->Db_m->update('HISTORY', $updateArray, $updateWhere, 'PLAYBAT');
            }
            $history_new_sql = "SELECT
                                    COUNT(*) CNT 
                                FROM 
                                    HISTORY 
                                WHERE 
                                    MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND
                                    READ_YN = 'N'";

            $data['history_new'] = $this->Db_m->getInfo($history_new_sql, 'PLAYBAT');

            $write_new_sql = "SELECT 
                            B.BOARD_IDX,
                            (
                              SELECT 
                                COUNT(*) 
                              FROM 
                                ITEM_INFO II
                              WHERE
                                B.BOARD_IDX = II.BOARD_IDX AND
                                II.CUPON_RECEPTION = 'Y'
                            ) CNT
                          FROM 
                            BOARD B
                          WHERE 
                            B.MEMBER_IDX = " . $this->session->userdata('MEMBER_IDX') . " AND
                            B.DEL_YN = 'N' AND
                            B.EFFECTIVE_TIME <= NOW() AND 
                            B.COMP_YN = 'N'";

            $data['write_new_lists'] = $this->Db_m->getList($write_new_sql, 'PLAYBAT');

            $cupon_new_sql = "SELECT 
                                COUNT(*) CNT 
                              FROM 
                                BOARD B, ITEM_INFO II
                              WHERE 
                                B.BOARD_IDX = II.BOARD_IDX AND
                                II.MEMBER_IDX = " . $this->session->userdata('MEMBER_IDX') . " AND 
                                B.CUPON_TIME >= NOW() AND 
                                II.CUPON_RECEPTION = 'Y' AND 
                                II.CUPON_USE_YN = 'N'";

            $data['cupon_new'] = $this->Db_m->getInfo($cupon_new_sql, 'PLAYBAT');
        }

        $this->load->view('inc/header', $data);

        if (method_exists($this, $method)) {
            $this->{"{$method}"}();
        }

        $this->load->view('inc/footer');
    }

    function segment_explode($seg) {
        //세크먼트 앞뒤 '/' 제거후 uri를 배열로 반환
        $len = strlen($seg);
        if (substr($seg, 0, 1) == '/') {
            $seg = substr($seg, 1, $len);
        }

        $len = strlen($seg);
        if (substr($seg, -1) == '/') {
            $seg = substr($seg, 0, $len - 1);
        }

        $seg_exp = explode("/", $seg);
        return $seg_exp;
    }

    function url_explode($url, $key) {
        for ($i = 0; count($url) > $i; $i++) {
            if ($url[$i] == $key) {
                $k = $i + 1;
                return $url[$k];
            }
        }
    }

    function index() {
        $this->load->view('index');
    }

    function mypage() {

        if (!$this->session->userdata('MEMBER_IDX')) {
            alert('로그인이 필요합니다.', '/index/login');
            exit;
        }

        $history_new_sql = "SELECT
                                COUNT(*) CNT 
                            FROM 
                                HISTORY 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND
                                READ_YN = 'N'";

        $data['history_new'] = $this->Db_m->getInfo($history_new_sql, 'PLAYBAT');

        $write_new_sql = "SELECT 
                            B.BOARD_IDX,
                            (
                              SELECT 
                                COUNT(*) 
                              FROM 
                                ITEM_INFO II
                              WHERE
                                B.BOARD_IDX = II.BOARD_IDX AND
                                II.CUPON_RECEPTION = 'Y'
                            ) CNT
                          FROM 
                            BOARD B
                          WHERE 
                            B.MEMBER_IDX = " . $this->session->userdata('MEMBER_IDX') . " AND
                            B.DEL_YN = 'N' AND
                            B.EFFECTIVE_TIME <= NOW() AND 
                            B.COMP_YN = 'N'";

        $data['write_new_lists'] = $this->Db_m->getList($write_new_sql, 'PLAYBAT');

        $cupon_new_sql = "SELECT 
                            COUNT(*) CNT 
                          FROM 
                            BOARD B, ITEM_INFO II
                          WHERE 
                            B.BOARD_IDX = II.BOARD_IDX AND
                            II.MEMBER_IDX = " . $this->session->userdata('MEMBER_IDX') . " AND 
                            B.CUPON_TIME >= NOW() AND 
                            II.CUPON_RECEPTION = 'Y' AND 
                            II.CUPON_USE_YN = 'N'";

        $data['cupon_new'] = $this->Db_m->getInfo($cupon_new_sql, 'PLAYBAT');

        $endBoard_sql = "SELECT
                            BOARD_IDX, 
                            TITLE,
                            EFFECTIVE_TIME
                         FROM 
                            BOARD 
                         WHERE 
                            EFFECTIVE_TIME <= NOW() AND 
                            MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $endBoard_res = $this->Db_m->getList($endBoard_sql, 'PLAYBAT');

        foreach ($endBoard_res as $row) {
            $history_chk_sql = "SELECT
                                    COUNT(*) CNT
                                FROM 
                                    HISTORY 
                                WHERE
                                    MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND 
                                    BOARD_IDX = '" . $row['BOARD_IDX'] . "' AND
                                    TYPE = 'EB'";

            $history_chk_res = $this->Db_m->getInfo($history_chk_sql, 'PLAYBAT');

            if ($history_chk_res->CNT == 0) {
                $insHistoryArray = array(
                    'MEMBER_IDX' => $this->session->userdata('MEMBER_IDX'),
                    'BOARD_IDX' => $row['BOARD_IDX'],
                    'CONTENTS' => $row['TITLE'] . ' 내기가 종료되었습니다. 옵션을 확인하세요',
                    'TYPE' => 'EB',
                    'TIMESTAMP' => $row['EFFECTIVE_TIME']
                );

                $this->Db_m->insData('HISTORY', $insHistoryArray, 'PLAYBAT');
            }
        }

        $history_sql = "SELECT
                            M.BUSINESS_NAME,
                            CONCAT(AO.SIDO, ' ', AO.SIGUNGU) LOCATION,
                            B.TITLE,
                            H.CONTENTS,
                            H.TYPE,
                            DATE_FORMAT(H.TIMESTAMP, '%Y-%m-%d') H_DATE
                        FROM 
                            MEMBER M, BOARD B, ADDRESS_ORG AO, HISTORY H 
                        WHERE 
                            M.MEMBER_IDX = H.MEMBER_IDX AND 
                            B.BOARD_IDX = H.BOARD_IDX AND
                            B.ADDRESS_ORG_IDX = AO.ADDRESS_ORG_IDX AND
                            H.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'
                            ORDER BY H.TIMESTAMP DESC";

        $data['historyLists'] = $this->Db_m->getList($history_sql, 'PLAYBAT');

        $myboard_sql = "SELECT
                            B.BOARD_IDX,
                            IF(DATE_FORMAT(B.TIMESTAMP, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d'), 'Y', 'N') TODAY,
                            B.EFFECTIVE_TIME,
                            DATE_FORMAT(B.TIMESTAMP, '%Y-%m-%d') INS_DATE,
                            B.TIME_LINE,
                            NOW() NOW,
                            (
                                SELECT 
                                    I.ITEM_IDX 
                                FROM 
                                    ITEM I 
                                WHERE 
                                    I.BOARD_IDX = B.BOARD_IDX 
                                    LIMIT 0, 1
                            ) ITEM_IDX,
                            (
                              SELECT
                                COUNT(*) CNT 
                              FROM 
                                ITEM_INFO II 
                              WHERE 
                                II.BOARD_IDX = B.BOARD_IDX
                            ) ITEM_INFO_CNT,
                            B.CUPON_TIME,
                            B.COMP_YN
                        FROM 
                            BOARD B, MEMBER M, ADDRESS_ORG AO, CATEGORY C
                        WHERE
                            B.MEMBER_IDX = M.MEMBER_IDX AND
                            B.ADDRESS_ORG_IDX = AO.ADDRESS_ORG_IDX AND
                            B.CATEGORY_IDX = C.CATEGORY_IDX AND
                            B.DEL_YN = 'N' AND
                            B.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'
                            ORDER BY B.TIMESTAMP DESC";

        $data['myboard_lists'] = $this->Db_m->getList($myboard_sql, 'PLAYBAT');

        $mycupon_sql = "SELECT 
                            II.ITEM_INFO_IDX,
                            (
                              SELECT 
                                M.NAME
                              FROM 
                                BOARD B, MEMBER M
                              WHERE
                                B.MEMBER_IDX = M.MEMBER_IDX AND
                                B.BOARD_IDX = II.BOARD_IDX
                            ) NAME,
                            (
                              SELECT 
                                B.TITLE
                              FROM 
                                BOARD B, MEMBER M
                              WHERE
                                B.MEMBER_IDX = M.MEMBER_IDX AND
                                B.BOARD_IDX = II.BOARD_IDX
                            ) TITLE,
                            (
                              SELECT 
                                B.CUPON_TIME
                              FROM 
                                BOARD B, MEMBER M
                              WHERE
                                B.MEMBER_IDX = M.MEMBER_IDX AND
                                B.BOARD_IDX = II.BOARD_IDX
                            ) CUPON_TIME,
                            NOW() NOW,
                            CUPON_USE_YN,
                            CONCAT('쿠폰번호 : ', DATE_FORMAT(II.TIMESTAMP, '%Y%m%d%H%i%S')) CUPON_MSG
                        FROM
                            ITEM_INFO II, MEMBER M
                        WHERE
                            II.MEMBER_IDX = M.MEMBER_IDX AND
                            II.CUPON_RECEPTION ='Y' AND
                            II.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'
                            ORDER BY II.TIMESTAMP DESC";

        $data['cupon_lists'] = $this->Db_m->getList($mycupon_sql, 'PLAYBAT');

        $member_info_sql = "SELECT
                                M.MEMBER_IDX, 
                                M.NAME, 
                                M.EMAIL,
                                M.PROFILE_IMG,
                                M.PWD,
                                M.ADDRESS_ORG_IDX,
                                AO.SIDO,
                                AO.SIGUNGU,
                                M.PHONE,
                                M.BUSINESS_NAME,
                                M.BUSINESS_NUMBER,
                                M.ADDR,
                                M.DETAIL_ADDR,
                                M.BUSINESS_PHONE,
                                M.HOME_PAGE
                            FROM 
                                MEMBER M LEFT JOIN ADDRESS_ORG AO
                                ON AO.ADDRESS_ORG_IDX = M.ADDRESS_ORG_IDX
                            WHERE 
                                M.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        $si_sql = "SELECT
                        ADDRESS_ORG_IDX, 
                        SIDO 
                    FROM 
                        ADDRESS_ORG 
                        GROUP BY SIDO ORDER BY ADDRESS_ORG_IDX ASC";

        $data['si_lists'] = $this->Db_m->getList($si_sql, 'PLAYBAT');

        $this->load->view('mypage', $data);
    }

    function myinfo() {
        $this->load->view('myinfo');
    }

    function login() {
        $this->load->view('login');
    }

    function mailing() {
        $this->load->view('mailing');
    }

    function write() {
        if (!$this->session->userdata('MEMBER_IDX')) {
            alert('로그인이 필요합니다.', '/index/login');
            exit;
        }

        $member_info_sql = "SELECT
                                MEMBER_IDX, 
                                NAME, 
                                PROFILE_IMG,
                                PHONE,
                                BUSINESS_NAME,
                                BUSINESS_NUMBER,
                                ADDRESS_ORG_IDX,
                                TYPE
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        $board_cnt_sql = "SELECT
                            COUNT(*) CNT
                         FROM 
                            BOARD 
                         WHERE 
                            MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND
                            COMP_YN = 'N' AND
                            DEL_YN = 'N'";

        $board_cnt = $this->Db_m->getInfo($board_cnt_sql, 'PLAYBAT');

        $write_cnt_sql = "SELECT 
                            COUNT(*) CNT
                          FROM 
                            BOARD 
                          WHERE 
                            MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND
                            DATE(TIMESTAMP) >= DATE(SUBDATE(NOW(), INTERVAL 30 DAY)) AND 
                            DATE(TIMESTAMP) <= DATE(NOW())";

        $write_cnt = $this->Db_m->getInfo($write_cnt_sql, 'PLAYBAT');

        $cupon_send_cnt_sql = "SELECT 
                                COUNT(*) CNT
                              FROM 
                                ITEM_INFO II, BOARD B
                              WHERE 
                                II.BOARD_IDX = B.BOARD_IDX AND
                                B.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "' AND
                                DATE(II.CUPON_SEND_TIME) >= DATE(SUBDATE(NOW(), INTERVAL 25 DAY)) AND 
                                DATE(II.CUPON_SEND_TIME) <= DATE(NOW())";

        $cupon_send_cnt = $this->Db_m->getInfo($cupon_send_cnt_sql, 'PLAYBAT');

        if (!$data['info']->BUSINESS_NAME) {
            alert('상호명등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->BUSINESS_NUMBER) {
            alert('(무료)사업자정보 등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->ADDRESS_ORG_IDX) {
            alert('주소 등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->PHONE) {
            alert('연락처인증이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if ($data['info']->TYPE === 'N' || $data['info']->TYPE === 'I') {
            // if ($write_cnt->CNT > 0) {
            //     alert('무료회원은 글작성후 30일후 작성 가능합니다.', '/index/premium');
            //     exit;
            // }
            if($cupon_send_cnt->CNT >0) {
                alert('무료회원은 결과 입력 후 25일후 작성 가능합니다.', '/index/premium');
                exit;   
            }
            if ($board_cnt->CNT > 0) {
                alert('이미 진행중인 게시물이 있습니다.', '/index/mypage/write');
                exit;
            }
        }

        if ($data['info']->TYPE === 'Y') {
            if ($board_cnt->CNT > 0) {
                alert('이미 진행중인 게시물이 있습니다.', '/index/mypage/write');
                exit;
            }
        }

        $address_org_info_sql = "SELECT 
                    M.ADDRESS_ORG_IDX,
                    AO.SIDO,
                    AO.SIGUNGU
                FROM
                    MEMBER M, ADDRESS_ORG AO
                WHERE 
                    M.ADDRESS_ORG_IDX = AO.ADDRESS_ORG_IDX AND
                    M.MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['address_org_info'] = $this->Db_m->getInfo($address_org_info_sql, 'PLAYBAT');

        $si_sql = "SELECT
                        ADDRESS_ORG_IDX, 
                        SIDO 
                    FROM 
                        ADDRESS_ORG 
                        GROUP BY SIDO ORDER BY ADDRESS_ORG_IDX ASC";

        $data['si_lists'] = $this->Db_m->getList($si_sql, 'PLAYBAT');

        $category_sql = "SELECT
                            CATEGORY_IDX,
                            NAME,
                            SHOW_LEVEL,
                            USE_YN,
                            TIMESTAMP
                         FROM
                            CATEGORY
                         WHERE
                            USE_YN = 'Y' AND
                            PNUM = 0
                            ORDER BY SHOW_LEVEL = 0, SHOW_LEVEL";

        $data['category_lists'] = $this->Db_m->getList($category_sql, 'PLAYBAT');

        $this->load->view('write', $data);
    }

    function view() {

        $sql = "SELECT 
                    B.BOARD_IDX,
                    M.NAME,
                    M.BUSINESS_NAME,
                    CONCAT(AO.SIDO, ' ', AO.SIGUNGU) LOCATION,
                    CONCAT(AO.SIDO, ' ', AO.SIGUNGU, ' ', AO.DONG, ' ', M.DETAIL_ADDR) ADDR,
                    IF(
                        B.CATEGORY_IDX = 1, 'AREA', 
                        B.HASH_TAG
                    ) CATEGORY_TYPE,
                    AO.SIGUNGU,
                    M.HOME_PAGE,
                    B.TITLE,
                    B.TIME_LINE,
                    B.EFFECTIVE_TIME,
                    M.MEMBER_IDX,
                    NOW() NOW,
                    M.BUSINESS_NAME,
                    M.BUSINESS_PHONE PHONE,
                    B.CONTENTS,
                    B.LINK_URL,
                    B.VIDEO
                FROM
                    BOARD B, MEMBER M, ADDRESS_ORG AO, CATEGORY C
                WHERE 
                    B.MEMBER_IDX = M.MEMBER_IDX AND
                    B.CATEGORY_IDX = C.CATEGORY_IDX AND
                    B.ADDRESS_ORG_IDX = AO.ADDRESS_ORG_IDX AND
                    B.BOARD_IDX = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($sql, 'PLAYBAT');

        $member_sql = "SELECT
                        PROFILE_IMG 
                       FROM 
                        MEMBER 
                       WHERE 
                        MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['member_info'] = $this->Db_m->getInfo($member_sql, 'PLAYBAT');

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
                        I.BOARD_IDX = '" . $data['info']->BOARD_IDX . "'";

        $data['item_lists'] = $this->Db_m->getList($item_sql, 'PLAYBAT');

        $file_sql = "SELECT
                        LOCATION 
                     FROM 
                        BOARD_FILE 
                     WHERE 
                        BOARD_IDX = '" . $data['info']->BOARD_IDX . "'";

        $data['file_lists'] = $this->Db_m->getList($file_sql, 'PLAYBAT');

        $comment_sql = "SELECT
                            C.COMMENT_IDX,
                            C.MEMBER_IDX,
                            M.NAME,
                            M.PROFILE_IMG,
                            C.CONTENTS
                        FROM 
                            COMMENT C, MEMBER M
                        WHERE 
                            C.MEMBER_IDX = M.MEMBER_IDX AND
                            C.BOARD_IDX = '" . $data['info']->BOARD_IDX . "' AND
                            C.PNUM IS NULL
                            ORDER BY C.TIMESTAMP DESC";

        $data['comment_lists'] = $this->Db_m->getList($comment_sql, 'PLAYBAT');

        foreach ($data['comment_lists'] as $row) {
            $comment_sub_sql = "SELECT
                                    C.COMMENT_IDX,
                                    C.MEMBER_IDX,
                                    M.NAME,
                                    M.PROFILE_IMG,
                                    C.CONTENTS
                                FROM 
                                    COMMENT C, MEMBER M
                                WHERE 
                                    C.MEMBER_IDX = M.MEMBER_IDX AND
                                    C.BOARD_IDX = '" . $data['info']->BOARD_IDX . "' AND
                                    C.PNUM = '" . $row['COMMENT_IDX'] . "'
                                    ORDER BY C.TIMESTAMP DESC";

            $data['comment_sub_lists' . $row['COMMENT_IDX']] = $this->Db_m->getList($comment_sub_sql, 'PLAYBAT');
        }

        $this->load->view('view', $data);
    }

    function user_info() {

        $member_info_sql = "SELECT
                                M.MEMBER_IDX, 
                                M.NAME, 
                                M.EMAIL,
                                M.PROFILE_IMG,
                                M.PWD,
                                M.ADDRESS_ORG_IDX,
                                CONCAT(AO.SIDO, ' ', AO.SIGUNGU, ' ', AO.DONG) LOCATION,
                                M.PHONE,
                                M.BUSINESS_NAME,
                                M.BUSINESS_NUMBER
                            FROM 
                                MEMBER M LEFT JOIN ADDRESS_ORG AO
                                ON AO.ADDRESS_ORG_IDX = M.ADDRESS_ORG_IDX
                            WHERE 
                                M.MEMBER_IDX = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        $this->load->view('userinfo', $data);
    }

    function modify() {

        if (!$this->session->userdata('MEMBER_IDX')) {
            alert('로그인이 필요합니다.', '/index/login');
            exit;
        }

        $member_info_sql = "SELECT
                                MEMBER_IDX, 
                                NAME, 
                                PROFILE_IMG,
                                PHONE,
                                BUSINESS_NAME,
                                BUSINESS_NUMBER,
                                ADDRESS_ORG_IDX,
                                TYPE
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        if (!$data['info']->BUSINESS_NAME) {
            alert('상호명등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->BUSINESS_NUMBER) {
            alert('사업자번호 등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->ADDRESS_ORG_IDX) {
            alert('주소 등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->PHONE) {
            alert('연락처인증이 필요합니다.', '/index/mypage/info');
            exit;
        }

        $si_sql = "SELECT
                        ADDRESS_ORG_IDX, 
                        SIDO 
                    FROM 
                        ADDRESS_ORG 
                        GROUP BY SIDO ORDER BY ADDRESS_ORG_IDX ASC";

        $data['si_lists'] = $this->Db_m->getList($si_sql, 'PLAYBAT');

        $category_sql = "SELECT
                            CATEGORY_IDX,
                            NAME,
                            SHOW_LEVEL,
                            USE_YN,
                            TIMESTAMP
                         FROM
                            CATEGORY
                         WHERE
                            USE_YN = 'Y' AND
                            PNUM = 0
                            ORDER BY SHOW_LEVEL = 0, SHOW_LEVEL";

        $data['category_lists'] = $this->Db_m->getList($category_sql, 'PLAYBAT');

//        IF(
//                                (SELECT 
//                                    PNUM 
//                                FROM 
//                                    CATEGORY
//                                WHERE
//                                    CATEGORY_IDX = B.CATEGORY_IDX
//                                ) <> 0, 
//                                (SELECT 
//                                    PNUM 
//                                FROM 
//                                    CATEGORY
//                                WHERE
//                                    CATEGORY_IDX = B.CATEGORY_IDX
//                                ), B.CATEGORY_IDX
//                                
//                            ) CATEGORY_IDX,

        $board_sql = "SELECT
                            B.BOARD_IDX, 
                            B.TIME_LINE, 
                            B.ITEM_CNT,
                            B.TITLE,
                            B.CUPON_TIME,
                            B.ADDRESS_ORG_IDX,
                            AO.SIDO,
                            AO.SIGUNGU,
                            B.CONTENTS,
                            B.LINK_URL,
                            B.CATEGORY_IDX,
                            B.HASH_TAG,
                            B.EFFECTIVE_TIME,
                            B.VIDEO
                        FROM 
                            BOARD B, ADDRESS_ORG AO
                        WHERE 
                            AO.ADDRESS_ORG_IDX = B.ADDRESS_ORG_IDX AND
                            B.BOARD_IDX = '" . $this->uri->segment(3) . "'";

        $data['board_info'] = $this->Db_m->getInfo($board_sql, 'PLAYBAT');

        $item_sql = "SELECT
                        NAME 
                     FROM
                        ITEM 
                     WHERE 
                        BOARD_IDX = '" . $data['board_info']->BOARD_IDX . "'";

        $data['board_item_lists'] = $this->Db_m->getList($item_sql, 'PLAYBAT');

        $img_sql = "SELECT
                        LOCATION, 
                        ORG_NAME 
                    FROM 
                        BOARD_FILE 
                    WHERE 
                        BOARD_IDX = '" . $data['board_info']->BOARD_IDX . "'";

        $data['img_lists'] = $this->Db_m->getList($img_sql, 'PLAYBAT');

        $this->load->view('modify', $data);
    }

    function premium() {

        if (!$this->session->userdata('MEMBER_IDX')) {
            alert('로그인이 필요합니다.', '/index/login');
            exit;
        }

        $member_info_sql = "SELECT
                                MEMBER_IDX, 
                                NAME, 
                                PROFILE_IMG,
                                PHONE,
                                BUSINESS_NAME,
                                BUSINESS_NUMBER,
                                ADDRESS_ORG_IDX,
                                TYPE
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        if (!$data['info']->BUSINESS_NAME) {
            alert('상호명등록이 필요합니다.', '/index/mypage/info');
            exit;
        }

        if (!$data['info']->BUSINESS_NUMBER) {
            alert('(무료)사업자정보 등록이 필요합니다.', '/index/mypage/info');
            exit;
        }


        $member_info_sql = "SELECT
                                MEMBER_IDX, 
                                PREMIUM_DATE,
                                TYPE
                            FROM 
                                MEMBER 
                            WHERE 
                                MEMBER_IDX = '" . $this->session->userdata('MEMBER_IDX') . "'";

        $data['info'] = $this->Db_m->getInfo($member_info_sql, 'PLAYBAT');

        $this->load->view('premium', $data);
    }

}
