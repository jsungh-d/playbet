<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->TIMING_CUSTOMER = $this->load->database('TIMING_CUSTOMER', TRUE);
        $this->TIMING_NEWS = $this->load->database('TIMING_NEWS', TRUE);
        $this->TIMING_STATS = $this->load->database('TIMING_STATS', TRUE);

        $this->load->helper(array('url', 'date', 'form', 'alert'));
        $this->load->model('Db_m');
        $this->load->library('session');
    }

    function _remap($method) {

        if ($this->uri->segment(2)) {

            if (!$this->session->userdata('admin_seq')) {
                alert('로그인 해주세요.', '/');
                exit;
            }

            $my_menu_sql = "SELECT
                                menu_seq_list 
                            FROM 
                                admin 
                            WHERE 
                                admin_seq = '" . $this->session->userdata('admin_seq') . "'";

            $data['my_menu_info'] = $this->Db_m->getInfo($my_menu_sql, 'TIMING_CUSTOMER');

            $this->load->view('inc/header', $data);

            if (method_exists($this, $method)) {
                $this->{"{$method}"}();
            }

            $this->load->view('inc/footer');
        } else {

            $this->cookie_id = $this->input->cookie('do_user_id');
            if ($this->cookie_id && !$this->session->userdata('admin_seq')) {

                $id = $this->TIMING_CUSTOMER->escape($this->cookie_id);

                $sql = "SELECT
                            admin_seq,
                            admin_id,
                            admin_level,
                            admin_name
                        FROM 
                            admin 
                        WHERE
                            admin_id = $id AND
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
                }

                alert('자동 로그인 되었습니다.', '/index/main');
            }

            if ($this->session->userdata('admin_seq') && $this->cookie_id) {
                echo '<script type="text/javascript">location.href="/index/main"</script>';
                exit;
            }
            if (method_exists($this, $method)) {
                $this->{"{$method}"}();
            }
        }
    }

    function index() {
        $this->load->view('login');
    }

    function main() {

        $pv_sql = "SELECT 
                       IFNULL(SUM(statistic_value), 0) value
                   FROM 
                       statistics_customer2 sc2
                   WHERE
                       statistic_type = 2 AND
                       DATE_FORMAT(statistic_dt, '%Y%m%d') = DATE_FORMAT(now(), '%Y%m%d')";

        $data['pv_value'] = $this->Db_m->getInfo($pv_sql, 'TIMING_STATS');

        $uv_sql = "SELECT 
                       IFNULL(SUM(statistic_value), 0) value
                   FROM 
                       statistics_customer2 sc2
                   WHERE
                       statistic_type = 1 AND
                       DATE_FORMAT(statistic_dt, '%Y%m%d') = DATE_FORMAT(now(), '%Y%m%d')";

        $data['uv_value'] = $this->Db_m->getInfo($uv_sql, 'TIMING_STATS');

        $user_all_sql = "SELECT
                            COUNT(*) CNT 
                         FROM 
                            customer";

        $data['user_all'] = $this->Db_m->getInfo($user_all_sql, 'TIMING_CUSTOMER');

        $user_today_sql = "SELECT
                              COUNT(*) CNT 
                           FROM 
                              customer
                           WHERE
                              DATE_FORMAT(reg_date, '%Y%m%d') = DATE_FORMAT(now(), '%Y%m%d')";

        $data['user_today'] = $this->Db_m->getInfo($user_today_sql, 'TIMING_CUSTOMER');

        $user_drop_sql = "SELECT 
                            COUNT(*) CNT 
                          FROM 
                            customer_withdraw_history 
                          WHERE
                            DATE_FORMAT(reg_date, '%Y%m%d') = DATE_FORMAT(now(), '%Y%m%d')";

        $data['user_drop'] = $this->Db_m->getInfo($user_drop_sql, 'TIMING_CUSTOMER');

        $data['selectDay'] = date("Y-m-d", strtotime("now"));
        $sdate = date("Y-m-d", strtotime("-6 day"));
        $edate = date("Y-m-d", strtotime("now"));

        if ($this->uri->segment(3)) {
            $data['selectDay'] = $this->uri->segment(3);
            $sdate = date("Y-m-d", strtotime("" . $data['selectDay'] . " -6 day"));
            $edate = $this->uri->segment(3);
        }

        $date_sql = "SELECT 
                        CONCAT(d,
                        CASE 
                            DAYOFWEEK(d) 
                            WHEN 1     THEN '(일)' 
                            WHEN 2     THEN '(월)' 
                            WHEN 3     THEN '(화)' 
                            WHEN 4     THEN '(수)' 
                            WHEN 5     THEN '(목)' 
                            WHEN 6     THEN '(금)' 
                            WHEN 7     THEN '(토)' 
                          END
                        ) d
                     FROM 
                        date_t 
                     WHERE 
                        d BETWEEN '$sdate' AND '$edate'
                        ORDER BY d";

        $data['date_lists'] = $this->Db_m->getList($date_sql, 'TIMING_STATS');

        $pv_graph_sql = "SELECT 
                            DATE_FORMAT(d, '%Y-%m-%d') d,
                            IFNULL(CNT, 0) CNT
                          FROM 
                            timing_stats.date_t LEFT OUTER JOIN (
                              SELECT 
                                SUM(statistic_value) CNT,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M
                              FROM 
                                timing_stats.statistics_customer2 sc2
                             WHERE
                                statistic_type = 2
                                GROUP BY DATE_FORMAT(statistic_dt, '%Y-%m-%d')
                            ) sc2 ON sc2.M = timing_stats.date_t.d
                          WHERE 
                            d BETWEEN '$sdate' AND '$edate'
                            ORDER BY d";

        $data['pv_graph'] = $this->Db_m->getList($pv_graph_sql, 'TIMING_CUSTOMER');

        $uv_graph_sql = "SELECT 
                            DATE_FORMAT(d, '%Y-%m-%d') d,
                            IFNULL(CNT, 0) CNT
                          FROM 
                            timing_stats.date_t LEFT OUTER JOIN (
                              SELECT 
                                SUM(statistic_value) CNT,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M
                              FROM 
                                timing_stats.statistics_customer2 sc2
                             WHERE
                                statistic_type = 1
                                GROUP BY DATE_FORMAT(statistic_dt, '%Y-%m-%d')
                            ) sc2 ON sc2.M = timing_stats.date_t.d
                          WHERE 
                            d BETWEEN '$sdate' AND '$edate'
                            ORDER BY d";

        $data['uv_graph'] = $this->Db_m->getList($uv_graph_sql, 'TIMING_CUSTOMER');

        $user_graph_sql = "SELECT 
                             DATE_FORMAT(d, '%Y-%m-%d') d,
                             IFNULL(CNT, 0) CNT
                           FROM 
                             timing_stats.date_t LEFT OUTER JOIN (
                               SELECT 
                                 COUNT(*) CNT,
                                 DATE_FORMAT(reg_date, '%Y-%m-%d') M
                               FROM 
                                 customer
                                 GROUP BY DATE_FORMAT(reg_date, '%Y-%m-%d')
                             ) C ON C.M = timing_stats.date_t.d
                           WHERE 
                             d BETWEEN '$sdate' AND '$edate'
                             ORDER BY d";

        $data['user_graph'] = $this->Db_m->getList($user_graph_sql, 'TIMING_CUSTOMER');

        $user_drop_graph_sql = "SELECT 
                                  DATE_FORMAT(d, '%Y-%m-%d') d,
                                  IFNULL(CNT, 0) CNT
                                FROM 
                                  timing_stats.date_t LEFT OUTER JOIN (
                                    SELECT 
                                      COUNT(*) CNT,
                                      DATE_FORMAT(reg_date, '%Y-%m-%d') M
                                    FROM 
                                      customer_withdraw_history 
                                      GROUP BY DATE_FORMAT(reg_date, '%Y-%m-%d')
                                  ) CW ON CW.M = timing_stats.date_t.d
                                WHERE 
                                  d BETWEEN '$sdate' AND '$edate'
                                  ORDER BY d";

//        echo $user_drop_graph_sql;

        $data['user_drop_graph'] = $this->Db_m->getList($user_drop_graph_sql, 'TIMING_CUSTOMER');

        $this->load->view('index', $data);
    }

    function user_list() {

        $this->load->view('sub/member/user_list');
    }

    function user_drop() {

        $this->load->view('sub/member/user_drop');
    }

    function business() {

        $this->load->view('sub/contents/business');
    }

    function option() {
        $this->load->view('sub/contents/option');
    }

    function option_add() {

        $sql = "SELECT
                    business_seq, 
                    description 
                FROM 
                    business
                    ORDER BY description ASC";

        $data['lists'] = $this->Db_m->getList($sql, 'TIMING_NEWS');
        $this->load->view('sub/contents/option_add', $data);
    }

    function option_mod() {

        $sql = "SELECT
                    business_seq, 
                    description 
                FROM 
                    business
                    ORDER BY description ASC";

        $data['lists'] = $this->Db_m->getList($sql, 'TIMING_NEWS');

        $info_sql = "SELECT
                        s.stock_seq, 
                        s.company_name, 
                        s.company_name_e,
                        s.company_name_i,
                        s.business_seq,
                        s.kind,
                        s.crp_cd,
                        s.crp_no,
                        s.bsn_no,
                        s.stock_status,
                        sk.keyword,
                        sk.keyword_seq
                     FROM 
                        stock s
                        LEFT JOIN stock_keyword sk
                        on s.stock_seq = sk.stock_seq
                     WHERE 
                        s.stock_seq = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($info_sql, 'TIMING_NEWS');

        $this->load->view('sub/contents/option_mod', $data);
    }

    function news_object() {

        $this->load->view('sub/contents/news_object');
    }

    function news_disclosure() {
        $this->load->view('sub/contents/news_disclosure');
    }

    function news_online() {
        $this->load->view('sub/contents/news_online');
    }

    function news_article() {
        $this->load->view('sub/contents/news_article');
    }

    function news_broadcast() {
        $this->load->view('sub/contents/news_broadcast');
    }

    function keyword_all() {
        $this->load->view('sub/contents/keyword_all');
    }

    function keyword_kospi() {
        $this->load->view('sub/contents/keyword_kospi');
    }

    function keyword_konex() {
        $this->load->view('sub/contents/keyword_konex');
    }

    function keyword_synonym() {
        $this->load->view('sub/contents/keyword_synonym');
    }

    function keyword_prev() {
        $this->load->view('sub/contents/keyword_prev');
    }

    function keyword_add() {
        $this->load->view('sub/contents/keyword_add');
    }

    function keyword_mod() {

        $sql = "SELECT 
                    keyword, 
                    synonym 
                FROM 
                    dictionary 
                WHERE 
                    dict_seq = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($sql, 'TIMING_NEWS');

        $this->load->view('sub/contents/keyword_mod', $data);
    }

    function push() {
        $this->load->view('sub/contents/push');
    }

    function push_detail() {

        $sql = "SELECT 
                    P.send_id , 
                    N.news_kind 
                    , CASE N.news_kind WHEN 1 THEN '공시' WHEN 2 THEN '지면' WHEN 3 THEN '온라인뉴스' WHEN 4 THEN '방송뉴스' END AS news_kind_nm-- 1 공시, 2 
                    , GROUP_CONCAT( S.company_name_i ) AS stock_name -- 종목명
                    , ( SELECT media_name FROM timing_news.media WHERE media_seq = N.media_seq ) media_name
                    , NC.news_title -- 제목
                    , NC.news_contents -- 내용 
                    , NC.reg_date -- 뉴스/공시 등록시점
                    , ( SELECT confirm_date FROM timing_customer.push_message_send_log WHERE send_id = P.send_id ) AS confirm_date -- 전송시간
                FROM 
                    timing_customer.push_log P
                    JOIN timing_news.news N ON P.news_seq = N.news_seq 
                    JOIN timing_news.news_content NC ON P.news_seq = NC.news_seq 
                    JOIN timing_news.stock S ON S.stock_seq = P.stock_seq 
                WHERE 
                    P.send_id = " . $this->uri->segment(3) . "";

        $data['info'] = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        $this->load->view('sub/contents/push_detail', $data);
    }

    function notice() {
        $this->load->view('sub/app/notice');
    }

    function notice_view() {

        $sql = "SELECT
                    notice_seq, 
                    notice_status,
                    is_top,
                    push_type,
                    is_toast,
                    title,
                    contents,
                    push_date,
                    DATE_FORMAT(reg_date, '%Y.%m.%d') INS_DATE
                FROM 
                    tbl_notice_board 
                WHERE 
                    notice_seq = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        $this->load->view('sub/app/notice_view', $data);
    }

    function notice_add() {
        $this->load->view('sub/app/notice_add');
    }

    function question() {
        $this->load->view('sub/app/question');
    }

    function popup() {
        $this->load->view('sub/app/popup');
    }

    function popup_add() {
        $this->load->view('sub/app/popup_add');
    }

    function popup_view() {
        $sql = "SELECT
                    popup_seq, 
                    popup_title, 
                    popup_contents, 
                    popup_image_path, 
                    popup_image_name,
                    popup_start_day, 
                    popup_end_day,
                    popup_status
                FROM 
                    tbl_popup 
                WHERE 
                    popup_seq = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');
        $this->load->view('sub/app/popup_view', $data);
    }

    function member() {

        $data['sdate'] = date("Y-m-d", strtotime("-1 week"));
        $data['edate'] = date("Y-m-d", strtotime("-1 day"));
        $data['range'] = 'day';

        $sdate = date("Y-m-d", strtotime("-1 week"));
        $edate = date("Y-m-d", strtotime("-1 day"));

        if (!$this->uri->segment(5) || $this->uri->segment(5) == 'day') {

            $date_where = "d BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";
            $date_where2 = "d BETWEEN '" . date("Y-m-d", strtotime("$edate -6 day")) . "' AND '" . $edate . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {
                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);

                $sdate = $this->uri->segment(3);
                $edate = $this->uri->segment(4);

                $diff_sdate = new DateTime($sdate);
                $diff_edate = new DateTime($edate);

                $diff = date_diff($diff_sdate, $diff_edate);

                $date_where = "d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                if ($diff->days >= 7) {
                    $date_where2 = "d BETWEEN '" . date("Y-m-d", strtotime("$edate -6 day")) . "' AND '" . $edate . "' ";
                } else if ($diff->days < 7) {
                    $date_where2 = "d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }
            }

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
                              WHERE sc.statistic_dt BETWEEN '" . $data['sdate'] . "' AND '" . $data['edate'] . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql .= $date_where;
            $sql .= "ORDER BY d";

            $sql2 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(NEW_CNT, 0) NEW_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 1 THEN statistic_value
                                          ELSE 0
                                       END)
                                       NEW_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "ORDER BY d";

            //누적회원
            $sql3 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 2 THEN statistic_value
                                          ELSE 0
                                       END)
                                       ACCUMULATE_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "ORDER BY d";

            //방문회원
            $sql4 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(INS_CNT, 0) INS_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 3 THEN statistic_value
                                          ELSE 0
                                       END)
                                       INS_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "ORDER BY d";

            //탈퇴회원
            $sql5 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(SECESSION_CNT, 0) SECESSION_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 4 THEN statistic_value
                                          ELSE 0
                                       END)
                                       SECESSION_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc.statistic_dt, sc.statistic_kind) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "ORDER BY d";
