<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DataFunction extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->TIMING_CUSTOMER = $this->load->database('TIMING_CUSTOMER', TRUE);
        $this->TIMING_NEWS = $this->load->database('TIMING_NEWS', TRUE);
        $this->TIMING_STATS = $this->load->database('TIMING_STATS', TRUE);
        $this->FX_TIMIBRKA = $this->load->database('FX_TIMIBRKA', TRUE);

        $this->load->helper(array('url', 'date', 'form', 'alert'));
        $this->load->model('Db_m');
        $this->load->library('session');
    }

    function adminLogin() {
        //sql 인젝션 방지
        $id = $this->TIMING_CUSTOMER->escape($this->input->post('adminId', TRUE));
        $pwd = $this->TIMING_CUSTOMER->escape(hash('sha256', $this->input->post('adminPw', TRUE)));

        $sql = "SELECT
                    admin_seq,
                    admin_id,
                    admin_level,
                    admin_name
                FROM 
                    admin 
                WHERE
                    admin_id = $id AND
                    admin_pass = $pwd AND
                    acept = 1";

        $res = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        if ($res) {
            //세션 생성
            $newdata = array(
                'admin_id' => $res->admin_id,
                'admin_seq' => $res->admin_seq,
                'admin_level' => $res->admin_level,
                'admin_name' => $res->admin_name
            );
            $this->session->set_userdata($newdata);

            if ($this->input->post('login_auto')) {
                $cookie = array(
                    'name' => 'user_id',
                    'value' => $res->admin_id,
                    'expire' => '999999',
                    'prefix' => 'do_',
                    'secure' => false
                );

                $this->input->set_cookie($cookie);
            }

            alert('로그인 되었습니다.', '/index/main');
        } else {

            alert('아이디나 비밀번호를 확인해주세요', '/');
        }
    }

    function logout() {
        $this->load->helper('cookie');
        $this->session->sess_destroy();
        delete_cookie('do_user_id');
        echo "<script language='javascript'>";
        echo "alert('로그아웃 되었습니다.');";
        echo "location.href='/'";
        echo "</script>";
    }

    //메인 keyword리스트 추가 
    function indexKeyWordList() {

        $count_sql = "SELECT count(1) CNT
                        FROM (SELECT @rnum := CASE WHEN A.stock_seq = @stock_seq THEN @rnum + 1 ELSE 1 END AS rnum
                                    ,@stock_seq := A.stock_seq AS stock_seq, A.reg_date
                              FROM timing_news.keyword A
                              CROSS JOIN (SELECT @stock_seq :=0, @rnum := 0) A
                              ORDER BY A.stock_seq, A.reg_date DESC, A.ord
                        ) A
                        INNER JOIN timing_news.stock B ON B.stock_seq = A.stock_seq
                        WHERE A.rnum = 1
                          AND EXISTS (SELECT 1 
                                        FROM fx_timbrka.fx_stock_item_related_keywords_meta X
                                      WHERE X.stock_item_code = B.crp_cd 
                                        AND X.acted_on > A.reg_date )";

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_NEWS');

        $sql = "SELECT 
                    B.company_name_i
                FROM (SELECT @rnum := CASE WHEN A.stock_seq = @stock_seq THEN @rnum + 1 ELSE 1 END AS rnum
                            ,@stock_seq := A.stock_seq AS stock_seq, A.reg_date
                      FROM timing_news.keyword A
                      CROSS JOIN (SELECT @stock_seq :=0, @rnum := 0) A
                      ORDER BY A.stock_seq, A.reg_date DESC, A.ord
                ) A
                INNER JOIN timing_news.stock B ON B.stock_seq = A.stock_seq
                WHERE A.rnum = 1
                  AND EXISTS (SELECT 1 
                                FROM fx_timbrka.fx_stock_item_related_keywords_meta X
                              WHERE X.stock_item_code = B.crp_cd 
                                AND X.acted_on > A.reg_date ) ";
        $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);
        $i = 1;
        foreach ($res as $row) {
            $result['data'][] = array(
                'recordsFiltered' => intval($count_res->CNT),
                'company_name_i' => $row['company_name_i']
            );

            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170810 이승용
    function user_list() {

        $addWhere = "";

        if ($this->input->get('search', true)['value'] && !$this->input->get('search_text', true)) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('search_text', true) && $this->input->get('search', true)['value']) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('search_text', true) && !$this->input->get('search', true)['value']) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search_text', true) . "%' ";
        }

        if ($this->input->get('sdate', true) && $this->input->get('edate', true)) {
            $addWhere .= "AND A.reg_date >= '" . $this->input->get('sdate', true) . " 00:00:00' AND A.reg_date <= '" . $this->input->get('edate', true) . " 23:59:59' ";
        }

        if ($this->input->get('type_select', true) == 'login_path') {
            $addWhere .= "AND A.customer_type = '" . $this->input->get('type_location', true) . "' ";
        }

        if ($this->input->get('type_select', true) == 'pay') {
            $addWhere .= "AND A.`status` = '" . $this->input->get('type_pay', true) . "'";
        }

        $columns = array(
            // datatable column index  => database column name
            0 => 'A.customer_seq',
            1 => 'A.user_name',
            2 => 'A.email',
            3 => 'A.phone',
            4 => 'customer_type',
            5 => 'A.reg_date',
            6 => 'status'
        );

        $count_sql = "SELECT
                            COUNT(*) CNT
                      FROM
                            customer A
                      WHERE 
                            A.customer_seq <> '' ";
        $count_sql .= $addWhere;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

        $lists_sql = " SELECT 
                            A.customer_seq, 
                            A.user_name, 
                            A.email, 
                            A.phone, 
                            CASE A.customer_type 
                                WHEN 0 THEN '이메일(일반)'
                                WHEN 1 THEN '카카오'
                                WHEN 2 THEN '페이스북'
                                WHEN 3 THEN '구글플러스'
                                ELSE '미회원 기기 등록'
                            END AS customer_type, 
                            DATE_FORMAT(A.reg_date, '%Y/%m/%d') AS reg_date,
                            CASE A.`status` 
                                WHEN NULL THEN '무료'
                                WHEN 0 THEN '무료'
                                WHEN 1 THEN '유료'
                                WHEN 2 THEN '유료'
                            END AS `status` 
                        FROM 
                            customer A 
                        WHERE 
                            1 = 1 ";
        $lists_sql .= $addWhere;
        if ($this->input->get('order', true)[0]['column']) {
            $lists_sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $lists_sql .= " ORDER BY A.customer_seq DESC ";
        }
        $lists_sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";

        $res = $this->Db_m->getList($lists_sql, 'TIMING_CUSTOMER');

        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        foreach ($res as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'customer_seq' => $row['customer_seq'],
                'user_name' => $row['user_name'] . '<input type="hidden" class="idx" value="' . $row['customer_seq'] . '">',
                'email' => $row['email'],
                'phone' => preg_replace("/(^02.{0}|^01.{1}|^07.{1}|^03.{1}|^04.{1}|^05.{1}|^06.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $row['phone']),
                'customer_type' => $row['customer_type'],
                'reg_date' => $row['reg_date'],
                'status' => $row['status']
            );
        }

        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //이승용 페이징 파라미터 필요
    function user_drop_list() {

        $addWhere = "";

        if ($this->input->get('search', true)['value'] && !$this->input->get('search_text', true)) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('search_text', true) && $this->input->get('search', true)['value']) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('search_text', true) && !$this->input->get('search', true)['value']) {
            $addWhere .= "AND A.user_name LIKE '%" . $this->input->get('search_text', true) . "%' ";
        }

        if ($this->input->get('sdate', true) && $this->input->get('edate', true)) {
            $addWhere .= "AND A.reg_date >= '" . $this->input->get('sdate', true) . " 00:00:00' AND A.reg_date <= '" . $this->input->get('edate', true) . " 23:59:59' ";
        }

        if ($this->input->get('type_select', true) == 'login_path') {
            $addWhere .= "AND A.customer_type = '" . $this->input->get('type_location', true) . "' ";
        }

        if ($this->input->get('type_select', true) == 'pay') {
            $addWhere .= "AND A.`status` = '" . $this->input->get('type_pay', true) . "'";
        }

        $columns = array(
            // datatable column index  => database column name
            0 => 'A.customer_seq',
            1 => 'A.user_name',
            2 => 'A.email',
            3 => 'A.phone',
            4 => 'customer_type',
            5 => 'A.reg_date',
            6 => 'A.withdraw_date',
            7 => 'status'
        );

        $count_sql = "SELECT
                            COUNT(*) CNT
                      FROM
                            customer_withdraw_history A
                      WHERE 
                            A.customer_seq <> '' ";
        $count_sql .= $addWhere;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

        $lists_sql = " SELECT 
                            A.customer_seq, 
                            A.user_name, 
                            A.email, 
                            A.phone, 
                                CASE A.customer_type 
                                    WHEN 0 THEN '이메일(일반)'
                                    WHEN 1 THEN '카카오'
                                    WHEN 2 THEN '페이스북'
                                    WHEN 3 THEN '구글플러스'
                                    ELSE '미회원 기기 등록'
                                END AS customer_type, 
                                DATE_FORMAT(A.reg_date, '%Y/%m/%d') AS reg_date, 
                                DATE_FORMAT(A.withdraw_date, '%Y/%m/%d') AS withdraw_date, 
                                CASE A.`status` 
                                    WHEN NULL THEN '무료'
                                    WHEN 0 THEN '무료'
                                    WHEN 1 THEN '유료'
                                    WHEN 2 THEN '유료'
                                END AS `status` 
                       FROM customer_withdraw_history A 
                            WHERE 1 = 1 ";
        $lists_sql .= $addWhere;
        if ($this->input->get('order', true)[0]['column']) {
            $lists_sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $lists_sql .= " ORDER BY A.customer_seq DESC ";
        }
        $lists_sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";

        $res = $this->Db_m->getList($lists_sql, 'TIMING_CUSTOMER');

        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        $i = 1;
        foreach ($res as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'customer_seq' => $row['customer_seq'],
                'user_name' => $row['user_name'],
                'email' => $row['email'],
                'phone' => preg_replace("/(^02.{0}|^01.{1}|^07.{1}|^03.{1}|^04.{1}|^05.{1}|^06.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1-$2-$3", $row['phone']),
                'customer_type' => $row['customer_type'],
                'reg_date' => $row['reg_date'],
                'withdraw_date' => $row['withdraw_date'],
                'status' => $row['status']
            );
            $i++;
        }

        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function userView() {
        $sql = " SELECT customer_seq, user_name, email, phone
					, IF(customer_type = 0, '이메일(일반)', IF(customer_type = 1, '카카오', IF(customer_type = 2, '페이스북', IF(customer_type = 3, '구글플러스', '미회원 기기 등록')))) customer_type,
					DATE_FORMAT(reg_date, '%Y/%m/%d') reg_date,
					  IF(
						  IFNULL(status, '무료') = '무료', 
						  '무료', 
						  IF(
							  status = 0, 
							  '무료', 
							  '유료'
						  )
					  ) status
					FROM customer 
					WHERE 
					customer_seq = '" . $this->input->post('idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        $result = array(
            'customer_seq' => $res->customer_seq,
            'user_name' => $res->user_name,
            'email' => $res->email,
            'phone' => $res->phone,
            'customer_type' => $res->customer_type,
            'reg_date' => $res->reg_date,
            'status' => $res->status
        );

        print_r(json_encode($result));
    }

    function modUser() {
        $updateArray = array(
            'user_name' => $this->input->post('user_name', true),
            'email' => $this->input->post('email', true),
            'phone' => $this->input->post('phone', true)
        );

        $updateWhere = array(
            'customer_seq' => $this->input->post('customer_seq', true)
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->Db_m->update('customer', $updateArray, $updateWhere, 'TIMING_CUSTOMER');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/user_list');
        } else {
            alert('회원수정 되었습니다.', '/index/user_list');
        }
    }

    //20170810 이승용 유저탈퇴 프로시저 호출필요 CALL usp_delete_customer($customer_seq, @p1, @p2);
    function delUser() {
        $sql = "SELECT
                    customer_seq, 
                    customer_type, 
                    user_name, 
                    email, 
                    password, 
                    phone, 
                    reg_date, 
                    status 
                FROM 
                    customer 
                WHERE 
                    customer_seq = '" . $this->input->post('customer_seq', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        if ($res) {

            $del_sp = "CALL usp_delete_customer(" . $this->input->post('customer_seq', true) . ", @p1, @p2)";
//            $insArray = array(
//                'customer_seq' => $res->customer_seq,
//                'withdraw_date' => date("Y-m-d H:i:s", time()),
//                'customer_type' => $res->customer_type,
//                'user_name' => $res->user_name,
//                'email' => $res->email,
//                'password' => $res->password,
//                'phone' => $res->phone,
//                'reg_date' => $res->reg_date,
//                'status' => $res->status
//            );
//
//            $delWhere = array(
//                'customer_seq' => $res->customer_seq
//            );
//
//            $insHistoryArray = array(
//                'menu_seq' => '2',
//                'admin_seq' => $this->session->userdata('admin_seq'),
//                'work' => $this->input->post('history_name', true) . ' 회원 탈퇴처리',
//                'reg_date' => date("Y-m-d H:i:s", time())
//            );

            $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

            $this->TIMING_CUSTOMER->query($del_sp);
//            $this->Db_m->insData('customer_withdraw_history', $insArray, 'TIMING_CUSTOMER');
//            $this->Db_m->delete('customer', $delWhere, 'TIMING_CUSTOMER');
//            $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

            $this->TIMING_CUSTOMER->trans_complete();

            if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
                echo "FAILED";
            } else {
                echo "SUCCESS";
            }
        } else {
            echo "NONDATA";
        }
    }

    function insBusiness() {
        $insArray = array(
            'ind_cd' => $this->input->post('ind_cd', true),
            'description' => $this->input->post('description', true)
        );

        $insHistoryArray = array(
            'menu_seq' => '5',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('description', true) . ' 업종등록',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->insData('business', $insArray, 'TIMING_NEWS');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/business');
        } else {
            alert('등록 되었습니다.', '/index/business');
        }
    }

    function businessLists() {
        $business_sql = "	SELECT business_seq, ind_cd, description
									FROM business 
									ORDER BY business_seq DESC ";

        $business_lists = $this->Db_m->getList($business_sql, 'TIMING_NEWS');

        $result = array();
        foreach ($business_lists as $row) {
            $result[] = array(
                'business_seq' => '<input type="checkbox" class="row_check" value="' . $row['business_seq'] . '">',
                'contents' => $row['description']
            );
        }

        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function delBusiness() {
        $sql = "DELETE
					FROM business 
					WHERE 
                    business_seq IN(" . $this->input->post('idx') . ")";

        $select_sql = " 	SELECT description
								FROM business 
								WHERE 
								business_seq IN(" . $this->input->post('idx') . ") ";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        foreach ($select_res as $row) {
            $insHistoryArray[] = array(
                'menu_seq' => '5',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $row['description'] . ' 업종삭제',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function stockLists() {
        $stock_sql = "	SELECT stock_seq, company_name, crp_cd
								FROM stock 
								WHERE 
								business_seq = '" . $this->input->get('business_seq', true) . "'";

        $stock_lists = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');
        $result = array();
        foreach ($stock_lists as $row) {
            $result[] = array(
                'contents' => $row['company_name'] . '(' . $row['crp_cd'] . ')'
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170810 이승용 페이징 파라미터 필요
    function optionLists() {

        $add_where = "";

        if ($this->input->get('option')) {
            if ($this->input->get('option') != 'all') {
                $add_where .= "AND SK.kind = '" . $this->input->get('option', true) . "' ";
                if ($this->input->get('text')) {
                    $add_where .= "AND (SK.crp_cd LIKE '%" . $this->input->get('text') . "%' OR SK.company_name LIKE '%" . $this->input->get('text') . "%')";
                }
            } else if ($this->input->get('option') == 'all') {
                if ($this->input->get('text')) {
                    $add_where .= "AND (SK.crp_cd LIKE '%" . $this->input->get('text') . "%' OR SK.company_name LIKE '%" . $this->input->get('text') . "%')";
                }
            }
        } else {
            if ($this->input->get('search', true)['value']) {
                $add_where .= "AND (SK.crp_cd LIKE '%" . $this->input->get('search', true)['value'] . "%' OR SK.company_name LIKE '%" . $this->input->get('search', true)['value'] . "%')";
            }
        }

        $columns = array(
            // datatable column index  => database column name
            1 => 'kind',
            2 => 'SK.company_name',
            3 => 'SK.crp_cd',
            4 => 'BS.description',
            5 => 'stock_status'
        );

        $count_sql = "SELECT
                            COUNT(*) CNT
                      FROM
                            stock SK, business BS
                      WHERE 
                            SK.business_seq = BS.business_seq ";
        $count_sql .= $add_where;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_NEWS');

        $stock_sql = "SELECT
                            SK.stock_seq,
                            IF(SK.kind = 'Y', '유가', IF(SK.kind = 'K', '코스닥', IF(SK.kind = 'N', '코넥스', '기타'))) kind,
                            SK.company_name, 
                            SK.crp_cd,
                            BS.description,
                            IF(SK.stock_status = 1, '노출', '비노출') stock_status
                      FROM
                            stock SK, business BS
                      WHERE 
                            SK.business_seq = BS.business_seq ";
        $stock_sql .= $add_where;
        if ($this->input->get('order', true)[0]['column']) {
            $stock_sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
        }
        $stock_sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . " ";

        $stock_lists = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');
        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        foreach ($stock_lists as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['stock_seq'] . '">',
                'company_name' => $row['company_name'],
                'kind' => $row['kind'],
                'crp_cd' => $row['crp_cd'],
                'description' => $row['description'],
                'stock_status' => $row['stock_status']
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function keyword_add_html() {

        $data['keyword'] = $this->input->post('keyword', true);
        $data['index'] = $this->input->post('index', true);
        $this->load->view('sub/contents/option_add_keyword', $data);
    }

    function insOption() {

        $this->TIMING_NEWS->set('company_name', $this->input->post('company_name', true));
        $this->TIMING_NEWS->set('company_name_e', $this->input->post('company_name_e', true));
        $this->TIMING_NEWS->set('company_name_i', $this->input->post('company_name_i', true));
        $this->TIMING_NEWS->set('business_seq', $this->input->post('business_seq', true));
        $this->TIMING_NEWS->set('kind', $this->input->post('kind', true));
        $this->TIMING_NEWS->set('crp_cd', $this->input->post('crp_cd', true));
        $this->TIMING_NEWS->set('ticker', '');
        $this->TIMING_NEWS->set('crp_no', $this->input->post('crp_no', true));
        $this->TIMING_NEWS->set('bsn_no', $this->input->post('bsn_no', true));
        $this->TIMING_NEWS->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->TIMING_NEWS->set('stock_status', b'' . $this->input->post('stock_status', true) . '', false);

        $insHistoryArray = array(
            'menu_seq' => '6',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('company_name_i', true) . ' 종목등록',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->insert('stock');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $ins_id = $this->TIMING_NEWS->insert_id();

        if ($this->input->post('keyword', true)) {
            for ($i = 0; $i < count($this->input->post('keyword', true)); $i++) {

                $insKeyword_sp = "CALL classification_keywords_ins('$ins_id', '" . $this->input->post('keyword', true)[$i] . "', @sqlstate, @errno, @text)";

                $this->TIMING_NEWS->query($insKeyword_sp);
//                $insKeywordArray = array(
//                    'stock_seq' => $ins_id,
//                    'sort_seq' => $i + 1,
//                    '_keyword' => $this->input->post('keyword', true)[$i]
//                );
//
//                $this->Db_m->insData('classification_keywords', $insKeywordArray, 'TIMING_NEWS');
//                $ins_id2 = $this->TIMING_NEWS->insert_id();
                $getInsKeyword_sp = "SELECT @sqlstate, @errno, @text";
                $ins_id2 = $this->Db_m->getInfo($getInsKeyword_sp, 'TIMING_NEWS');
                $getStatus = '@sqlstate';

                for ($j = 0; $j < count($this->input->post('keyword_row', true)[$i + 1]); $j++) {
//                    $insSubKeyWordArray[] = array(
//                        'keyword_id' => $ins_id2,
//                        'sort_seq' => $j + 1,
//                        'sub_keywords' => $this->input->post('keyword_row', true)[$i + 1][$j]
//                    );

                    $insSubKeyword_sp = "CALL classification_subkeywords_ins('" . $ins_id2->$getStatus . "', '" . $this->input->post('keyword_row', true)[$i + 1][$j] . "', @errno, @text)";
                    $this->TIMING_NEWS->query($insSubKeyword_sp);
                }
            }

//            if ($insSubKeyWordArray) {
//                $this->Db_m->insMultiData('classification_subkeywords', $insSubKeyWordArray, 'TIMING_NEWS');
//            }
        }

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/option_add');
        } else {
            alert('종목 등록 되었습니다.', '/index/option');
        }
    }

    function modOption() {
        $this->TIMING_NEWS->set('company_name', $this->input->post('company_name', true));
        $this->TIMING_NEWS->set('company_name_e', $this->input->post('company_name_e', true));
        $this->TIMING_NEWS->set('company_name_i', $this->input->post('company_name_i', true));
        $this->TIMING_NEWS->set('business_seq', $this->input->post('business_seq', true));
        $this->TIMING_NEWS->set('kind', $this->input->post('kind', true));
        $this->TIMING_NEWS->set('crp_cd', $this->input->post('crp_cd', true));
        $this->TIMING_NEWS->set('ticker', '');
        $this->TIMING_NEWS->set('crp_no', $this->input->post('crp_no', true));
        $this->TIMING_NEWS->set('bsn_no', $this->input->post('bsn_no', true));
        $this->TIMING_NEWS->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->TIMING_NEWS->set('stock_status', b'' . $this->input->post('stock_status', true) . '', false);

        $this->TIMING_NEWS->where('stock_seq', $this->input->post('idx', true));

        $insHistoryArray = array(
            'menu_seq' => '6',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('company_name_i', true) . ' 종목수정',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->update('stock');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $sql = "SELECT
                    id 
                FROM 
                    classification_keywords 
                WHERE 
                    stock_seq = '" . $this->input->post('idx', true) . "'";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        if ($res) {
//            $del_id = "";
            foreach ($res as $row) {
                $del_keyword_sp = "CALL classification_keywords_del ('" . $row['id'] . "', @errno, @text)";
                $this->TIMING_NEWS->query($del_keyword_sp);

                for ($i = 0; $i < count($this->input->post('subkeyword_id', true)); $i++) {
                    $del_subkeyword_sp = "CALL classification_subkeywords_del ('" . $row['id'] . "', '" . $this->input->post('subkeyword_id', true)[$i] . "', @errno, @text)";
                    $this->TIMING_NEWS->query($del_subkeyword_sp);
                }

//                $del_id .= $row['id'] . ',';
            }


//            $del_sql = "DELETE
//                        FROM 
//                            classification_keywords 
//                        WHERE 
//                            id IN(" . substr($del_id, 0, -1) . ")";
//
//            $sub_del_sql = "DELETE
//                            FROM 
//                                classification_subkeywords 
//                            WHERE 
//                                keyword_id IN(" . substr($del_id, 0, -1) . ")";
//
//            $this->TIMING_NEWS->query($sub_del_sql);
//            $this->TIMING_NEWS->query($del_sql);
        }

        if ($this->input->post('keyword', true)) {
            for ($i = 0; $i < count($this->input->post('keyword', true)); $i++) {

                $insKeyword_sp = "CALL classification_keywords_ins('" . $this->input->post('idx', true) . "', '" . $this->input->post('keyword', true)[$i] . "', @sqlstate, @errno, @text)";

                $this->TIMING_NEWS->query($insKeyword_sp);

//                $insKeywordArray = array(
//                    'stock_seq' => $this->input->post('idx', true),
//                    'sort_seq' => $i + 1,
//                    '_keyword' => $this->input->post('keyword', true)[$i]
//                );
//
//                $this->Db_m->insData('classification_keywords', $insKeywordArray, 'TIMING_NEWS');
//                $ins_id = $this->TIMING_NEWS->insert_id();

                $getInsKeyword_sp = "SELECT @sqlstate, @errno, @text";
                $ins_id2 = $this->Db_m->getInfo($getInsKeyword_sp, 'TIMING_NEWS');
                $getStatus = '@sqlstate';

                for ($j = 0; $j < count($this->input->post('keyword_row', true)[$this->input->post('main_id', true)[$i]]); $j++) {
//                    $insSubKeyWordArray[] = array(
//                        'keyword_id' => $ins_id,
//                        'sort_seq' => $j + 1,
//                        'sub_keywords' => $this->input->post('keyword_row', true)[$i + 1][$j]
//                    );
                    
                    $insSubKeyword_sp = "CALL classification_subkeywords_ins('" . $ins_id2->$getStatus . "', '" . $this->input->post('keyword_row', true)[$this->input->post('main_id', true)[$i]][$j] . "', @errno, @text)";
                    $this->TIMING_NEWS->query($insSubKeyword_sp);
                }
            }

//            if ($insSubKeyWordArray) {
//                $this->Db_m->insMultiData('classification_subkeywords', $insSubKeyWordArray, 'TIMING_NEWS');
//            }
        }

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/option_mod/' . $this->input->post('idx', true) . '');
        } else {
            alert('종목 수정 되었습니다.', '/index/option');
        }
    }

    function delOption() {
        $sql = "DELETE
                FROM 
                    stock 
                WHERE 
                    stock_seq IN(" . $this->input->post('idx') . ")";

        $select_sql = "SELECT
                        company_name_i
                       FROM 
                        stock 
                       WHERE 
                        stock_seq IN(" . $this->input->post('idx') . ")";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        foreach ($select_res as $row) {
            $insHistoryArray[] = array(
                'menu_seq' => '6',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $row['company_name_i'] . ' 종목삭제',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function optionSearchAutoComplete() {
        $sql = "SELECT
                  company_name_i, 
                  crp_cd
                FROM
                  stock 
                ";
        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        foreach ($res as $row) {
            $result[] = array(
                'name' => $row['company_name_i']
            );
        }

        foreach ($res as $row) {
            $result[] = array(
                'name' => $row['crp_cd']
            );
        }

        echo json_encode($result);
    }

    //20170810 이승용 쿼리 수정, 페이징 처리를 위한 값 필요
    function newsObjectLists() {

        $kind_search = "";

        if ($this->input->get('news_kind', true)) {
            $kind_search .= "AND A.news_kind = '" . $this->input->get('news_kind', true) . "' ";
        }

        $stock_search = "";

        if ($this->input->get('show', true)) {
            if ($this->input->get('show', true) == 'Y') {
                $status = 1;
            } else if ($this->input->get('show', true) == 'N') {
                $status = 0;
            }
            $stock_search .= "AND B.status = '$status' ";
        }
		
		
//        if ($this->input->get('text', true)) {
//            $stock_search .= "AND C.company_name_i LIKE '%" . $this->input->get('text', true) . "%' ";
//        }

        if ($this->input->get('search', true)['value'] && !$this->input->get('text', true)) {
            $stock_search .= "AND C.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && $this->input->get('search', true)['value']) {
            $stock_search .= "AND C.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && !$this->input->get('search', true)['value']) {
            $stock_search .= "AND C.company_name_i LIKE '%" . $this->input->get('text', true) . "%' ";
        }

        $temp_union = 1;

        if ($stock_search == "") {
            $temp_union = 1;
        } else {
            $temp_union = 2;
        }


        //실시간(1시간)이 디폴트
        $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -1 HOUR)";

        if ($this->input->get('sdate', true) && $this->input->get('edate', true)) {
            $date_search = "AND A.news_reg_date >= '" . $this->input->get('sdate', true) . " 00:00:00' AND A.news_reg_date <= '" . $this->input->get('edate', true) . " 23:59:59' ";
        }

        if ($this->input->get('time_chk', true) == '1') {
            $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -1 HOUR) ";
        }

        if ($this->input->get('time_chk', true) == '6') {
            $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -6 HOUR) ";
        }

        if ($this->input->get('time_chk', true) == '12') {
            $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -12 HOUR) ";
        }

        if ($this->input->get('time_chk', true) == 'day') {
            $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -1 DAY) ";
        }

        if ($this->input->get('time_chk', true) == '2day') {
            $date_search = "AND A.news_reg_date >= DATE_ADD(NOW(), INTERVAL -2 DAY) ";
        }

        $count_sql = "SELECT
                        COUNT(*) CNT
                      FROM
                        news A
                        INNER JOIN timing_news.news_stock B ON A.news_seq = B.news_seq
						INNER JOIN timing_news.stock C ON B.stock_seq = C.stock_seq
                      WHERE 
                        A.news_seq <> '' ";
        $count_sql .= $date_search;
        $count_sql .= $kind_search;
        $count_sql .= $stock_search;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_NEWS');

        $columns = array(
            // datatable column index  => database column name
            1 => 'D.news_title'
        );

        $sql = "SELECT
                    A.news_seq, 
                    D.news_title
		FROM(
                        (SELECT 
                            DISTINCT A.news_seq
                        FROM
                            (SELECT 
                                A.news_seq, 
                                A.news_reg_date
                            FROM 
                                timing_news.news A 
                            WHERE
                                A.news_reg_date <= DATE_FORMAT(CURRENT_TIMESTAMP(), '%Y-%m-%d 23:59:59'
                            )                            ";
        $sql .= $date_search;
        $sql .= $kind_search;
        $sql .= " ORDER BY A.news_reg_date DESC 
                  LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "
		) A
                    LEFT JOIN
                        timing_news.news_stock B 
                    ON 
                        A.news_seq = B.news_seq
                    LEFT JOIN
                        timing_news.stock D 
                    ON 
                        B.stock_seq = D.stock_seq
                    WHERE 1 = $temp_union
		)
		UNION ALL
		(SELECT 
                    DISTINCT A.news_seq
		FROM
                    (SELECT  
                        A.news_seq, 
                        A.news_reg_date
                    FROM 
                        timing_news.news A 
                    WHERE 
                        A.news_reg_date <= DATE_FORMAT(CURRENT_TIMESTAMP(), '%Y-%m-%d 23:59:59'
                    ) ";
        $sql .= $date_search;
        $sql .= $kind_search;
        $sql .= " ORDER BY A.news_reg_date DESC) A
                INNER JOIN timing_news.news_stock B ON A.news_seq = B.news_seq
                INNER JOIN timing_news.stock C ON B.stock_seq = C.stock_seq
                WHERE 
                    2 = $temp_union ";
        $sql .= $stock_search;
        $sql .= " LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "
                )
                )A
                INNER JOIN timing_news.news_content D ON A.news_seq = D.news_seq ";
        if ($this->input->get('order', true)[0]['column']) {
            $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $sql .= " ORDER BY D.news_reg_date DESC ";
        }
        //$sql .= $addWhere;

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

//        echo $sql;
        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);


        if ($this->input->get('show', true) == 'N') {
            if ($res) {
                foreach ($res as $row) {
                    $result['data'][] = array(
                        'draw' => intval($this->input->get('draw')),
                        'recordsTotal' => intval($count_res->CNT),
                        'recordsFiltered' => intval($count_res->CNT),
                        'checkbox' => '<input type="checkbox" class="row_check2" value="' . $row['news_seq'] . '">',
                        'news_title' => $row['news_title']
                    );
                }
            } else {
                $result['data'][] = array(
                    'draw' => intval($this->input->get('draw')),
                    'recordsTotal' => intval($count_res->CNT),
                    'recordsFiltered' => intval($count_res->CNT),
                    'checkbox' => '',
                    'news_title' => '데이터가 없습니다.'
                );
            }
        } else {
            foreach ($res as $row) {
                $result['data'][] = array(
                    'draw' => intval($this->input->get('draw')),
                    'recordsTotal' => intval($count_res->CNT),
                    'recordsFiltered' => intval($count_res->CNT),
                    'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['news_seq'] . '">',
                    'news_title' => $row['news_title']
                );
            }
        }
        $gropData['data'] = $result;

        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function loadSelectOptionStock() {
        $sql = "SELECT
                    SK.stock_seq,
                    SK.company_name_i
                FROM
                    stock SK, business BS
                WHERE 
                    SK.business_seq = BS.business_seq AND
                    SK.company_name_i LIKE '%" . $this->input->get('q', true) . "%'";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = [];

        foreach ($res as $row) {
            $result[] = [
                'id' => $row['stock_seq'],
                'text' => $row['company_name_i']
            ];
        }

        print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    function newsOptionSearchAutoComplete() {

        //20170810 이승용	쿼리수정	
        $sql = "SELECT A.company_name_i
					FROM timing_news.stock A
					WHERE A.stock_status = 1";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();

        foreach ($res as $row) {
            $result[] = array(
                'name' => $row['company_name_i']
            );
        }

        echo json_encode($result);
    }

    function newsView() {
        $sql = "SELECT
                    news_seq,
                    writer,  
                    DATE_FORMAT(reg_date, '%Y.%m.%d') reg_date,
                    news_title,
                    news_contents
                FROM 
                    news_content 
                WHERE 
                    news_seq = '" . $this->input->post('idx', true) . "'";

        $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        $result = array(
            'news_seq' => $res->news_seq,
            'writer' => $res->writer,
            'reg_date' => $res->reg_date,
            'news_title' => $res->news_title,
            'news_contents' => $res->news_contents
        );

        print_r(json_encode($result));
    }

    function newsView2() {
        $sql = "SELECT
                    NC.news_seq,
                    CASE 
                      WHEN NS.status = 1 
                      THEN '노출' 
                      WHEN NS.status = 0
                      THEN '대기'
                    END status,
                    NC.writer,  
                    NC.reg_date,
                    NC.news_title,
                    NC.news_contents
                FROM 
                    news_content NC
                    LEFT JOIN news_stock NS
                    ON NC.news_seq = NS.news_seq
                WHERE 
                    NC.news_seq = '" . $this->input->post('idx', true) . "'
                    GROUP BY news_seq";

        $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        $stock_sql = "SELECT 
                        S.company_name_i 
                      FROM 
                        news_stock NS, stock S
                      WHERE 
                        NS.stock_seq = S.stock_seq AND
                        NS.news_seq = '" . $res->news_seq . "'";

        $stock_res = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');

        $stock_lists = "";
        foreach ($stock_res as $row) {
            $stock_lists .= $row['company_name_i'] . ',';
        }

        $img_sql = "SELECT
                        news_img_path,
                        news_img_name
                    FROM 
                        news_image 
                    WHERE 
                        news_seq = '" . $res->news_seq . "'";

        $img_res = $this->Db_m->getInfo($img_sql, 'TIMING_NEWS');

        $img_url = '';
        if ($img_res) {
            $img_url = 'http://papers.eyesurfer.com' . $img_res->news_img_path . $img_res->news_img_name;
        }

        $result = array(
            'news_seq' => $res->news_seq,
            'company_name_i' => substr($stock_lists, 0, -1),
            'status' => $res->status,
            'writer' => $res->writer,
            'reg_date' => $res->reg_date,
            'news_title' => $res->news_title,
            'news_contents' => $res->news_contents,
            'news_img_path' => $img_url
        );

        print_r(json_encode($result));
    }

    function newsViewStock() {
        $stock_sql = "SELECT
                        S.stock_seq,
                        S.company_name_i
                      FROM 
                        news_stock NS, stock S 
                      WHERE 
                        NS.stock_seq = S.stock_seq AND
                        NS.news_seq = '" . $this->input->post('news_seq', true) . "'";

        $stock_res = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');

        $stock_result = [];

        foreach ($stock_res as $row) {
            $stock_result[] = [
                'id' => $row['stock_seq'],
                'text' => $row['company_name_i']
            ];
        }

        print_r(json_encode($stock_result));
    }

    function modNews() {
        $updateArray = array(
            'news_contents' => $this->input->post('news_contents', true)
        );

        $updateWhere = array(
            'news_seq' => $this->input->post('news_seq', true)
        );

        if ($this->input->post('news_kind', true)) {

            if ($this->input->post('news_kind', true) == '1') {
                $menu_sql = '8';
            }

            if ($this->input->post('news_kind', true) == '3') {
                $menu_sql = '9';
            }

            if ($this->input->post('news_kind', true) == '2') {
                $menu_sql = '10';
            }

            if ($this->input->post('news_kind', true) == '4') {
                $menu_sql = '11';
            }

            $insHistoryArray = array(
                'menu_seq' => $menu_sql,
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $this->input->post('news_title', true) . ' 수정',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        } else {
            $insHistoryArray = array(
                'menu_seq' => '7',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $this->input->post('news_title', true) . ' 수정',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $sql = "SELECT
                    N.news_reg_date,
                    N.news_kind
                FROM 
                    news N
                WHERE 
                    N.news_seq = '" . $this->input->post('news_seq', true) . "'";

        $stock_res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        $this->Db_m->update('news_content', $updateArray, $updateWhere, 'TIMING_NEWS');

        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->Db_m->delete('news_stock', $updateWhere, 'TIMING_NEWS');

        if ($this->input->post('multiple_select', true)) {
            foreach ($this->input->post('multiple_select', true) as $value) {
                $insStockArray[] = array(
                    'stock_seq' => $value,
                    'news_seq' => $this->input->post('news_seq', true),
                    'news_reg_date' => $stock_res->news_reg_date,
                    'news_kind' => $stock_res->news_kind,
                    'status' => 1
                );
            }

            $this->Db_m->insMultiData('news_stock', $insStockArray, 'TIMING_NEWS');
        }

        if ($this->input->post('status', true) == '0' || $this->input->post('status', true) == '1') {

            $updateStatusArray = array(
                'status' => $this->input->post('status', true)
            );
            $this->Db_m->update('news_stock', $updateStatusArray, $updateWhere, 'TIMING_NEWS');
        }

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function delNews() {
        $sql = "DELETE
                FROM 
                    news 
                WHERE 
                    news_seq IN(" . $this->input->post('idx') . ")";

        $addWhere = "";

        if ($this->input->post('news_kind', true)) {
            $addWhere .= "AND N.news_kind = '" . $this->input->post('news_kind', true) . "' ";
        }

        $select_sql = "SELECT 
                        NC.news_title
                      FROM 
                        news N, news_content NC
                      WHERE 
                        N.news_seq = NC.news_seq  AND
                        N.news_seq IN(" . $this->input->post('idx') . ") ";
        $select_sql .= $addWhere;
        $select_sql .= "GROUP BY N.news_seq ORDER BY N.news_reg_date DESC";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        if ($this->input->post('news_kind', true)) {

            if ($this->input->post('news_kind', true) == '1') {
                $menu_sql = '8';
            }

            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => $menu_sql,
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['news_title'] . ' 제외',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        } else {
            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => '7',
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['news_title'] . ' 제외',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function newsStatusChange() {
        $sql = "UPDATE
                    news_stock 
                SET
                    status = " . $this->input->post('status', true) . "
                WHERE 
                    news_seq IN(" . $this->input->post('idx') . ")";

        $addWhere = "";

        if ($this->input->post('news_kind', true)) {
            $addWhere .= "AND N.news_kind = '" . $this->input->post('news_kind', true) . "' ";
        }

        $select_sql = "SELECT 
                        NC.news_title
                      FROM 
                        news N, news_content NC
                      WHERE 
                        N.news_seq = NC.news_seq  AND
                        N.news_seq IN(" . $this->input->post('idx') . ") ";
        $select_sql .= $addWhere;
        $select_sql .= "GROUP BY N.news_seq ORDER BY N.news_reg_date DESC";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        if ($this->input->post('news_kind', true) == '1') {
            $menu_sql = '8';
        }

        if ($this->input->post('news_kind', true) == '3') {
            $menu_sql = '9';
        }

        if ($this->input->post('news_kind', true) == '2') {
            $menu_sql = '10';
        }

        if ($this->input->post('news_kind', true) == '4') {
            $menu_sql = '11';
        }

        if ($this->input->post('status') == '0') {
            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => $menu_sql,
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['news_title'] . ' 대기뉴스로 이동',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        } else if ($this->input->post('status') == '1') {
            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => $menu_sql,
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['news_title'] . ' 노출뉴스로 이동',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insNotice() {

        $this->db->set('notice_status', $this->input->post('notice_status', true));
        $this->db->set('is_top', b'' . $this->input->post('is_top', true) . '', false);
        $this->db->set('push_type', $this->input->post('push_type', true));
        $this->db->set('title', $this->input->post('title', true));
        $this->db->set('contents', $this->input->post('contents', true));
        $this->db->set('reg_date', date("Y-m-d H:i:s", time()));
        if ($this->input->post('push_type', true) == 2) {
            $this->db->set('push_date', $this->input->post('push_date', true));
        }

        $insHistoryArray = array(
            'menu_seq' => '19',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('title', true) . ' 추가',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->db->insert('tbl_notice_board');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/notice_add');
        } else {
            alert('공지사항 등록 되었습니다.', '/index/notice');
        }
    }

    function modNotice() {
        $this->db->set('notice_status', $this->input->post('notice_status', true));
        $this->db->set('is_top', b'' . $this->input->post('is_top', true) . '', false);
        $this->db->set('push_type', $this->input->post('push_type', true));
        $this->db->set('title', $this->input->post('title', true));
        $this->db->set('contents', $this->input->post('contents', true));
        if ($this->input->post('push_type', true) == 2) {
            $this->db->set('push_date', $this->input->post('push_date', true));
        } else {
            $this->db->set('push_date', NULL);
        }

        $this->db->where('notice_seq', $this->input->post('notice_seq', true));

        $insHistoryArray = array(
            'menu_seq' => '19',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('title', true) . ' 수정',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->db->update('tbl_notice_board');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/notice_view/' . $this->input->post('notice_seq', true) . '');
        } else {
            alert('공지사항 수정 되었습니다.', '/index/notice');
        }
    }

    //20170810 이승용 페이징 파라미터 필요
    function noticeLists() {

        if ($this->input->get('page', true)) {
            $page = $this->input->get('page', true);
            //페이지 카운트 예외처리
            if ($page == 0) {
                $page = 1;
            }
        } else { //임시 데이터
            $page = 1;
        }

        if ($this->input->get('page_count', true)) {
            $page_count = $this->input->get('page_count', true);
            //페이지 카운트 예외처리
            if ($page_count == 0) {
                $page_count = 10;
            }
        } else { //임시 데이터
            $page_count = 100;
        }

        $page_start = ($page - 1) * $page_count;

        $icon = '<span class="badge bg-red mr5">공지</span>';
        $sql = "SELECT
                    notice_seq, 
                    title,
                    DATE_FORMAT(reg_date, '%Y.%m.%d') reg_date, 
                    IF(notice_status = 0, '미노출', '노출') notice_status, 
                    push_date,
                    IF(push_type = 0, '미발송', IF(push_type = 1, '발송 완료', '발송 예약')) push_type,
                    IF(is_top = 1, '$icon', '') is_top_type
                FROM 
                    tbl_notice_board 
                    ORDER BY is_top DESC, reg_date DESC
				LIMIT  $page_start,  $page_count ";
        $res = $this->Db_m->getList($sql, 'TIMING_CUSTOMER');

        $result = array();

        $i = 1;
        foreach ($res as $row) {
            $result[] = array(
                'notice_seq' => $i . "<input type='hidden' class='idx' value= '" . $row['notice_seq'] . "'>",
                'title' => $row['is_top_type'] . $row['title'],
                'reg_date' => $row['reg_date'],
                'notice_status' => $row['notice_status'],
                'push_date' => $row['push_date'],
                'push_type' => $row['push_type']
            );

            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170814 페이징 파라미터 필요
    function questionLists() {

        $add_where = "";

        if ($this->input->get('type', true)) {
            $add_where .= "AND A.board_kind = " . $this->input->get('type', true) . " ";
        }

        if ($this->input->get('text', true)) {
            $add_where .= "AND A.board_contents LIKE '%" . $this->input->get('text', true) . "%' ";
        }

        //해당부분 order by부분에 필요합니다. 쿼리에 맞추어 검색하실 컬럼명을 작성해주세요.
        $columns = array(
            // datatable column index  => database column name
            1 => 'board_kind',
            2 => 'board_contents',
            3 => 'user_name',
            4 => 'reg_date',
            5 => 'status',
            6 => 'admin_name',
        );

        $count_sql = "SELECT
                        COUNT(*) CNT
                      FROM
                        board_ask A 
                        LEFT JOIN customer B ON A.customer_seq = B.customer_seq 
                        LEFT JOIN admin C ON A.admin_seq = C.admin_seq  
                        WHERE 1 = 1 ";
        $count_sql .= $add_where;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

        $sql = " SELECT 
                    A.board_seq, 
                    CASE A.board_kind 
                            WHEN 1 THEN '일반문의'
                            WHEN 2 THEN '계정문의'
                            WHEN 3 THEN '뉴스문의'
                            WHEN 4 THEN '제휴문의'
                            WHEN 5 THEN '랜딩페이지문의'
                            END board_kind
                    , A.board_contents, IFNULL(B.user_name, '') AS user_name, DATE_FORMAT(A.reg_date, '%Y-%m-%d') reg_date
                    , CASE A.`status`
                            WHEN 0 THEN '미완료'
                            WHEN 1 THEN '처리중'
                            WHEN 2 THEN '완료'
                            END `status`
                    , IFNULL(C.admin_name, '') AS admin_name
                    FROM board_ask A 
                    LEFT JOIN customer B ON A.customer_seq = B.customer_seq 
                    LEFT JOIN admin C ON A.admin_seq = C.admin_seq  
                    WHERE 1 = 1 ";
        $sql .= $add_where;
        if ($this->input->get('order', true)[0]['column']) {
            $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . " " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $sql .= "ORDER BY A.reg_date DESC ";
        }
        $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";

        $res = $this->Db_m->getList($sql, 'TIMING_CUSTOMER');


        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);
        $i = 1;
        foreach ($res as $row) {
            $result['data'][] = array(
                'recordsFiltered' => intval($count_res->CNT),
                'board_seq' => $row['board_seq'] . "<input type='hidden' class='idx' value= '" . $row['board_seq'] . "'>",
                'board_kind' => $row['board_kind'],
                'board_contents' => $row['board_contents'],
                'user_name' => $row['user_name'],
                'reg_date' => $row['reg_date'],
                'status' => $row['status'],
                'admin_name' => $row['admin_name']
            );

            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function mod_board_ask() {
        $updateArray = array(
            'status' => '2',
            'response_seq' => $this->input->post('board_seq', true),
            'admin_seq' => $this->input->post('admin_seq', true),
            'admin_reg_date' => date("Y-m-d H:i:s", time()),
            'contents_response' => $this->input->post('contents_response', true)
        );

        $updateWhere = array(
            'board_seq' => $this->input->post('board_seq', true)
        );
        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->Db_m->update('board_ask', $updateArray, $updateWhere, 'TIMING_CUSTOMER');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/question');
        } else {
            alert('답변 등록 되었습니다.', '/index/question');
        }
    }

    function ins_question_tmp() {
        $updateArray = array(
            'status' => '1',
            'contents_response' => $this->input->post('text', true)
        );

        $updateWhere = array(
            'board_seq' => $this->input->post('idx', true)
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->Db_m->update('board_ask', $updateArray, $updateWhere, 'TIMING_CUSTOMER');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insPopUp() {
        $file['location'] = '';
        $file['origin_name'] = '';

        if (@$_FILES['file']['name']) {

            $this->load->library('upload');

            $url_path = "/popupImg";
            $upload_config = Array(
                'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                'encrypt_name' => TRUE,
                'max_size' => '512000'
            );
            $this->upload->initialize($upload_config);
            $upfile = $_FILES['file']['name'];
            if (!$this->upload->do_upload('file')) {
                echo $this->upload->display_errors();
            }
            $info = $this->upload->data();
            $file['location'] = $url_path . "/" . $info['file_name'];
            $file['origin_name'] = $info['orig_name'];
        }

        $this->db->set('popup_title', $this->input->post('popup_title', true));
        $this->db->set('popup_start_day', $this->input->post('popup_start_day', true));
        $this->db->set('popup_end_day', $this->input->post('popup_end_day', true));
        $this->db->set('popup_title', $this->input->post('popup_title', true));
        $this->db->set('popup_contents', $this->input->post('popup_contents', true));
        $this->db->set('popup_image_path', $file['location']);
        $this->db->set('popup_image_name', $file['origin_name']);
        $this->db->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->db->set('popup_status', b'' . $this->input->post('popup_status', true) . '', false);

        $insHistoryArray = array(
            'menu_seq' => '21',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('popup_title', true) . ' 추가',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->db->insert('tbl_popup');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/popup_add');
        } else {
            alert('팝업 등록 되었습니다.', '/index/popup');
        }
    }

    function modPopUp() {
        $file['location'] = $this->input->post('org_popup_image_path', true);
        $file['origin_name'] = $this->input->post('org_popup_image_name', true);

        if (!$file['location']) {
            if (@$_FILES['file']['name']) {

                $this->load->library('upload');

                $url_path = "/popupImg";
                $upload_config = Array(
                    'upload_path' => $_SERVER['DOCUMENT_ROOT'] . $url_path,
                    'allowed_types' => 'gif|jpg|jpeg|png|bmp',
                    'encrypt_name' => TRUE,
                    'max_size' => '512000'
                );
                $this->upload->initialize($upload_config);
                $upfile = $_FILES['file']['name'];
                if (!$this->upload->do_upload('file')) {
                    echo $this->upload->display_errors();
                }
                $info = $this->upload->data();
                $file['location'] = $url_path . "/" . $info['file_name'];
                $file['origin_name'] = $info['orig_name'];
            }
        }

        $this->db->set('popup_title', $this->input->post('popup_title', true));
        $this->db->set('popup_start_day', $this->input->post('popup_start_day', true));
        $this->db->set('popup_end_day', $this->input->post('popup_end_day', true));
        $this->db->set('popup_title', $this->input->post('popup_title', true));
        $this->db->set('popup_contents', $this->input->post('popup_contents', true));
        $this->db->set('popup_image_path', $file['location']);
        $this->db->set('popup_image_name', $file['origin_name']);
        $this->db->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->db->set('popup_status', b'' . $this->input->post('popup_status', true) . '', false);

        $this->db->where('popup_seq', $this->input->post('popup_seq', true));

        $insHistoryArray = array(
            'menu_seq' => '21',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('popup_title', true) . ' 수정',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->db->update('tbl_popup');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/popup_view/' . $this->input->post('popup_seq') . '');
        } else {
            alert('팝업 수정 되었습니다.', '/index/popup');
        }
    }

    function popupLists() {
        $sql = "SELECT
                    popup_seq, 
                    popup_title,
                    DATE_FORMAT(reg_date, '%Y-%m-%d') reg_date, 
                    DATE_FORMAT(popup_start_day, '%Y-%m-%d') popup_start_day, 
                    DATE_FORMAT(popup_end_day, '%Y-%m-%d') popup_end_day, 
                    IF(popup_status = 0, '미노출', '노출') popup_status,
                    NOW() NOW
                FROM 
                    tbl_popup 
                    ORDER BY reg_date DESC";
        $res = $this->Db_m->getList($sql, 'TIMING_CUSTOMER');

        $result = array();
        $i = 1;

        foreach ($res as $row) {

            $date1 = new DateTime($row['NOW']);
            $date2 = new DateTime($row['popup_end_day']);
            $diff = date_diff($date1, $date2);
            $status = '';
            if ($diff->invert == 0) {
                $status = '노출';
            } else {
                $status = '미노출';
            }

            $result[] = array(
                'popup_seq' => $i . '<input type="hidden" class="idx" value="' . $row['popup_seq'] . '">',
                'popup_title' => $row['popup_title'],
                'reg_date' => $row['reg_date'],
                'popup_day' => $row['popup_start_day'] . ' ~ ' . $row['popup_end_day'],
                'popup_end_status' => $status,
                'popup_status' => $row['popup_status']
            );
            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function chk_adminId() {
        $sql = "SELECT
                    admin_id 
                FROM 
                    admin 
                WHERE 
                    admin_id = '" . $this->input->post('id', true) . "'";

        $res = $this->Db_m->getInfo($sql, "TIMING_CUSTOMER");

        if ($res) {
            echo 'DUPLE';
        }
    }

    function insAdmin() {

        $menu_seq = '';

        if ($this->input->post('menu_seq_list')) {
            for ($i = 0; $i < count($this->input->post('menu_seq_list')); $i++) {
                $menu_seq .= $this->input->post('menu_seq_list')[$i] . '|';
            }
        }

        $this->db->set('admin_id', $this->input->post('admin_id', true));
        $this->db->set('admin_pass', hash('sha256', $this->input->post('admin_pass', true)));
        $this->db->set('admin_level', $this->input->post('admin_level', true));
        $this->db->set('admin_name', $this->input->post('admin_name', true));
        $this->db->set('menu_seq_list', substr($menu_seq, 0, -1));
        $this->db->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->db->set('acept', b'' . $this->input->post('acept', true) . '', false);

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->db->insert('admin');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/admin_add');
        } else {
            alert('관리자 등록 되었습니다.', '/index/admin');
        }
    }

    function modAdmin() {

        $menu_seq = '';

        if ($this->input->post('menu_seq_list')) {
            for ($i = 0; $i < count($this->input->post('menu_seq_list')); $i++) {
                $menu_seq .= $this->input->post('menu_seq_list')[$i] . '|';
            }
        }

        $this->TIMING_CUSTOMER->set('admin_id', $this->input->post('admin_id', true));
        if ($this->input->post('admin_pass', true)) {
            $this->TIMING_CUSTOMER->set('admin_pass', hash('sha256', $this->input->post('admin_pass', true)));
        }
        $this->TIMING_CUSTOMER->set('admin_level', $this->input->post('admin_level', true));
        $this->TIMING_CUSTOMER->set('admin_name', $this->input->post('admin_name', true));
        $this->TIMING_CUSTOMER->set('menu_seq_list', substr($menu_seq, 0, -1));
//        $this->TIMING_CUSTOMER->set('reg_date', date("Y-m-d H:i:s", time()));
        $this->TIMING_CUSTOMER->set('acept', b'' . $this->input->post('acept', true) . '', false);

        $this->TIMING_CUSTOMER->where('admin_seq', $this->input->post('admin_seq', true));

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->TIMING_CUSTOMER->update('admin');

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/admin_mod/' . $this->input->post('admin_seq', true) . '');
        } else {
            alert('관리자 수정 되었습니다.', '/index/admin');
        }
    }

    function delAdmin() {
        $sql = "DELETE
                FROM 
                    admin 
                WHERE 
                    admin_seq IN(" . $this->input->post('idx') . ")";

        $this->TIMING_CUSTOMER->trans_start(); // Query will be rolled back

        $this->TIMING_CUSTOMER->query($sql);

        $this->TIMING_CUSTOMER->trans_complete();

        if ($this->TIMING_CUSTOMER->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    //20170814 이승용 페이징 파라미터 필요
    function adminList() {

        if ($this->input->get('page', true)) {
            $page = $this->input->get('page', true);
            //페이지 카운트 예외처리
            if ($page == 0) {
                $page = 1;
            }
        } else { //임시 데이터
            $page = 1;
        }

        if ($this->input->get('page_count', true)) {
            $page_count = $this->input->get('page_count', true);
            //페이지 카운트 예외처리
            if ($page_count == 0) {
                $page_count = 10;
            }
        } else { //임시 데이터
            $page_count = 100;
        }

        $page_start = ($page - 1) * $page_count;

        $sql = "SELECT
                    admin_seq,
                    admin_name,
                    admin_id,
                    admin_pass,
                    IF(admin_level = 'A', '관리자', '운영자') admin_level,
                    IF(acept = 1, '허용', '비허용') acept,
                    DATE_FORMAT(reg_date, '%Y-%m-%d') reg_date
                FROM 
                    admin 
                    ORDER BY admin.reg_date DESC
					LIMIT  $page_start,  $page_count ";

        $res = $this->Db_m->getList($sql, 'TIMING_CUSTOMER');

        $result = array();
        $i = 1;
        foreach ($res as $row) {
            $result[] = array(
                'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['admin_seq'] . '">',
                'admin_seq' => $i,
                'admin_name' => $row['admin_name'],
                'admin_id' => $row['admin_id'],
                'admin_pass' => $row['admin_pass'],
                'admin_level' => $row['admin_level'],
                'acept' => $row['acept'],
                'reg_date' => $row['reg_date']
            );
            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170814 이승용 페이징 파라미터 필요
    function keywordStockLists() {

        $sdate = date('Y-m-d');
        $edate = date('Y-m-d');

        $addWhere = "";

        if ($this->input->get('sdate', true) && $this->input->get('edate', true)) {
            $sdate = $this->input->get('sdate', true);
            $edate = $this->input->get('edate', true);

            $addWhere = "AND B.reg_date >= '" . $sdate . " 00:00:00' AND B.reg_date <= '" . $edate . " 23:59:59' ";
        } else {
            $addWhere = "";
        }

        if ($this->input->get('search', true)['value'] && !$this->input->get('text', true)) {
            $addWhere .= "AND B.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && $this->input->get('search', true)['value']) {
            $addWhere .= "AND B.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && !$this->input->get('search', true)['value']) {
            $addWhere .= "AND B.company_name_i LIKE '%" . $this->input->get('text', true) . "%' ";
        }

        if ($this->input->get('type', true)) {
            $addWhere .= "AND B.kind = '" . $this->input->get('type', true) . "' ";
        }

        /* $sql = "SELECT 
          A.stock_seq,
          A.crp_cd,
          A.company_name_i,
          fx_timbrka.fn_isNewKeywordStockItem(A.stock_seq) AS is_new
          FROM
          timing_news.stock A
          WHERE
          A.stock_status = 1                     #체크조건
          AND A.kind IN ('K','N','Y')  #체크조건
          AND A.stock_seq = IFNULL(null, A.stock_seq) ";
          $sql .= $addWhere;
          $sql .= "ORDER BY 4, A.company_name_i"; */

        $columns = array(
            // datatable column index  => database column name
            0 => 'B.company_name_i',
        );

        $count_sql = "SELECT COUNT(1) CNT
								FROM
								(
									  SELECT  A.stock_seq
										FROM (SELECT @rnum := CASE WHEN A.stock_seq = @stock_seq THEN @rnum + 1 ELSE 1 END AS rnum
												,@stock_seq := A.stock_seq AS stock_seq, A.reg_date
										FROM timing_news.keyword A
										CROSS JOIN (SELECT @stock_seq :=0, @rnum := 0) A
										ORDER BY A.stock_seq, A.reg_date DESC, A.ord
										) A
										INNER JOIN timing_news.stock B ON B.stock_seq = A.stock_seq
										WHERE A.rnum = 1
										  AND EXISTS (SELECT 1 
														  FROM fx_timbrka.fx_stock_item_related_keywords_meta X
														  WHERE X.stock_item_code = B.crp_cd 
															AND X.acted_on > A.reg_date )
								) A
								RIGHT OUTER JOIN timing_news.stock B ON A.stock_seq = B.stock_seq
								WHERE 1 = 1 ";
        $count_sql .= $addWhere;
        $count_sql .= " AND B.stock_status = 1                    
								AND B.kind IN ('K','N','Y') ";

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_NEWS');

        $sql = "SELECT B.stock_seq, B.crp_cd, B.company_name_i, IF(A.stock_seq, 1, 0) AS is_new 
					FROM
					(
						  SELECT  A.stock_seq
							FROM (SELECT @rnum := CASE WHEN A.stock_seq = @stock_seq THEN @rnum + 1 ELSE 1 END AS rnum
									,@stock_seq := A.stock_seq AS stock_seq, A.reg_date
							FROM timing_news.keyword A
							CROSS JOIN (SELECT @stock_seq :=0, @rnum := 0) A
							ORDER BY A.stock_seq, A.reg_date DESC, A.ord
							) A
							INNER JOIN timing_news.stock B ON B.stock_seq = A.stock_seq
							WHERE A.rnum = 1
							  AND EXISTS (SELECT 1 
											  FROM fx_timbrka.fx_stock_item_related_keywords_meta X
											  WHERE X.stock_item_code = B.crp_cd 
												AND X.acted_on > A.reg_date )
					) A
					RIGHT OUTER JOIN timing_news.stock B ON A.stock_seq = B.stock_seq
					WHERE 1 = 1 ";
        $sql .= $addWhere;
        $sql .= " AND B.stock_status = 1                    
					AND B.kind IN ('K','N','Y') ";
        if ($this->input->get('order', true)[0]['column']) {
            $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . " " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $sql .= " ORDER BY is_new DESC, B.company_name_i ";
        }
        $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true);

//        echo $sql;
        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);
        foreach ($res as $row) {
            $result['data'][] = array(
                'company_name_i' => $row['company_name_i'] . '<input type="hidden" class="stock_seq" value="' . $row['stock_seq'] . '">' . '<input type="hidden" class="crp_cd" value="' . $row['crp_cd'] . '">'
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function keywordStockLists2() {

        $add_where = "";

        if ($this->input->get('stock_sql', true)) {
            $add_where .= "AND A.stock_seq = " . $this->input->get('stock_sql', true) . " ";
        }
        $sql = "SELECT A.stock_seq, A.crp_cd, A.company_name_i, A.related_keyword, A.article_cnt, mention_cnt, (@rank := @rank + 1) AS _rank, A.is_new, created_on
                FROM (SELECT A.stock_seq, A.crp_cd, A.company_name_i, B.related_keyword
                            ,fx_timbrka.fn_isNewKeyword(A.stock_seq, ADDTIME(B.cre_date, B.cre_time)) AS is_new
                            ,AVG(C.related_idx) AS related_idx, SUM(C.noam) AS article_cnt, SUM(C.nomiat + C.nomiac) AS mention_cnt
                            , D.created_on -- 데이터가 있으면 미노출된 키워드 
                      FROM timing_news.stock A
                      INNER JOIN fx_timbrka.fx_stock_item_related_keywords B ON B.stock_item_code = CAST(A.crp_cd AS CHAR)
                      INNER JOIN fx_timbrka.fx_stock_item_related_keywords_daily C ON C.stock_item_code = B.stock_item_code AND C.related_keyword = B.related_keyword
                      LEFT JOIN fx_timbrka.fx_stock_item_stopwords D ON D.stock_item_code = B.stock_item_code AND D.stopword = B.related_keyword
                      WHERE A.stock_status = 1  
                        AND A.kind IN ('K','N','Y') ";
        $sql .= $add_where;
        $sql .= " GROUP BY A.crp_cd, A.company_name_i, B.related_keyword, fx_timbrka.fn_isNewKeyword(A.stock_seq, ADDTIME(B.cre_date, B.cre_time))
                ) A
                CROSS JOIN (SELECT @rank := 0) B
                ORDER BY A.related_idx DESC ";

//        echo $sql;

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        foreach ($res as $row) {
            $result[] = array(
                'checkbox' => '<input type="checkbox" class="row_checkbox" value="' . $row['related_keyword'] . '">',
                'related_keyword' => $row['related_keyword'],
                'article_cnt' => $row['article_cnt'],
                'mention_cnt' => $row['mention_cnt'],
                '_rank' => $row['_rank'],
                'created_on' => $row['created_on']
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function keywordExpo() {
        $sql = "DELETE
                FROM 
                    fx_stock_item_stopwords 
                WHERE 
                    stock_item_code = '" . $this->input->post('crp_cd', true) . "' AND 
                    stopword IN(" . $this->input->post('expo_keyword') . ")";

        $select_sql = "SELECT
                        stopword
                       FROM 
                        fx_stock_item_stopwords 
                       WHERE 
                        stock_item_code = '" . $this->input->post('crp_cd', true) . "' AND 
                        stopword IN(" . $this->input->post('expo_keyword') . ")";

        $select_res = $this->Db_m->getList($select_sql, 'FX_TIMIBRKA');

        foreach ($select_res as $row) {
            $insHistoryArray[] = array(
                'menu_seq' => '14',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $row['stopword'] . ' 노출',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->FX_TIMIBRKA->trans_start(); // Query will be rolled back

        $this->FX_TIMIBRKA->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->FX_TIMIBRKA->trans_complete();

        if ($this->FX_TIMIBRKA->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function keywordUnexpo() {
        $exp = explode(',', $this->input->post('unexpo_keyword', true));
        for ($i = 0; $i < count($exp); $i++) {
            $insArray[] = array(
                'stock_item_code' => $this->input->post('crp_cd', true),
                'stopword' => $exp[$i]
            );

            $insHistoryArray[] = array(
                'menu_seq' => '14',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $exp[$i] . ' 미노출',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->FX_TIMIBRKA->trans_start(); // Query will be rolled back

        $this->Db_m->insMultiData('fx_stock_item_stopwords', $insArray, 'FX_TIMIBRKA');
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->FX_TIMIBRKA->trans_complete();

        if ($this->FX_TIMIBRKA->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function insKeyWord() {
        $exp = explode(',', $this->input->post('keyword', true));
        $exp2 = explode(',', $this->input->post('type_data', true));

        $sql = "SELECT
                    stock_seq, 
                    ord, 
                    keyword, 
                    reg_date, 
                    kind, 
                    type 
                FROM 
                    keyword 
                WHERE 
                    stock_seq = '" . $this->input->post('stock_seq', true) . "'";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        if ($res) {
            foreach ($res as $row) {
                $insBackArray[] = array(
                    'stock_seq' => $row['stock_seq'],
                    'ord' => $row['ord'],
                    'keyword' => $row['keyword'],
                    'reg_date' => $row['reg_date'],
                    'kind' => $row['kind'],
                    'type' => $row['type']
                );
            }
            $this->Db_m->insMultiData('keyword_history', $insBackArray, 'TIMING_NEWS');
        }

        $delWhere = array(
            'stock_seq' => $this->input->post('stock_seq', true)
        );

        $this->Db_m->delete('keyword', $delWhere, 'TIMING_NEWS');

        $type = NULL;

        for ($i = 0; $i < count($exp); $i++) {

            if ($exp2[$i] == '0') {
                $type = NULL;
            } else {
                $type = $exp2[$i];
            }

            $insNewArray[] = array(
                'stock_seq' => $this->input->post('stock_seq', true),
                'ord' => $i + 1,
                'keyword' => $exp[$i],
                'kind' => 1,
                'type' => $type
            );
        }

        $this->Db_m->insMultiData('keyword', $insNewArray, 'TIMING_NEWS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function delKeyWord2() {
        $sql = "DELETE
                FROM 
                    keyword 
                WHERE 
                    keyword IN(" . $this->input->post('keyword', true) . ")";

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->query($sql);

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function keywordStockLists4() {
        $sql = "SELECT
                    keyword, 
                    type
                FROM 
                    keyword 
                WHERE 
                    stock_seq = '" . $this->input->get('stock_seq', true) . "'
                    ORDER BY ord ASC";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        $color = "";
        foreach ($res as $row) {

            if ($row['type'] == 1) {
                $color = "key_important";
            } else if ($row['type'] == 2) {
                $color = "key_issue";
            } else {
                $color = "";
            }
            $result[] = array(
                'checkbox' => '<input type="checkbox" class="row_checkbox3 ' . $color . '" value="' . $row['keyword'] . '">',
                'keyword' => $row['keyword'],
                'color' => $color
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170817 이승용 페이징 파라미터 추가
    function stock_keyword() {

        $addWhere = "";

        if ($this->input->get('search', true)['value'] && !$this->input->get('text', true)) {
            $addWhere .= "AND S.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' OR SK.keyword LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && $this->input->get('search', true)['value']) {
            $addWhere .= "AND S.company_name_i LIKE '%" . $this->input->get('search', true)['value'] . "%' OR SK.keyword LIKE '%" . $this->input->get('search', true)['value'] . "%' ";
        }

        if ($this->input->get('text', true) && !$this->input->get('search', true)['value']) {
            $addWhere .= "AND S.company_name_i LIKE '%" . $this->input->get('text', true) . "%' OR SK.keyword LIKE '%" . $this->input->get('text', true) . "%' ";
        }

        if ($this->input->get('sdate', true) && $this->input->get('edate', true)) {
            $addWhere .= "AND DATE_FORMAT(S.reg_date, '%Y-%m-%d') BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "' ";
        }

        $count_sql = "SELECT
                        COUNT(*) CNT
                      FROM
                        stock S
                        LEFT JOIN
                        stock_keyword SK
                        ON S.stock_seq = SK.stock_seq AND SK.keyword_type = 2
                      WHERE 
                        S.stock_seq <> '' ";
        $count_sql .= $addWhere;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_NEWS');

        $columns = array(
            // datatable column index  => database column name
            0 => 'S.company_name_i',
        );

        $sql = "SELECT
                    S.stock_seq,
                    S.company_name_i 
                FROM 
                    stock S
                    LEFT JOIN
                    stock_keyword SK
                    ON S.stock_seq = SK.stock_seq AND SK.keyword_type = 2
                WHERE
                    S.stock_seq <> '' ";
        $sql .= $addWhere;
        if ($this->input->get('order', true)[0]['column'] || $this->input->get('order', true)[0]['column'] == '0') {
            $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . " " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $sql .= " ORDER BY S.stock_seq DESC ";
        }
        $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";

//        echo $sql;

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        foreach ($res as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'company_name_i' => $row['company_name_i'] . '<input type="hidden" class="stock_seq" value="' . $row['stock_seq'] . '">'
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function stock_keyword1() {
        $sql = "SELECT
                    keyword_seq,
                    keyword 
                FROM 
                    stock_keyword 
                WHERE 
                    keyword_type = 1 AND
                    stock_seq = '" . $this->input->get('stock_seq', true) . "'";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        foreach ($res as $row) {
            $exp = explode(',', $row['keyword']);
            for ($i = 0; $i < count($exp); $i++) {
                $result[] = array(
                    'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['keyword_seq'] . '">',
                    'keyword' => $exp[$i]
                );
            }
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function stock_keyword2() {
        $sql = "SELECT
                    keyword_seq,
                    keyword 
                FROM 
                    stock_keyword 
                WHERE 
                    keyword_type = 2 AND
                    stock_seq = '" . $this->input->get('stock_seq', true) . "'";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        foreach ($res as $row) {
            $exp = explode(',', $row['keyword']);
            for ($i = 0; $i < count($exp); $i++) {
                $result[] = array(
                    'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['keyword_seq'] . '">',
                    'keyword' => $exp[$i],
                    'allKeyword' => $row['keyword']
                );
            }
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function addKeyWord() {
        $insArray = array(
            'stock_seq' => $this->input->post('stock_seq', true),
            'keyword' => $this->input->post('value', true),
            'reg_date' => date("Y-m-d H:i:s", time()),
            'keyword_type' => $this->input->post('keyword_type', true)
        );

        if ($this->input->post('keyword_type', true) == '2') {
            $insHistoryArray = array(
                'menu_seq' => '13',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $this->input->post('value', true) . ' 2차 분류어 추가',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        if ($this->input->post('keyword_type', true) == '1') {
            $insHistoryArray = array(
                'menu_seq' => '15',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $this->input->post('value', true) . ' 추가',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->insData('stock_keyword', $insArray, 'TIMING_NEWS');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function delKeyWord() {
        $sql = "DELETE
                FROM 
                    stock_keyword 
                WHERE 
                    keyword_seq IN(" . $this->input->post('idx') . ")";

        $select_sql = "SELECT
                        keyword
                       FROM 
                        stock_keyword 
                       WHERE 
                        keyword_seq IN(" . $this->input->post('idx') . ")";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        if ($this->input->post('keyword_type', true) == '2') {
            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => '13',
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['keyword'] . ' 2차 분류어 삭제',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        }

        if ($this->input->post('keyword_type', true) == '1') {
            foreach ($select_res as $row) {
                $insHistoryArray[] = array(
                    'menu_seq' => '13',
                    'admin_seq' => $this->session->userdata('admin_seq'),
                    'work' => $row['keyword'] . ' 삭제',
                    'reg_date' => date("Y-m-d H:i:s", time())
                );
            }
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function modKeyWord() {
        $updateArray = array(
            'keyword' => $this->input->post('value', true)
        );

        $updateWhere = array(
            'keyword_seq' => $this->input->post('keyword_seq', true)
        );

        $insHistoryArray = array(
            'menu_seq' => '15',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('value', true) . ' 수정',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->update('stock_keyword', $updateArray, $updateWhere, 'TIMING_NEWS');
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    //20170817 이승용 쿼리 수정, 페이징 작업 필요
    function pushLists() {

        $add_where = "";

        //데이터 테이블 기본 검색을 위한 부분입니다. 최상담 검색 기능과는 무관.
        //최상단은 추가로 파라미터 전송 받으셔서 쿼리 추가해서 검색 달으시면 됩니다.
        if ($this->input->get('search', true)['value']) {
//            $add_where .= "AND (SK.crp_cd LIKE '%" . $this->input->get('search', true)['value'] . "%' OR SK.company_name LIKE '%" . $this->input->get('search', true)['value'] . "%')";
        }

        //해당부분 order by부분에 필요합니다. 쿼리에 맞추어 검색하실 컬럼명을 작성해주세요.
        $columns = array(
            // datatable column index  => database column name
            0 => 'A.send_id',
            1 => 'news_kind',
            2 => 'stock_name',
            3 => 'SK.crp_cd', //1차분류어 컬럼쓰세요
            4 => 'BS.description', //2차분류어 컬럼쓰세요
            5 => 'NC.news_title',
            6 => 'A.confirm_dt',
        );

        $count_sql = "SELECT
                            COUNT(*) CNT
                      FROM
                            timing_customer.push_log";
        $count_sql .= $add_where;

        $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

        $sql = "SELECT 
                    A.send_id, 
                    N.news_kind, 
                    CASE N.news_kind 
                        WHEN 1 THEN '공시' 
                        WHEN 2 THEN '지면' 
                        WHEN 3 THEN '온라인뉴스' 
                        WHEN 4 THEN '방송뉴스' 
                    END AS news_kind_nm,
                    S.company_name_i AS stock_name, 
                    NC.news_title, 
                    NC.reg_date, 
                    A.reg_date AS confirm_date
					, IFNULL(CK._keyword, '') AS 'main_keyword'
					, IFNULL(CS.sub_keywords, '') AS 'sub_keyword'
                FROM (SELECT 
                        A.send_id, 
                        A.reg_date, 
                        A.news_seq, 
                        A.stock_seq
						, IFNULL(A.main_keyword_id, '') AS main_keyword_id
                        , IFNULL(A.sub_keywords_id, '') AS sub_keywords_id
                      FROM 
                        timing_customer.push_log A
					";
        if ($this->input->get('order', true)[0]['column']) {
            $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . " " . $this->input->get('order', true)[0]['dir'] . " ";
        } else {
            $sql .= " ORDER BY A.send_id desc ";
        }
        $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "
                    ) A
                INNER JOIN timing_news.news N ON A.news_seq = N.news_seq 
                INNER JOIN timing_news.news_content NC ON A.news_seq = NC.news_seq 
                INNER JOIN timing_news.stock S ON S.stock_seq = A.stock_seq
				LEFT JOIN timing_news.classification_keywords CK ON A.main_keyword_id = CK.id
				LEFT JOIN timing_news.classification_subkeywords CS ON A.main_keyword_id = CS.keyword_id AND A.sub_keywords_id = CS.id
                ORDER BY A.send_id DESC";

        $res = $this->Db_m->getList($sql, 'TIMING_CUSTOMER');

        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        foreach ($res as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'send_id' => $row['send_id'] . '<input type="hidden" class="row_value" value = "' . $row['send_id'] . '">',
                'news_kind' => $row['news_kind_nm'],
                'stock_name' => $row['stock_name'],
                '1stock_name' => $row['main_keyword'],
                '2stock_name' => $row['sub_keyword'],
                'news_title' => $row['news_title'],
                'reg_date' => $row['reg_date'],
                'confirm_date' => $row['confirm_date']
            );
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function menuLists() {
        $sql = "SELECT
                    menu_seq, 
                    menu_name 
                FROM 
                    tbl_menu 
                WHERE 
                    p_menu_seq IS NOT NULL AND
                    p_menu_seq <> 0 AND
                    p_menu_seq <> 22 AND
                    menu_name <> '탈퇴회원' AND
                    menu_name <> '푸쉬관리' AND
                    menu_name <> '관리자계정' AND
                    menu_name <> '뉴스관리(데이터)' AND
                    menu_name <> '작업히스토리'
                ORDER BY menu_seq ASC, sort_seq ASC";

        $res = $this->Db_m->getList($sql, 'TIMING_STATS');

        $result = array();

        $result[] = array(
            'menu_name' => '전체<input type="hidden" class="menu_seq" value="all">'
        );

        foreach ($res as $row) {
            $result[] = array(
                'menu_name' => $row['menu_name'] . '<input type="hidden" class="menu_seq" value="' . $row['menu_seq'] . '">'
            );
        }

        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    //20170817 페이징작업
    function historyLists() {

        $columns = array(
            // datatable column index  => database column name
            0 => 'WH.reg_date',
            1 => 'WH.work',
            2 => 'A.admin_name',
        );

        $add_where = "";

        if ($this->input->get('search', true)['value']) {
            $add_where = "AND WH.work LIKE '%" . $this->input->get('search', true)['value'] . "%'";
        }

        if ($this->input->get('menu_seq', true) == 'all') {

            $count_sql = "SELECT
                            COUNT(*) CNT
                          FROM
                            timing_stats.work_history WH, timing_customer.admin A
                          WHERE 
                            WH.admin_seq = A.admin_seq ";
            $count_sql .= $add_where;

            $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

            $sql = "SELECT
                    WH.reg_date,
                    WH.work,
                    A.admin_name
                FROM 
                    timing_stats.work_history WH, timing_customer.admin A
                WHERE 
                    WH.admin_seq = A.admin_seq ";
            $sql .= $add_where;
            if ($this->input->get('order', true)[0]['column'] || $this->input->get('order', true)[0]['column'] == '0') {
                $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
            } else {
                $sql .= " ORDER BY WH.reg_date DESC ";
            }
            $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";
        } else {

            $count_sql = "SELECT
                            COUNT(*) CNT
                          FROM
                            timing_stats.work_history WH, timing_customer.admin A
                          WHERE 
                            WH.admin_seq = A.admin_seq AND
                            WH.menu_seq = '" . $this->input->get('menu_seq', true) . "' ";
            $count_sql .= $add_where;

            $count_res = $this->Db_m->getInfo($count_sql, 'TIMING_CUSTOMER');

            $sql = "SELECT
                    WH.reg_date,
                    WH.work,
                    A.admin_name
                FROM 
                    timing_stats.work_history WH, timing_customer.admin A
                WHERE 
                    WH.admin_seq = A.admin_seq AND
                    WH.menu_seq = '" . $this->input->get('menu_seq', true) . "' ";
            $sql .= $add_where;
            if ($this->input->get('order', true)[0]['column'] || $this->input->get('order', true)[0]['column'] == '0') {
                $sql .= " ORDER BY " . $columns[$this->input->get('order', true)[0]['column']] . "   " . $this->input->get('order', true)[0]['dir'] . " ";
            } else {
                $sql .= " ORDER BY WH.reg_date DESC ";
            }
            $sql .= "LIMIT " . $this->input->get('start', true) . ", " . $this->input->get('length', true) . "";
        }

        $res = $this->Db_m->getList($sql, 'TIMING_STATS');

        $result = array();

        $result['draw'] = Intval($this->input->get('draw'));
        $result['recordsTotal'] = intval($count_res->CNT);
        $result['recordsFiltered'] = intval($count_res->CNT);

        foreach ($res as $row) {
            $result['data'][] = array(
                'draw' => intval($this->input->get('draw')),
                'recordsTotal' => intval($count_res->CNT),
                'recordsFiltered' => intval($count_res->CNT),
                'reg_date' => $row['reg_date'],
                'work' => $row['work'],
                'admin_name' => $row['admin_name']
            );
        }

        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function excellDown() {
        $this->load->library('PHPExcel');
        if ($this->input->get('file', true) == 'member') {
            if ($this->input->get('range', true) == 'day') {
                $sql = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            CONCAT(
                               DATE_FORMAT(d, '%Y-%m-%d'),
                               CASE DAYOFWEEK(d)
                                  WHEN 1 THEN '(일)'
                                  WHEN 2 THEN '(월)'
                                  WHEN 3 THEN '(화)'
                                  WHEN 4 THEN '(수)'
                                  WHEN 5 THEN '(목)'
                                  WHEN 6 THEN '(금)'
                                  WHEN 7 THEN '(토)'
                               END)
                               DAY_NAME,
                            IFNULL(NEW_CNT, 0) NEW_CNT,
                            IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT,
                            IFNULL(INS_CNT, 0) INS_CNT,
                            IFNULL(SECESSION_CNT, 0) SECESSION_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 1 THEN statistic_value
                                          ELSE 0
                                       END)
                                       NEW_CNT,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 2 THEN statistic_value
                                          ELSE 0
                                       END)
                                       ACCUMULATE_CNT,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 3 THEN statistic_value
                                          ELSE 0
                                       END)
                                       INS_CNT,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 4 THEN statistic_value
                                          ELSE 0
                                       END)
                                       SECESSION_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE 
                        d BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                      ORDER BY d";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = 회원통계_일간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD>날짜</TD>
                                <TD>신규회원 수</TD>                
                                <TD>누적회원 수</TD>
                                <TD>방문회원 수</TD>
                                <TD>탈퇴회원 수</TD>";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <TD style='$date'>$row[DAY_NAME]</TD>
                                <TD style='$number'>$row[NEW_CNT]</TD>
                                <TD style='$number'>$row[ACCUMULATE_CNT]</TD>
                                <TD style='$number'>$row[INS_CNT]</TD>
                                <TD style='$number'>$row[SECESSION_CNT]</TD>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }

            if ($this->input->get('range', true) == 'week') {
                $sql = "SELECT 
                            CONCAT(DATE_FORMAT(sc.statistic_dt,'%Y-%m월 '),FLOOR((DATE_FORMAT(sc.statistic_dt,'%d')+(DATE_FORMAT(DATE_FORMAT(sc.statistic_dt,'%Y%m%01'),'%w')-1))/7)+1, '주차') DAY_NAME,
                            CONCAT(DATE_FORMAT(sc.statistic_dt,'%Y-%m월 '),FLOOR((DATE_FORMAT(sc.statistic_dt,'%d')+(DATE_FORMAT(DATE_FORMAT(sc.statistic_dt,'%Y%m%01'),'%w')-1))/7)+1, '주차') chart_day,
                            SUM(CASE WHEN statistic_kind = 1 THEN statistic_value ELSE 0 END ) NEW_CNT,
                            SUM(CASE WHEN statistic_kind = 2 THEN statistic_value ELSE 0 END ) ACCUMULATE_CNT,
                            SUM(CASE WHEN statistic_kind = 3 THEN statistic_value ELSE 0 END ) INS_CNT,
                            SUM(CASE WHEN statistic_kind = 4 THEN statistic_value ELSE 0 END ) SECESSION_CNT
                        FROM 
                            statistics_customer sc
                        WHERE 
                            sc.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                        GROUP BY chart_day";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = 회원통계_주간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD>날짜</TD>
                                <TD>신규회원 수</TD>                
                                <TD>누적회원 수</TD>
                                <TD>방문회원 수</TD>
                                <TD>탈퇴회원 수</TD>";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <TD style='$date'>$row[DAY_NAME]</TD>
                                <TD style='$number'>$row[NEW_CNT]</TD>
                                <TD style='$number'>$row[ACCUMULATE_CNT]</TD>
                                <TD style='$number'>$row[INS_CNT]</TD>
                                <TD style='$number'>$row[SECESSION_CNT]</TD>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }

            if ($this->input->get('range', true) == 'month') {
                $sql = "SELECT 
                            CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') chart_day,
                            DATE_FORMAT(sc.statistic_dt, '%Y-%m') M,
                            CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                            SUM(CASE WHEN statistic_kind = 1 THEN statistic_value ELSE 0 END ) NEW_CNT,
                            SUM(CASE WHEN statistic_kind = 2 THEN statistic_value ELSE 0 END ) ACCUMULATE_CNT,
                            SUM(CASE WHEN statistic_kind = 3 THEN statistic_value ELSE 0 END ) INS_CNT,
                            SUM(CASE WHEN statistic_kind = 4 THEN statistic_value ELSE 0 END ) SECESSION_CNT
                        FROM 
                            statistics_customer sc
                        WHERE 
                            sc.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                        GROUP BY M";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = 회원통계_월간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD>날짜</TD>
                                <TD>신규회원 수</TD>                
                                <TD>누적회원 수</TD>
                                <TD>방문회원 수</TD>
                                <TD>탈퇴회원 수</TD>";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <TD style='$date'>$row[DAY_NAME]</TD>
                                <TD style='$number'>$row[NEW_CNT]</TD>
                                <TD style='$number'>$row[ACCUMULATE_CNT]</TD>
                                <TD style='$number'>$row[INS_CNT]</TD>
                                <TD style='$number'>$row[SECESSION_CNT]</TD>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }
        }

        if ($this->input->get('file', true) == 'uvpv') {
            if ($this->input->get('range', true) == 'day') {
                $sql = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                                CONCAT(
                                   DATE_FORMAT(d, '%Y-%m-%d'),
                                   CASE DAYOFWEEK(d)
                                      WHEN 1 THEN '(일)'
                                      WHEN 2 THEN '(월)'
                                      WHEN 3 THEN '(화)'
                                      WHEN 4 THEN '(수)'
                                      WHEN 5 THEN '(목)'
                                      WHEN 6 THEN '(금)'
                                      WHEN 7 THEN '(토)'
                                   END)
                                   DAY_NAME,
                                IFNULL(UV_MAIN_KEY_CNT, 0) UV_MAIN_KEY_CNT,
                                IFNULL(UV_MAIN_LIST_CNT, 0) UV_MAIN_LIST_CNT,
                                IFNULL(UV_INTEREST_CNT, 0) UV_INTEREST_CNT,
                                IFNULL(UV_LANK_POPULAR_CNT, 0) UV_LANK_POPULAR_CNT,
                                IFNULL(UV_LANK_POPULAR_KEY_CNT, 0) UV_LANK_POPULAR_KEY_CNT,
                                IFNULL(UV_LANK_POPULAR_NEWS_CNT, 0) UV_LANK_POPULAR_NEWS_CNT,
                                IFNULL(UV_NOTICE_CNT, 0) UV_NOTICE_CNT,
                                IFNULL(UV_SCRAP_CNT, 0) UV_SCRAP_CNT,
                                IFNULL(UV_LOGIN_CNT, 0) UV_LOGIN_CNT,
                                IFNULL(PV_MAIN_KEY_CNT, 0) PV_MAIN_KEY_CNT,
                                IFNULL(PV_MAIN_LIST_CNT, 0) PV_MAIN_LIST_CNT,
                                IFNULL(PV_INTEREST_CNT, 0) PV_INTEREST_CNT,
                                IFNULL(PV_LANK_POPULAR_CNT, 0) PV_LANK_POPULAR_CNT,
                                IFNULL(PV_LANK_POPULAR_KEY_CNT, 0) PV_LANK_POPULAR_KEY_CNT,
                                IFNULL(PV_LANK_POPULAR_NEWS_CNT, 0) PV_LANK_POPULAR_NEWS_CNT,
                                IFNULL(PV_NOTICE_CNT, 0) PV_NOTICE_CNT,
                                IFNULL(PV_SCRAP_CNT, 0) PV_SCRAP_CNT,
                                IFNULL(PV_LOGIN_CNT, 0) PV_LOGIN_CNT
                           FROM timing_stats.date_t
                                LEFT OUTER JOIN
                                (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 1 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_MAIN_KEY_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 2 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_MAIN_LIST_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 3 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_INTEREST_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 4 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_LANK_POPULAR_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 5 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_LANK_POPULAR_KEY_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 6 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_LANK_POPULAR_NEWS_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 7 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_NOTICE_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 8 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_SCRAP_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 9 AND statistic_type = 1
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           UV_LOGIN_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 1 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_MAIN_KEY_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 2 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_MAIN_LIST_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 3 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_INTEREST_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 4 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_LANK_POPULAR_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 5 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_LANK_POPULAR_KEY_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 6 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_LANK_POPULAR_NEWS_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 7 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_NOTICE_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 8 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_SCRAP_CNT,
                                        SUM(
                                           CASE
                                              WHEN statistic_kind = 9 AND statistic_type = 2
                                              THEN
                                                 statistic_value
                                              ELSE
                                                 0
                                           END)
                                           PV_LOGIN_CNT
                                   FROM statistics_customer2 sc2
                                  WHERE sc2.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                                 GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                                   ON sc2.M = date_t.d
                          WHERE 
                            d BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                        ORDER BY d";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = UV/PV통계_일간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD rowspan='2'></TD>
                                <TD colspan='2'>메인<br>(키워드)</TD>                
                                <TD colspan='2'>메인<br>(리스트)/TD>
                                <TD colspan='2'>관심종목</TD>
                                <TD colspan='2'>랭킹<br>(인기종목)</TD>
                                <TD colspan='2'>랭킹<br>(인기키워드)</TD>
                                <TD colspan='2'>랭킹<br>(인기뉴스)</TD>
                                <TD colspan='2'>알림</TD>
                                <TD colspan='2'>스크랩</TD>
                                <TD colspan='2'>로그인</TD>
                            </TR>
                            <TR>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                ";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <td style='$date'>" . $row['DAY_NAME'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LOGIN_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LOGIN_CNT'] . "</td>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }

            if ($this->input->get('range', true) == 'week') {
                $sql = "SELECT 
                            CONCAT(DATE_FORMAT(sc2.statistic_dt,'%Y-%m월 '),FLOOR((DATE_FORMAT(sc2.statistic_dt,'%d')+(DATE_FORMAT(DATE_FORMAT(sc2.statistic_dt,'%Y%m%01'),'%w')-1))/7)+1, '주차') DAY_NAME,
                            CONCAT(DATE_FORMAT(sc2.statistic_dt,'%Y-%m월 '),FLOOR((DATE_FORMAT(sc2.statistic_dt,'%d')+(DATE_FORMAT(DATE_FORMAT(sc2.statistic_dt,'%Y%m%01'),'%w')-1))/7)+1, '주차') chart_day,
                            SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_LIST_CNT,
                            SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_INTEREST_CNT,
                            SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_CNT,
                            SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_NEWS_CNT,
                            SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_NOTICE_CNT,
                            SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_SCRAP_CNT,
                            SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LOGIN_CNT,
                            SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_LIST_CNT,
                            SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_INTEREST_CNT,
                            SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_CNT,
                            SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_NEWS_CNT,
                            SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_NOTICE_CNT,
                            SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_SCRAP_CNT,
                            SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LOGIN_CNT
                        FROM 
                            statistics_customer2 sc2
                        WHERE 
                            sc2.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                        GROUP BY chart_day";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = UV/PV통계_주간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD rowspan='2'></TD>
                                <TD colspan='2'>메인<br>(키워드)</TD>                
                                <TD colspan='2'>메인<br>(리스트)/TD>
                                <TD colspan='2'>관심종목</TD>
                                <TD colspan='2'>랭킹<br>(인기종목)</TD>
                                <TD colspan='2'>랭킹<br>(인기키워드)</TD>
                                <TD colspan='2'>랭킹<br>(인기뉴스)</TD>
                                <TD colspan='2'>알림</TD>
                                <TD colspan='2'>스크랩</TD>
                                <TD colspan='2'>로그인</TD>
                            </TR>
                            <TR>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                ";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <td style='$date'>" . $row['DAY_NAME'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LOGIN_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LOGIN_CNT'] . "</td>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }

            if ($this->input->get('range', true) == 'month') {
                $sql = "SELECT 
                            CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') chart_day,
                            DATE_FORMAT(sc2.statistic_dt, '%Y-%m') M,
                            CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                            SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_LIST_CNT,
                            SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_INTEREST_CNT,
                            SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_CNT,
                            SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_NEWS_CNT,
                            SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_NOTICE_CNT,
                            SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_SCRAP_CNT,
                            SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LOGIN_CNT,
                            SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_LIST_CNT,
                            SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_INTEREST_CNT,
                            SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_CNT,
                            SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_KEY_CNT,
                            SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_NEWS_CNT,
                            SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_NOTICE_CNT,
                            SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_SCRAP_CNT,
                            SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LOGIN_CNT
                        FROM 
                            statistics_customer2 sc2
                        WHERE 
                            sc2.statistic_dt BETWEEN '" . $this->input->get('sdate', true) . "' AND '" . $this->input->get('edate', true) . "'
                        GROUP BY M";

                $res = $this->Db_m->getList($sql, 'TIMING_STATS');
                header("Content-type: application/vnd.ms-excel");
                header("Content-type: application/x-msexcel; charset=utf-8");
                header("Content-Disposition: attachment; filename = UV/PV통계_월간.xls");
                header("Content-Description: PHP4 Generated Data");

                echo " 
                        <meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel;charset=utf-8\"> 
                        <TABLE border='1'>
                            <TR>
                                <TD rowspan='2'></TD>
                                <TD colspan='2'>메인<br>(키워드)</TD>                
                                <TD colspan='2'>메인<br>(리스트)/TD>
                                <TD colspan='2'>관심종목</TD>
                                <TD colspan='2'>랭킹<br>(인기종목)</TD>
                                <TD colspan='2'>랭킹<br>(인기키워드)</TD>
                                <TD colspan='2'>랭킹<br>(인기뉴스)</TD>
                                <TD colspan='2'>알림</TD>
                                <TD colspan='2'>스크랩</TD>
                                <TD colspan='2'>로그인</TD>
                            </TR>
                            <TR>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                <TH>UV</TH>
                                <TH>PV</TH>
                                ";
                $number = 'mso-number-format:"\@";'; //다운로드 서식 숫자로 인식시키기
                $date = 'mso-number-format:"yyyy-mm-dd"'; //다운로드 서식 날짜 변환
                foreach ($res as $row) {
                    echo " 
                            <TR>
                                <td style='$date'>" . $row['DAY_NAME'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_MAIN_LIST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_INTEREST_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_KEY_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LANK_POPULAR_NEWS_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_NOTICE_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_SCRAP_CNT'] . "</td>
                                <td style='$number'>" . $row['UV_LOGIN_CNT'] . "</td>
                                <td style='$number'>" . $row['PV_LOGIN_CNT'] . "</td>
                            </TR>	
                                    ";
                }
                echo "</TR>	
                </TABLE>";
            }
        }
    }

    function chk_dictionary() {
        //keyword 가 있는지 확인
        $sql = "SELECT
                    keyword, 
                    synonym 
                FROM 
                    dictionary 
                WHERE 
                    keyword = '" . $this->input->post('keyword', true) . "'";
        $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        if (!$res) {
            echo 'NO_MATCH';
            exit;
        }

        if ($res->keyword) {
            $this->mod_dictionary($res->keyword, $res->synonym, $this->input->post('synonym', true));
        }
    }

    function ins_dictionary() {

        //새로운단어추가
        $insArray = array(
            'synonym' => $this->input->post('keyword', true) . ',',
            'keyword' => $this->input->post('synonym', true)
        );

        $insHistoryArray = array(
            'menu_seq' => '14',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('synonym', true) . '동의어를 생성하고 ' . $this->input->post('keyword', true) . ' 단어를 연결하였습니다.',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->insData('dictionary', $insArray, 'TIMING_NEWS');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo "FAILED";
            exit;
        } else {
            echo "SUCCESS";
            exit;
        }
    }

    function ins_dictionary2() {
        //새로운단어추가
        $insArray = array(
            'keyword' => $this->input->post('keyword', true),
            'synonym' => $this->input->post('synonym', true) . ','
        );

        $insHistoryArray = array(
            'menu_seq' => '16',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('keyword', true) . ' 추가',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->insData('dictionary', $insArray, 'TIMING_NEWS');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/keyword_add');
            exit;
        } else {
            alert('사전 등록 되었습니다.', '/index/keyword_prev');
            exit;
        }
    }

    function mod_dictionary2() {
        $updateArray = array(
            'keyword' => $this->input->post('keyword', true),
            'synonym' => $this->input->post('synonym', true) . ','
        );

        $updateWhere = array(
            'dict_seq' => $this->input->post('idx', true)
        );

        $insHistoryArray = array(
            'menu_seq' => '16',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => $this->input->post('keyword', true) . ' 수정',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back

        $this->Db_m->update('dictionary', $updateArray, $updateWhere, 'TIMING_NEWS');
        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert('데이터 처리오류!!', '/index/keyword_add');
            exit;
        } else {
            alert('사전 수정 되었습니다.', '/index/keyword_prev');
            exit;
        }
    }

    function chk_keyword() {
        //keyword 가 있는지 확인
        $sql = "SELECT
                    keyword
                FROM 
                    dictionary 
                WHERE 
                    keyword = '" . $this->input->post('keyword', true) . "'";
        $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        if ($res) {
            echo 'DUPLE';
        }
    }

    function mod_dictionary($keyword, $synonym, $input_synonym) {

        $exp = array_map("trim", explode(',', $synonym));

        if (in_array($input_synonym, $exp)) {
            echo 'DUPLE';
            exit;
        } else {
            $updateArray = array(
                'synonym' => $synonym . $input_synonym . ','
            );

            $updateWhere = array(
                'keyword' => $keyword
            );

            $insHistoryArray = array(
                'menu_seq' => '14',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $keyword . '에 ' . $input_synonym . ' 단어를 추가연결 하였습니다.',
                'reg_date' => date("Y-m-d H:i:s", time())
            );

            $this->TIMING_NEWS->trans_start(); // Query will be rolled back

            $this->Db_m->update('dictionary', $updateArray, $updateWhere, 'TIMING_NEWS');
            $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

            $this->TIMING_NEWS->trans_complete();

            if ($this->TIMING_NEWS->trans_status() === FALSE) {
                echo "FAILED";
                exit;
            } else {
                echo "SUCCESS";
                exit;
            }
        }
    }

    function dictionaryLists() {
        $sql = "SELECT
                    dict_seq, 
                    keyword, 
                    synonym 
                FROM 
                    dictionary 
                    ORDER BY dict_seq DESC";

        $res = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $result = array();
        $i = 1;
        foreach ($res as $row) {
            $result[] = array(
                'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['dict_seq'] . '">',
                'num' => $i,
                'keyword' => $row['keyword'],
                'synonym' => $row['synonym']
            );
            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function del_dictionary() {
        $sql = "DELETE
                FROM 
                    dictionary 
                WHERE 
                    dict_seq IN(" . $this->input->post('idxs') . ")";

        $select_sql = "SELECT
                        keyword
                       FROM 
                        dictionary 
                       WHERE 
                        dict_seq IN(" . $this->input->post('idxs') . ")";

        $select_res = $this->Db_m->getList($select_sql, 'TIMING_NEWS');

        foreach ($select_res as $row) {
            $insHistoryArray[] = array(
                'menu_seq' => '16',
                'admin_seq' => $this->session->userdata('admin_seq'),
                'work' => $row['keyword'] . ' 삭제',
                'reg_date' => date("Y-m-d H:i:s", time())
            );
        }

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`

        $this->TIMING_NEWS->query($sql);
        $this->Db_m->insMultiData('work_history', $insHistoryArray, 'TIMING_STATS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function loadExcel() {

        $this->load->library('PHPExcel');

        $UpFile = $_FILES["file"];

        $UpFileName = $UpFile["name"];

//        print_r($UpFile);

        $UpFilePathInfo = pathinfo($UpFileName);
        $UpFileExt = strtolower($UpFilePathInfo["extension"]);

        if ($UpFileExt != "xls" && $UpFileExt != "xlsx") {
            echo "ERR_FORMAT";
            exit;
        }

        //업로드된 엑셀파일을 서버의 지정된 곳에 옮기기 위해 경로 적절히 설정
        $upload_path = $_SERVER["DOCUMENT_ROOT"] . "/upExcelFile/";
        $upfile_path = $upload_path . $UpFileName;


        if (is_uploaded_file($UpFile["tmp_name"])) {

            if (!move_uploaded_file($UpFile["tmp_name"], $upfile_path)) {
                echo "ERR_UPLOAD";
                exit;
            }

//파일 타입 설정 (확자자에 따른 구분)
            $inputFileType = 'Excel2007';
            if ($UpFileExt == "xls") {
                $inputFileType = 'Excel5';
            }

//엑셀리더 초기화
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
            $objReader->setReadDataOnly(true);

//범위 지정(위에 작성한 범위필터 적용)
//            $objReader->setReadFilter($filterSubset);
//업로드된 엑셀 파일 읽기
            $objPHPExcel = $objReader->load($upfile_path);

//첫번째 시트로 고정
            $objPHPExcel->setActiveSheetIndex(0);

//고정된 시트 로드
            $objWorksheet = $objPHPExcel->getActiveSheet();

//시트의 지정된 범위 데이터를 모두 읽어 배열로 저장
            $sheetData_a = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $sheetData_b = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $total_rows = '0';
            $total_rows = count($sheetData_a);
        }
        if ($total_rows >= 1) {
            $data['total_rows'] = $total_rows;
            $data['sheetData_a'] = $sheetData_a;
            $data['sheetData_b'] = $sheetData_b;

            $this->load->view('/excel/excelView', $data);
        } else {
            echo "NO_DATA";
        }
    }

    function loadExcel2() {
        $this->load->library('PHPExcel');

        $UpFile = $_FILES["file"];

        $UpFileName = $UpFile["name"];

//        print_r($UpFile);

        $UpFilePathInfo = pathinfo($UpFileName);
        $UpFileExt = strtolower($UpFilePathInfo["extension"]);

        if ($UpFileExt != "xls" && $UpFileExt != "xlsx") {
            echo "ERR_FORMAT";
            exit;
        }

        //업로드된 엑셀파일을 서버의 지정된 곳에 옮기기 위해 경로 적절히 설정
        $upload_path = $_SERVER["DOCUMENT_ROOT"] . "/upExcelFile/";
        $upfile_path = $upload_path . $UpFileName;


        if (is_uploaded_file($UpFile["tmp_name"])) {

            if (!move_uploaded_file($UpFile["tmp_name"], $upfile_path)) {
                echo "ERR_UPLOAD";
                exit;
            }

//파일 타입 설정 (확자자에 따른 구분)
            $inputFileType = 'Excel2007';
            if ($UpFileExt == "xls") {
                $inputFileType = 'Excel5';
            }

//엑셀리더 초기화
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
            $objReader->setReadDataOnly(true);

//범위 지정(위에 작성한 범위필터 적용)
//            $objReader->setReadFilter($filterSubset);
//업로드된 엑셀 파일 읽기
            $objPHPExcel = $objReader->load($upfile_path);

//첫번째 시트로 고정
            $objPHPExcel->setActiveSheetIndex(0);

//고정된 시트 로드
            $objWorksheet = $objPHPExcel->getActiveSheet();

//시트의 지정된 범위 데이터를 모두 읽어 배열로 저장
            $sheetData_a = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $sheetData_b = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $total_rows = '0';
            $total_rows = count($sheetData_a);
        }
        if ($total_rows >= 1) {
            $data['total_rows'] = $total_rows;
            $data['sheetData_a'] = $sheetData_a;
            $data['sheetData_b'] = $sheetData_b;

            $this->load->view('/excel/excelView2', $data);
        } else {
            echo "NO_DATA";
        }
    }

    function excellDataSave() {

        $insHistoryArray = array(
            'menu_seq' => '16',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => '일괄등록',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`

        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        for ($i = 0; $i < $this->input->post('totalRows', true); $i++) {
            if ($i > 0) {

                $sql = "SELECT
                            keyword, 
                            synonym 
                        FROM 
                            dictionary 
                        WHERE 
                            keyword = '" . $this->input->post('col_A', true)[$i] . "'";

                $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

                if ($res) {
                    $updateArray = array(
                        'synonym' => $this->input->post('col_B', true)[$i]
                    );

                    $updateWhere = array(
                        'keyword' => $res->keyword
                    );

                    $this->Db_m->update('dictionary', $updateArray, $updateWhere, 'TIMING_NEWS');
                } else {

                    $addData[] = array(
                        'keyword' => $this->input->post('col_A', true)[$i],
                        'synonym' => $this->input->post('col_B', true)[$i]
                    );
                }
            }
        }

        if (@$addData) {
//            print_r($addData);
            $this->Db_m->insMultiData('dictionary', $addData, 'TIMING_NEWS');
        }

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function excellDataSave2() {
        $insHistoryArray = array(
            'menu_seq' => '16',
            'admin_seq' => $this->session->userdata('admin_seq'),
            'work' => '일괄등록',
            'reg_date' => date("Y-m-d H:i:s", time())
        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`
//        $this->Db_m->insData('work_history', $insHistoryArray, 'TIMING_STATS');

        for ($i = 0; $i < $this->input->post('totalRows', true); $i++) {
            if ($i > 1) {
                $addData[] = array(
                    'stock_seq' => '-1',
                    'stopkeywords' => $this->input->post('col_B', true)[$i]
                );
            }
        }

        if (@$addData) {
//            print_r($addData);
            $this->Db_m->insMultiData('classification_stopkeywords', $addData, 'TIMING_NEWS');
        }

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

    function stockList() {
        $stock_sql = "SELECT
                        stock_seq,
                        company_name_i
                      FROM 
                        stock
                      WHERE 
                        kind = '" . $this->input->post('kind', true) . "'";

        $stock_res = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');

        $stock_result = [];

        foreach ($stock_res as $row) {
            $stock_result[] = [
                'id' => $row['stock_seq'],
                'text' => $row['company_name_i']
            ];
        }

        print_r(json_encode($stock_result));
    }

    function getStockCode() {
        $stock_sql = "SELECT
                        crp_cd
                      FROM 
                        stock
                      WHERE 
                        stock_seq = '" . $this->input->post('stock_seq', true) . "'";

        $stock_res = $this->Db_m->getInfo($stock_sql, 'TIMING_NEWS');

        echo $stock_res->crp_cd;
    }

    function chk_exclude() {
        $sql = "SELECT
                    COUNT(*) CNT 
                FROM 
                    classification_stopkeywords 
                WHERE 
                    stock_seq = '" . $this->input->post('stock_seq', true) . "'";
        $res = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        if ($res->CNT > 0) {
            echo 'DUPLE';
        }
    }

    function insExclude() {

        if ($this->input->post('kind', true) == 'ALL') {
            $stock_seq = '-1';
        } else {
            $stock_seq = $this->input->post('stock_seq', true);
        }

        $ins_sqp = "CALL classification_stopkeywords_ins('$stock_seq', '" . $this->input->post('stopkeywords', true) . "', @errno, @text)";

//        $insArray = array(
//            'stock_seq' => $stock_seq,
//            'stopkeywords' => $this->input->post('stopkeywords', true)
//        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`
//        $this->Db_m->insData('classification_stopkeywords', $insArray, 'TIMING_NEWS');

        $this->TIMING_NEWS->query($ins_sqp);

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert("데이터 처리오류!!", '/index/keyword_exclude');
        } else {
            alert("배제어 등록 되었습니다.", '/index/keyword_exclude');
        }
    }

    function modExclude() {
        if ($this->input->post('kind', true) == 'ALL') {
            $stock_seq = '-1';
        } else {
            $stock_seq = $this->input->post('stock_seq', true);
        }

        $update_sp = "CALL classification_stopkeywords_upd('" . $this->input->post('idx', true) . "', '" . $this->input->post('stopkeywords', true) . "', @errno, @text)";

//        $updateArray = array(
//            'stock_seq' => $stock_seq,
//            'stopkeywords' => $this->input->post('stopkeywords', true)
//        );
//
//        $updateWhere = array(
//            'id' => $this->input->post('idx', true)
//        );

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`

        $this->TIMING_NEWS->query($update_sp);
//        $this->Db_m->update('classification_stopkeywords', $updateArray, $updateWhere, 'TIMING_NEWS');

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            alert("데이터 처리오류!!", '/index/keyword_exclude');
        } else {
            alert("배제어 수정 되었습니다.", '/index/keyword_exclude');
        }
    }

    function keyword_exclude() {

        $add_where = "";

        if ($this->input->get('kind', true)) {
            if ($this->input->get('kind', true) != 'all') {
                $add_where .= "AND S.kind = '" . $this->input->get('kind', true) . "' ";

                if ($this->input->get('text', true)) {

                    if ($this->input->get('search_type', true) == 'all') {
                        $add_where .= "AND (S.company_name_i LIKE '%" . $this->input->get('text') . "%' OR CS.stopkeywords LIKE '%" . $this->input->get('text') . "%')";
                    }

                    if ($this->input->get('search_type', true) == 'company_name_i') {
                        $add_where .= "AND S.company_name_i LIKE '%" . $this->input->get('text') . "%'";
                    }

                    if ($this->input->get('search_type', true) == 'stopkeywords') {
                        $add_where .= "AND CS.stopkeywords LIKE '%" . $this->input->get('text') . "%'";
                    }
                }
            } else if ($this->input->get('kind', true) == 'all') {
                if ($this->input->get('text', true)) {

                    if ($this->input->get('search_type', true) == 'all') {
                        $add_where .= "AND (S.company_name_i LIKE '%" . $this->input->get('text') . "%' OR CS.stopkeywords LIKE '%" . $this->input->get('text') . "%')";
                    }

                    if ($this->input->get('search_type', true) == 'company_name_i') {
                        $add_where .= "AND S.company_name_i LIKE '%" . $this->input->get('text') . "%'";
                    }

                    if ($this->input->get('search_type', true) == 'stopkeywords') {
                        $add_where .= "AND CS.stopkeywords LIKE '%" . $this->input->get('text') . "%'";
                    }
                }
            }
        }

        if ($this->input->get('page', true)) {
            $page = $this->input->get('page', true);
            //페이지 카운트 예외처리
            if ($page == 0) {
                $page = 1;
            }
        } else { //임시 데이터
            $page = 1;
        }

        if ($this->input->get('page_count', true)) {
            $page_count = $this->input->get('page_count', true);
            //페이지 카운트 예외처리
            if ($page_count == 0) {
                $page_count = 10;
            }
        } else { //임시 데이터
            $page_count = 100;
        }

        $page_start = ($page - 1) * $page_count;

        $stock_sql = "SELECT
                            CS.id,
                            IF(CS.stock_seq = '-1', 
                                '전체', 
                                CASE S.kind
                                    WHEN 'Y' THEN '코스피'
                                    WHEN 'K' THEN '코스닥'
                                    WHEN 'N' THEN '코넥스'
                                END
                            ) kind,
                            S.company_name_i, 
                            CS.stopkeywords
                      FROM
                            classification_stopkeywords CS
                            LEFT JOIN stock S
                            ON CS.stock_seq = S.stock_seq
                      WHERE 
                            CS.id <> '' ";
        $stock_sql .= $add_where;
        $stock_sql .= " ORDER BY CS.id DESC
                        LIMIT $page_start, $page_count ";

        $stock_lists = $this->Db_m->getList($stock_sql, 'TIMING_NEWS');
        $result = array();
        $i = 1;
        foreach ($stock_lists as $row) {
            $result[] = array(
                'checkbox' => '<input type="checkbox" class="row_check" value="' . $row['id'] . '">',
                'num' => $i,
                'kind' => $row['kind'],
                'company_name_i' => $row['company_name_i'],
                'stopkeywords' => $row['stopkeywords']
            );
            $i++;
        }
        $gropData['data'] = $result;
        print_r(json_encode($gropData, JSON_UNESCAPED_UNICODE));
    }

    function del_keyword_exclude() {

        $this->TIMING_NEWS->trans_start(); // Query will be rolled back`

        $exp_idx = explode(',', $this->input->post('idxs'));

        for ($i = 0; $i < count($exp_idx); $i++) {
            $del_sp = "CALL classification_stopkeywords_del('$exp_idx[$i]', @errno, @text)";

            $this->TIMING_NEWS->query($del_sp);
        }

//        $sql = "DELETE
//                FROM 
//                    classification_stopkeywords 
//                WHERE 
//                    id IN(" . $this->input->post('idxs') . ")";
//        $this->TIMING_NEWS->query($sql);

        $this->TIMING_NEWS->trans_complete();

        if ($this->TIMING_NEWS->trans_status() === FALSE) {
            echo 'FAILED';
        } else {
            echo 'SUCCESS';
        }
    }

}