//            echo $sql2;
        }

        if ($this->uri->segment(5) == 'week') {

            //오늘 날짜 출력 ex) 2013-04-10
            $today_date = date('Y-m-d');
            //오늘의 요일 출력 ex) 수요일 = 3 
            $day_of_the_week = date('w');
            //오늘의 첫째주인 날짜 출력 ex) 2013-04-07 (일요일임) 
            $a_week_ago = date('Y-m-d', strtotime($today_date . " -" . $day_of_the_week . "days"));

            $date_where = "sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$a_week_ago -4 week")) . "' AND '" . $a_week_ago . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {
                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);
                $sdate = $this->uri->segment(3);
                $edate = date("Y-m-d", strtotime("" . $this->uri->segment(4) . " +2 day"));

                $diff_sdate = new DateTime($data['sdate']);
                $diff_edate = new DateTime($data['edate']);

                $diff = date_diff($diff_sdate, $diff_edate);

                $date_where = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                if ($diff->days >= 28) {
                    $date_where2 = "date_t.d BETWEEN '" . date("Y-m-d", strtotime("$edate -27 day")) . "' AND '" . date("Y-m-d", strtotime("$edate -2 day")) . "' ";
                } else if ($diff->days < 28) {
                    $date_where2 = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }

//                echo $date_where2;
            }

            $sql = "SELECT CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                            DAY_NAME,
                         IFNULL(NEW_CNT, 0) NEW_CNT,
                         IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT,
                         IFNULL(INS_CNT, 0) INS_CNT,
                         IFNULL(SECESSION_CNT, 0) SECESSION_CNT,
                         ADDDATE(date_t.d, -WEEKDAY(date_t.d)) AS MONDAY,
                         ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6) AS SUNDAY
                    FROM timing_stats.date_t
                         LEFT OUTER JOIN
                         (SELECT 
                                 DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                 DATE_FORMAT(
                                    ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt) + 6),
                                    '%y/%m/%d')
                                    chart_day,
                                 ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt)) AS MONDAY,
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
                           WHERE sc.statistic_dt BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "'
                          GROUP BY MONDAY) sc
                            ON sc.M = date_t.d 
                          WHERE ";
            $sql .= $date_where;
            $sql .= "GROUP BY DAY_NAME
                     ORDER BY d";
//            echo $sql;
            $data['range'] = 'week';

            $sql2 = "SELECT 
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                            DAY_NAME,
                         DATE_FORMAT(ADDDATE(date_t.d, - WEEKDAY(date_t.d) + 6 ), '%y/%m/%d') chart_day,
                         IFNULL(NEW_CNT, 0) NEW_CNT
                    FROM timing_stats.date_t
                         LEFT OUTER JOIN
                         (SELECT 
                                 DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                 DATE_FORMAT(
                                    ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt) + 6),
                                    '%y/%m/%d')
                                    chart_day,
                                 ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt)) AS MONDAY,
                                 SUM(
                                    CASE
                                       WHEN statistic_kind = 1 THEN statistic_value
                                       ELSE 0
                                    END)
                                    NEW_CNT
                            FROM statistics_customer sc
                           WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                          GROUP BY MONDAY) sc
                            ON sc.M = date_t.d 
                          WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //누적회원수
            $sql3 = "SELECT 
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                            DAY_NAME,
                         DATE_FORMAT(ADDDATE(date_t.d, - WEEKDAY(date_t.d) + 6 ), '%y/%m/%d') chart_day,
                         IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT
                    FROM timing_stats.date_t
                         LEFT OUTER JOIN
                         (SELECT 
                                 DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                 DATE_FORMAT(
                                    ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt) + 6),
                                    '%y/%m/%d')
                                    chart_day,
                                 ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt)) AS MONDAY,
                                 SUM(
                                    CASE
                                       WHEN statistic_kind = 2 THEN statistic_value
                                       ELSE 0
                                    END)
                                    ACCUMULATE_CNT
                            FROM statistics_customer sc
                           WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                          GROUP BY MONDAY) sc
                            ON sc.M = date_t.d 
                          WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //방문회원수
            $sql4 = "SELECT 
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                            DAY_NAME,
                         DATE_FORMAT(ADDDATE(date_t.d, - WEEKDAY(date_t.d) + 6 ), '%y/%m/%d') chart_day,
                         IFNULL(INS_CNT, 0) INS_CNT
                    FROM timing_stats.date_t
                         LEFT OUTER JOIN
                         (SELECT 
                                 DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                 DATE_FORMAT(
                                    ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt) + 6),
                                    '%y/%m/%d')
                                    chart_day,
                                 ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt)) AS MONDAY,
                                 SUM(
                                    CASE
                                       WHEN statistic_kind = 3 THEN statistic_value
                                       ELSE 0
                                    END)
                                    INS_CNT
                            FROM statistics_customer sc
                           WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                          GROUP BY MONDAY) sc
                            ON sc.M = date_t.d 
                          WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //탙퇴회원수
            $sql5 = "SELECT 
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                            DAY_NAME,
                         DATE_FORMAT(ADDDATE(date_t.d, - WEEKDAY(date_t.d) + 6 ), '%y/%m/%d') chart_day,
                         IFNULL(SECESSION_CNT, 0) SECESSION_CNT
                    FROM timing_stats.date_t
                         LEFT OUTER JOIN
                         (SELECT 
                                 DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                 DATE_FORMAT(
                                    ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt) + 6),
                                    '%y/%m/%d')
                                    chart_day,
                                 ADDDATE(sc.statistic_dt, -WEEKDAY(sc.statistic_dt)) AS MONDAY,
                                 SUM(
                                    CASE
                                       WHEN statistic_kind = 4 THEN statistic_value
                                       ELSE 0
                                    END)
                                    SECESSION_CNT
                            FROM statistics_customer sc
                           WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                          GROUP BY MONDAY) sc
                            ON sc.M = date_t.d 
                          WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "GROUP BY DAY_NAME
                      ORDER BY d";

//            echo $sql2;
        }

        if ($this->uri->segment(5) == 'month') {
            $date_where = "sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {
                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);
                $sdate = $this->uri->segment(3);
                $edate = $this->uri->segment(4);

                $diff_sdate_y = substr($data['sdate'], 0, -6);
                $diff_sdate_m = substr($data['sdate'], 5, -3);
                $diff_edate_y = substr($data['edate'], 0, -6);
                $diff_edate_m = substr($data['edate'], 5, -3);

                $dist = ($diff_edate_y - $diff_sdate_y) * 12 + ($diff_edate_m - $diff_sdate_m);

                $date_where = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";

                if ($dist >= 6) {
                    $date_where2 = "date_t.d BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "' ";
                } else if ($dist < 6) {
                    $date_where2 = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }
            }

            $sql = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%Y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(NEW_CNT, 0) NEW_CNT,
                            IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT,
                            IFNULL(INS_CNT, 0) INS_CNT,
                            IFNULL(SECESSION_CNT, 0) SECESSION_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT CONCAT(DATE_FORMAT(sc.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
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
                              WHERE sc.statistic_dt BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "'
                             GROUP BY chart_day) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql .= $date_where;
            $sql .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                     ORDER BY d";

            $sql2 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(NEW_CNT, 0) NEW_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT CONCAT(DATE_FORMAT(sc.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 1 THEN statistic_value
                                          ELSE 0
                                       END)
                                       NEW_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "'
                             GROUP BY chart_day) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

//            echo $sql2;
            //누적회원수
            $sql3 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(ACCUMULATE_CNT, 0) ACCUMULATE_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT CONCAT(DATE_FORMAT(sc.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 2 THEN statistic_value
                                          ELSE 0
                                       END)
                                       ACCUMULATE_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "'
                             GROUP BY chart_day) sc
                               ON sc.M = date_t.d
                      WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //방문회원수
            $sql4 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(INS_CNT, 0) INS_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT CONCAT(DATE_FORMAT(sc.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 3 THEN statistic_value
                                          ELSE 0
                                       END)
                                       INS_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "'
                             GROUP BY chart_day) sc
                               ON sc.M = date_t.d
                     WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //탈퇴회원수
            $sql5 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(SECESSION_CNT, 0) SECESSION_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT CONCAT(DATE_FORMAT(sc.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc.statistic_dt, '%Y-%m-%d') M,
                                    CONCAT(DATE_FORMAT(sc.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                    SUM(
                                       CASE
                                          WHEN statistic_kind = 4 THEN statistic_value
                                          ELSE 0
                                       END)
                                       SECESSION_CNT
                               FROM statistics_customer sc
                              WHERE sc.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "'
                             GROUP BY chart_day) sc
                               ON sc.M = date_t.d
                     WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            $data['range'] = 'month';
        }
//        echo $sql;

        $data['lists'] = $this->Db_m->getList($sql, 'TIMING_STATS');
        $data['lists2'] = $this->Db_m->getList($sql2, 'TIMING_STATS');
        $data['lists3'] = $this->Db_m->getList($sql3, 'TIMING_STATS');
        $data['lists4'] = $this->Db_m->getList($sql4, 'TIMING_STATS');
        $data['lists5'] = $this->Db_m->getList($sql5, 'TIMING_STATS');

        $this->load->view('sub/statistics/member', $data);
    }

    function uvpv() {

        $data['sdate'] = date("Y-m-d", strtotime("-1 week"));
        $data['edate'] = date("Y-m-d", strtotime("-1 day"));

        $sdate = date("Y-m-d", strtotime("-1 week"));
        $edate = date("Y-m-d", strtotime("-1 day"));

        $data['range'] = 'day';
        if (!$this->uri->segment(5) || $this->uri->segment(5) == 'day') {
            $date_where = "d BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";
            $date_where2 = "d BETWEEN '" . date("Y-m-d", strtotime("$edate -6 day")) . "' AND '" . $edate . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {

                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);

                $sdate = $this->uri->segment(3);
                $edate = $this->uri->segment(4);

                $date_where = "d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";

                $diff_sdate = new DateTime($sdate);
                $diff_edate = new DateTime($edate);

                $diff = date_diff($diff_sdate, $diff_edate);

                if ($diff->days >= 7) {
                    $date_where2 = "d BETWEEN '" . date("Y-m-d", strtotime("$edate -6 day")) . "' AND '" . $edate . "' ";
                } else if ($diff->days < 7) {
                    $date_where2 = "d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }
            }

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
                              WHERE sc2.statistic_dt BETWEEN '" . $data['sdate'] . "' AND '" . $data['edate'] . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql .= $date_where;
            $sql .= "ORDER BY d";

//            echo $sql;

            $sql2 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_MAIN_KEY_CNT, 0) UV_MAIN_KEY_CNT,
                            IFNULL(PV_MAIN_KEY_CNT, 0) PV_MAIN_KEY_CNT
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
                                          WHEN statistic_kind = 1 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_MAIN_KEY_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "ORDER BY d";

            //메인(리스트)
            $sql3 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_MAIN_LIST_CNT, 0) UV_MAIN_LIST_CNT,
                            IFNULL(PV_MAIN_LIST_CNT, 0) PV_MAIN_LIST_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 2 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_MAIN_LIST_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "ORDER BY d";

            //관심종목
            $sql4 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_INTEREST_CNT, 0) UV_INTEREST_CNT,
                            IFNULL(PV_INTEREST_CNT, 0) PV_INTEREST_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 3 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_INTEREST_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "ORDER BY d";

            //랭킹(인기종목)
            $sql5 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_LANK_POPULAR_CNT, 0) UV_LANK_POPULAR_CNT,
                            IFNULL(PV_LANK_POPULAR_CNT, 0) PV_LANK_POPULAR_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 4 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_LANK_POPULAR_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "ORDER BY d";

            //랭킹(인기키워드)
            $sql6 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_LANK_POPULAR_KEY_CNT, 0) UV_LANK_POPULAR_KEY_CNT,
                            IFNULL(PV_LANK_POPULAR_KEY_CNT, 0) PV_LANK_POPULAR_KEY_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 5 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_LANK_POPULAR_KEY_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql6 .= $date_where2;
            $sql6 .= "ORDER BY d";

            //랭킹(인기뉴스)
            $sql7 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_LANK_POPULAR_NEWS_CNT, 0) UV_LANK_POPULAR_NEWS_CNT,
                            IFNULL(PV_LANK_POPULAR_NEWS_CNT, 0) PV_LANK_POPULAR_NEWS_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 6 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_LANK_POPULAR_NEWS_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql7 .= $date_where2;
            $sql7 .= "ORDER BY d";

            //알림
            $sql8 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_NOTICE_CNT, 0) UV_NOTICE_CNT,
                            IFNULL(PV_NOTICE_CNT, 0) PV_NOTICE_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 7 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_NOTICE_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql8 .= $date_where2;
            $sql8 .= "ORDER BY d";

            //스크랩
            $sql9 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_SCRAP_CNT, 0) UV_SCRAP_CNT,
                            IFNULL(PV_SCRAP_CNT, 0) PV_SCRAP_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 8 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_SCRAP_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql9 .= $date_where2;
            $sql9 .= "ORDER BY d";

            //로그인
            $sql10 = "SELECT CONCAT(DATE_FORMAT(d, '%d'), '일') chart_day,
                            IFNULL(UV_LOGIN_CNT, 0) UV_LOGIN_CNT,
                            IFNULL(PV_LOGIN_CNT, 0) PV_LOGIN_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                                          WHEN statistic_kind = 9 AND statistic_type = 2
                                          THEN
                                             statistic_value
                                          ELSE
                                             0
                                       END)
                                       PV_LOGIN_CNT
                               FROM statistics_customer2 sc2
                              WHERE sc2.statistic_dt BETWEEN '" . strtotime("$edate -1 week") . "' AND '" . $edate . "'
                             GROUP BY sc2.statistic_dt, sc2.statistic_kind) sc2
                               ON sc2.M = date_t.d
                      WHERE ";
            $sql10 .= $date_where2;
            $sql10 .= "ORDER BY d";

//            echo $sql2;
        }

        if ($this->uri->segment(5) == 'week') {
            $date_where = "sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {

                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);

                $sdate = $this->uri->segment(3);
                $edate = date("Y-m-d", strtotime("" . $this->uri->segment(4) . " +2 day"));

                $date_where = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";

                $diff_sdate = new DateTime($data['sdate']);
                $diff_edate = new DateTime($data['edate']);

                $diff = date_diff($diff_sdate, $diff_edate);
                if ($diff->days >= 28) {
                    $date_where2 = "date_t.d BETWEEN '" . date("Y-m-d", strtotime("$edate -27 day")) . "' AND '" . date("Y-m-d", strtotime("$edate -2 day")) . "' ";
                } else if ($diff->days < 28) {
                    $date_where2 = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }
            }

            $sql = "SELECT 
                        CONCAT(
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                        ' ~ ',
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
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
                        (SELECT 
                            DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                            ADDDATE(sc2.statistic_dt, - WEEKDAY(sc2.statistic_dt)) AS MONDAY,
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
                              WHERE sc2.statistic_dt BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "'
                             GROUP BY MONDAY) sc2
                               ON sc2.M = date_t.d
                        WHERE ";
            $sql .= $date_where;
            $sql .= "GROUP BY DAY_NAME
                     ORDER BY d";

            $sql2 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_MAIN_KEY_CNT, 0) UV_MAIN_KEY_CNT,
                         IFNULL(PV_MAIN_KEY_CNT, 0) PV_MAIN_KEY_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 1 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_MAIN_KEY_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "GROUP BY DAY_NAME
                      ORDER BY d";


            //메인(리스트)
            $sql3 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_MAIN_LIST_CNT, 0) UV_MAIN_LIST_CNT,
                         IFNULL(PV_MAIN_LIST_CNT, 0) PV_MAIN_LIST_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 2 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_MAIN_LIST_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //관심종목
            $sql4 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_INTEREST_CNT, 0) UV_INTEREST_CNT,
                         IFNULL(PV_INTEREST_CNT, 0) PV_INTEREST_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 3 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_INTEREST_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //랭킹(인기종목)
            $sql5 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_LANK_POPULAR_CNT, 0) UV_LANK_POPULAR_CNT,
                         IFNULL(PV_LANK_POPULAR_CNT, 0) PV_LANK_POPULAR_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 4 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_LANK_POPULAR_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //랭킹(인기키워드)
            $sql6 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_LANK_POPULAR_KEY_CNT, 0) UV_LANK_POPULAR_KEY_CNT,
                         IFNULL(PV_LANK_POPULAR_KEY_CNT, 0) PV_LANK_POPULAR_KEY_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 5 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_LANK_POPULAR_KEY_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql6 .= $date_where2;
            $sql6 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //랭킹(인기뉴스)
            $sql7 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_LANK_POPULAR_NEWS_CNT, 0) UV_LANK_POPULAR_NEWS_CNT,
                         IFNULL(PV_LANK_POPULAR_NEWS_CNT, 0) PV_LANK_POPULAR_NEWS_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 6 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_LANK_POPULAR_NEWS_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql7 .= $date_where2;
            $sql7 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //알림
            $sql8 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_NOTICE_CNT, 0) UV_NOTICE_CNT,
                         IFNULL(PV_NOTICE_CNT, 0) PV_NOTICE_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 7 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_NOTICE_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql8 .= $date_where2;
            $sql8 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //스크랩
            $sql9 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_SCRAP_CNT, 0) UV_SCRAP_CNT,
                         IFNULL(PV_SCRAP_CNT, 0) PV_SCRAP_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 8 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_SCRAP_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql9 .= $date_where2;
            $sql9 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            //로그인
            $sql10 = "SELECT 
                        DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d') chart_day,
                         CONCAT(
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d)), '%y/%m/%d'),
                            ' ~ ',
                            DATE_FORMAT(ADDDATE(date_t.d, -WEEKDAY(date_t.d) + 6), '%y/%m/%d'))
                         DAY_NAME,
                         IFNULL(UV_LOGIN_CNT, 0) UV_LOGIN_CNT,
                         IFNULL(PV_LOGIN_CNT, 0) PV_LOGIN_CNT
                    FROM timing_stats.date_t
                        LEFT OUTER JOIN
                        (SELECT DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                ADDDATE(sc2.statistic_dt, -WEEKDAY(sc2.statistic_dt))
                                   AS MONDAY,
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
                                      WHEN statistic_kind = 9 AND statistic_type = 2
                                      THEN
                                         statistic_value
                                      ELSE
                                         0
                                   END)
                                   PV_LOGIN_CNT
                           FROM statistics_customer2 sc2
                          WHERE sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("$edate -4 week")) . "' AND '" . date("Y-m-d", strtotime("$edate -1 day")) . "'
                         GROUP BY MONDAY) sc2
                           ON sc2.M = date_t.d
                WHERE ";
            $sql10 .= $date_where2;
            $sql10 .= "GROUP BY DAY_NAME
                      ORDER BY d";

            $data['range'] = 'week';
        }

        if ($this->uri->segment(5) == 'month') {
            $date_where = "sc2.statistic_dt BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";

            if ($this->uri->segment(3) && $this->uri->segment(4)) {

                $data['sdate'] = $this->uri->segment(3);
                $data['edate'] = $this->uri->segment(4);

                $sdate = $this->uri->segment(3);
                $edate = $this->uri->segment(4);

                $diff_sdate_y = substr($data['sdate'], 0, -6);
                $diff_sdate_m = substr($data['sdate'], 5, -3);
                $diff_edate_y = substr($data['edate'], 0, -6);
                $diff_edate_m = substr($data['edate'], 5, -3);

                $dist = ($diff_edate_y - $diff_sdate_y) * 12 + ($diff_edate_m - $diff_sdate_m);

                $date_where = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";

                if ($dist >= 6) {
                    $date_where2 = "date_t.d BETWEEN '" . date("Y-m-d", strtotime("$edate -5 month")) . "' AND '" . $edate . "' ";
                } else if ($dist < 6) {
                    $date_where2 = "date_t.d BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
                }
            }

            $sql = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%Y/%m'), '월') chart_day,
                            date_t.d,
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
                            (SELECT 
                                    CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                    DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
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
                            WHERE sc2.statistic_dt BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql .= $date_where;
            $sql .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                     ORDER BY d";
            
//            echo $sql;

            $sql2 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_MAIN_KEY_CNT, 0) UV_MAIN_KEY_CNT,
                            IFNULL(PV_MAIN_KEY_CNT, 0) PV_MAIN_KEY_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_KEY_CNT,
                                SUM(CASE WHEN statistic_kind = 1 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_KEY_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql2 .= $date_where2;
            $sql2 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";
            
//            echo $sql2;

            //메인(리스트)
            $sql3 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_MAIN_LIST_CNT, 0) UV_MAIN_LIST_CNT,
                            IFNULL(PV_MAIN_LIST_CNT, 0) PV_MAIN_LIST_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_MAIN_LIST_CNT,
                                SUM(CASE WHEN statistic_kind = 2 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_MAIN_LIST_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql3 .= $date_where2;
            $sql3 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //관심종목
            $sql4 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_INTEREST_CNT, 0) UV_INTEREST_CNT,
                            IFNULL(PV_INTEREST_CNT, 0) PV_INTEREST_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_INTEREST_CNT,
                                SUM(CASE WHEN statistic_kind = 3 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_INTEREST_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql4 .= $date_where2;
            $sql4 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //랭킹(인기종목)
            $sql5 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_LANK_POPULAR_CNT, 0) UV_LANK_POPULAR_CNT,
                            IFNULL(PV_LANK_POPULAR_CNT, 0) PV_LANK_POPULAR_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_CNT,
                                SUM(CASE WHEN statistic_kind = 4 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql5 .= $date_where2;
            $sql5 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //랭킹(인기키워드)
            $sql6 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_LANK_POPULAR_KEY_CNT, 0) UV_LANK_POPULAR_KEY_CNT,
                            IFNULL(PV_LANK_POPULAR_KEY_CNT, 0) PV_LANK_POPULAR_KEY_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_KEY_CNT,
                                SUM(CASE WHEN statistic_kind = 5 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_KEY_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql6 .= $date_where2;
            $sql6 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //랭킹(인기뉴스)
            $sql7 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_LANK_POPULAR_NEWS_CNT, 0) UV_LANK_POPULAR_NEWS_CNT,
                            IFNULL(PV_LANK_POPULAR_NEWS_CNT, 0) PV_LANK_POPULAR_NEWS_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LANK_POPULAR_NEWS_CNT,
                                SUM(CASE WHEN statistic_kind = 6 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LANK_POPULAR_NEWS_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql7 .= $date_where2;
            $sql7 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //알림
            $sql8 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_NOTICE_CNT, 0) UV_NOTICE_CNT,
                            IFNULL(PV_NOTICE_CNT, 0) PV_NOTICE_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_NOTICE_CNT,
                                SUM(CASE WHEN statistic_kind = 7 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_NOTICE_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql8 .= $date_where2;
            $sql8 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //스크랩
            $sql9 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_SCRAP_CNT, 0) UV_SCRAP_CNT,
                            IFNULL(PV_SCRAP_CNT, 0) PV_SCRAP_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_SCRAP_CNT,
                                SUM(CASE WHEN statistic_kind = 8 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_SCRAP_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql9 .= $date_where2;
            $sql9 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                      ORDER BY d";

            //로그인
            $sql10 = "SELECT CONCAT(DATE_FORMAT(date_t.d, '%Y-%m'), '월') DAY_NAME,
                            CONCAT(DATE_FORMAT(date_t.d, '%y/%m'), '월') chart_day,
                            date_t.d,
                            IFNULL(UV_LOGIN_CNT, 0) UV_LOGIN_CNT,
                            IFNULL(PV_LOGIN_CNT, 0) PV_LOGIN_CNT
                       FROM timing_stats.date_t
                            LEFT OUTER JOIN
                            (SELECT 
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%y/%m'), '월') chart_day,
                                DATE_FORMAT(sc2.statistic_dt, '%Y-%m-%d') M,
                                CONCAT(DATE_FORMAT(sc2.statistic_dt, '%Y-%m'), '월') DAY_NAME,
                                SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 1 THEN statistic_value ELSE 0 END ) UV_LOGIN_CNT,
                                SUM(CASE WHEN statistic_kind = 9 AND statistic_type = 2 THEN statistic_value ELSE 0 END ) PV_LOGIN_CNT
                            FROM 
                                statistics_customer2 sc2
                            WHERE sc2.statistic_dt BETWEEN '" . $sdate . "' AND '" . $edate . "'
                            GROUP BY chart_day
                            ) sc
                            ON sc.M = date_t.d
                      WHERE ";
            $sql10 .= $date_where2;
            $sql10 .= "GROUP BY DATE_FORMAT(date_t.d, '%Y-%m')
                       ORDER BY d";

            $data['range'] = 'month';
        }

        $data['lists'] = $this->Db_m->getList($sql, 'TIMING_STATS');
        $data['lists2'] = $this->Db_m->getList($sql2, 'TIMING_STATS');
        $data['lists3'] = $this->Db_m->getList($sql3, 'TIMING_STATS');
        $data['lists4'] = $this->Db_m->getList($sql4, 'TIMING_STATS');
        $data['lists5'] = $this->Db_m->getList($sql5, 'TIMING_STATS');
        $data['lists6'] = $this->Db_m->getList($sql6, 'TIMING_STATS');
        $data['lists7'] = $this->Db_m->getList($sql7, 'TIMING_STATS');
        $data['lists8'] = $this->Db_m->getList($sql8, 'TIMING_STATS');
        $data['lists9'] = $this->Db_m->getList($sql9, 'TIMING_STATS');
        $data['lists10'] = $this->Db_m->getList($sql10, 'TIMING_STATS');


        $this->load->view('sub/statistics/uvpv', $data);
    }

    function rank() {

        $data['sdate'] = date("Y-m-d", strtotime("-1 week"));
        $data['edate'] = date("Y-m-d", strtotime("-1 day"));
        $data['kind'] = 1;

        $add_where = "CB.reg_date BETWEEN '" . date("Y-m-d", strtotime("-1 week")) . "' AND '" . date("Y-m-d", strtotime("-1 day")) . "' ";
        $day_col = "CONCAT(DATE_FORMAT(CB.reg_Date, '%Y-%m-%d'),
                      CASE 
                        DAYOFWEEK(CB.reg_Date) 
                        WHEN 1     THEN '(일)' 
                        WHEN 2     THEN '(월)' 
                        WHEN 3     THEN '(화)' 
                        WHEN 4     THEN '(수)' 
                        WHEN 5     THEN '(목)' 
                        WHEN 6     THEN '(금)' 
                        WHEN 7     THEN '(토)' 
                      END
                    ) DAY_NAME";

        if ($this->uri->segment(3) && $this->uri->segment(4)) {
            $add_where = "CB.reg_date BETWEEN '" . $this->uri->segment(3) . "' AND '" . $this->uri->segment(4) . "' ";
            $data['sdate'] = $this->uri->segment(3);
            $data['edate'] = $this->uri->segment(4);
        }

        if ($this->uri->segment(5)) {
            $add_where .= "AND CB.type = '" . $this->uri->segment(5) . "' ";
            $data['kind'] = $this->uri->segment(5);
        }

        if ($this->uri->segment(6)) {
//            echo date("Y-m-d", strtotime("-1 week", strtotime($this->uri->segment(4)." 12:00:00")));
//            $day_col = "";
        }

        if ($this->uri->segment(5) == 3) {
            $cnt_row_sql = "SELECT 
                            MAX(CNT) MAX_CNT
                        FROM (
                            SELECT 
                              COUNT(*) CNT
                            FROM 
                              contents_best2 CB
                            WHERE 
                              CB.keyword <> '' AND ";
            $cnt_row_sql .= $add_where;
            $cnt_row_sql .= "GROUP BY CB.reg_date
                        ) AS counts";

            $data['cnt_row'] = $this->Db_m->getInfo($cnt_row_sql, 'TIMING_STATS');

            $chart_sql = "SELECT 
                        CONCAT(DATE_FORMAT(CB.reg_Date, '%d'), '일') DAY_NAME, 
                        SUM(CB.statistic_value) SUM_VALUE
                      FROM 
                        contents_best2 CB
                      WHERE 
                        CB.keyword <> '' AND ";
            $chart_sql .= $add_where;
            $chart_sql .= "GROUP BY CB.reg_date
                        ORDER BY CB.reg_date ASC, SUM_VALUE DESC";

            $data['chart_datas'] = $this->Db_m->getList($chart_sql, 'TIMING_STATS');

            $sql = "SELECT 
                    CB.reg_Date,
                    DATE_FORMAT(CB.reg_Date, '%Y%m%d') DAY_FORMAT,
                    CONCAT(DATE_FORMAT(CB.reg_Date, '%Y-%m-%d'),
                      CASE 
                        DAYOFWEEK(CB.reg_Date) 
                        WHEN 1     THEN '(일)' 
                        WHEN 2     THEN '(월)' 
                        WHEN 3     THEN '(화)' 
                        WHEN 4     THEN '(수)' 
                        WHEN 5     THEN '(목)' 
                        WHEN 6     THEN '(금)' 
                        WHEN 7     THEN '(토)' 
                      END
                    ) DAY_NAME
                FROM 
                    contents_best2 CB
                WHERE 
                    CB.keyword <> '' AND ";
            $sql .= $add_where;
            $sql .= "GROUP BY CB.reg_Date
                 ORDER BY CB.reg_date ASC";

            $data['dayList'] = $this->Db_m->getList($sql, 'TIMING_STATS');

            for ($i = 0; $i < $data['cnt_row']->MAX_CNT; $i++) {
                foreach ($data['dayList'] as $row) {
                    $rank_sql = "SELECT 
                            CB.reg_Date, 
                            SUM(CB.statistic_value) SUM_VALUE
                          FROM 
                            contents_best2 CB
                          WHERE 
                            CB.reg_date = '" . $row['reg_Date'] . "'
                            GROUP BY CB.stock_seq
                            ORDER BY CB.reg_date ASC, SUM_VALUE DESC LIMIT $i, 1";
//                echo $rank_sql;
                    $data['rank_list' . $row['DAY_FORMAT'] . $i] = $this->Db_m->getList($rank_sql, 'TIMING_STATS');
                }
            }
        } else {
            $cnt_row_sql = "SELECT 
                            MAX(CNT) MAX_CNT
                        FROM (
                            SELECT 
                              COUNT(*) CNT
                            FROM 
                              contents_best CB, timing_news.stock S 
                            WHERE 
                              CB.stock_seq = S.stock_seq AND ";
            $cnt_row_sql .= $add_where;
            $cnt_row_sql .= "GROUP BY CB.reg_date
                        ) AS counts";

            $data['cnt_row'] = $this->Db_m->getInfo($cnt_row_sql, 'TIMING_STATS');

            $chart_sql = "SELECT 
                        CONCAT(DATE_FORMAT(CB.reg_Date, '%d'), '일') DAY_NAME, 
                        SUM(CB.statistic_value) SUM_VALUE
                      FROM 
                        contents_best CB, timing_news.stock S 
                      WHERE 
                        CB.stock_seq = S.stock_seq AND ";
            $chart_sql .= $add_where;
            $chart_sql .= "GROUP BY CB.reg_date
                        ORDER BY CB.reg_date ASC, SUM_VALUE DESC";

            $data['chart_datas'] = $this->Db_m->getList($chart_sql, 'TIMING_STATS');

            $sql = "SELECT 
                    CB.reg_Date,
                    DATE_FORMAT(CB.reg_Date, '%Y%m%d') DAY_FORMAT,";
            $sql .= $day_col;
            $sql .= " FROM 
                    contents_best CB, timing_news.stock S 
                WHERE 
                    CB.stock_seq = S.stock_seq AND ";
            $sql .= $add_where;
            $sql .= "GROUP BY CB.reg_Date
                 ORDER BY CB.reg_date ASC";

            $data['dayList'] = $this->Db_m->getList($sql, 'TIMING_STATS');

            for ($i = 0; $i < $data['cnt_row']->MAX_CNT; $i++) {
                foreach ($data['dayList'] as $row) {
                    $rank_sql = "SELECT 
                            CB.reg_Date, 
                            min(S.company_name_i) company_name_i, 
                            SUM( CB.statistic_value) SUM_VALUE
                          FROM 
                            contents_best CB, timing_news.stock S 
                          WHERE 
                            CB.stock_seq = S.stock_seq AND
                            CB.reg_date = '" . $row['reg_Date'] . "'
                            GROUP BY CB.stock_seq
                            ORDER BY CB.reg_date ASC, SUM_VALUE DESC LIMIT $i, 1";
//                echo $rank_sql;
                    $data['rank_list' . $row['DAY_FORMAT'] . $i] = $this->Db_m->getList($rank_sql, 'TIMING_STATS');
                }
            }
        }

        $this->load->view('sub/statistics/rank', $data);
    }

    function admin() {
        $this->load->view('sub/admin/admin');
    }

    function admin_add() {

        $top_menu_sql = "SELECT
                            menu_seq,
                            menu_name
                         FROM 
                            tbl_menu 
                         WHERE 
                            p_menu_seq = 0";

        $data['top_menu_lists'] = $this->Db_m->getList($top_menu_sql, 'TIMING_STATS');
        foreach ($data['top_menu_lists'] as $row) {
            $sub_menu_sql = "SELECT
                                menu_seq, 
                                menu_name 
                             FROM 
                                tbl_menu 
                             WHERE 
                                p_menu_seq = '" . $row['menu_seq'] . "' AND
                                menu_seq <> 12";
            $data['sub_menu_lists' . $row['menu_seq']] = $this->Db_m->getList($sub_menu_sql, 'TIMING_STATS');
        }

        $this->load->view('sub/admin/admin_add', $data);
    }

    function admin_mod() {

        $sql = "SELECT
                    admin_seq, 
                    admin_id, 
                    admin_level, 
                    menu_seq_list, 
                    admin_name, 
                    acept 
                FROM 
                    admin 
                WHERE 
                    admin_seq = '" . $this->uri->segment(3) . "'";

        $data['info'] = $this->Db_m->getInfo($sql, 'TIMING_CUSTOMER');

        $top_menu_sql = "SELECT
                            menu_seq,
                            menu_name
                         FROM 
                            tbl_menu 
                         WHERE 
                            p_menu_seq = 0";

        $data['top_menu_lists'] = $this->Db_m->getList($top_menu_sql, 'TIMING_STATS');
        foreach ($data['top_menu_lists'] as $row) {
            $sub_menu_sql = "SELECT
                                menu_seq, 
                                menu_name 
                             FROM 
                                tbl_menu 
                             WHERE 
                                p_menu_seq = '" . $row['menu_seq'] . "' AND
                                menu_seq <> 12";
            $data['sub_menu_lists' . $row['menu_seq']] = $this->Db_m->getList($sub_menu_sql, 'TIMING_STATS');
        }

        $this->load->view('sub/admin/admin_mod', $data);
    }

    function work_history() {
        $this->load->view('sub/admin/work_history');
    }

}
