<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DB_m extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function totCnt($sql, $db) {
        $query = $this->$db->query($sql);
        if ($query->num_rows() > 0) {  //맞는 데이터가 있다면 해당 내용 반환
            return $query->row();
        } else {  //맞는 데이터가 없을 경우
            return 0;
        }
    }
    
    
    function update($table, $edit_data, $where, $db) {
        if ($table && $edit_data && $where) {
            $result = $this->$db->update($table, $edit_data, $where);
//            print_r($result); exit;
            return $result;
        }
    }

    function delete($table, $where, $db) {
        if ($table && $where) {
            $result = $this->$db->delete($table, $where);
            return $result;
        }
    }

    function getInfo($sql, $db) {
        $query = $this->$db->query($sql);
//게시물 내용 반환
        $result = $query->row();
        return $result;
    }

    function getList($sql, $db) {
        $query = $this->$db->query($sql);
//        print_r($query);
//        //게시물 리스트 반환
        $result = $query->result_array();
        return $result;
    }

    function insData($table, $insert_array, $db) {
        $result = $this->$db->insert($table, $insert_array);

//결과 반환
        return $result;
    }

    function insMultiData($table, $insert_array, $db) {
        $result = $this->$db->insert_batch($table, $insert_array);
//    $result = $query->result();
        return $result;
    }

    function modMultiData($table, $insert_array, $where, $db) {
        $result = $this->$db->update_batch($table, $insert_array, $where);        
        return $result;
    }

//    function insertOnDuplicateKeyUpdate($table, $tableData, $db) {
//        if (!is_array($tableData)) {
//            return $this->msg_invalid_data;
//        }
//
//        if (!$this->validateTable($table)) {
//            return $this->msg_no_table;
//        }
//
//        foreach ($tableData as $column => $value) {
//            $columnNames[] = $column;
//            $insertValues[] = "'" . $value . "'";
//            $updateValues[] = $column . " = '" . $value . "'";
//        }        
//        
//        $this->$db->query("INSERT INTO $table(" . implode(', ', $columnNames) . ") VALUES(" . implode(', ', $insertValues) . ") on duplicate key update " . implode(', ', $updateValues));        
//        return $this->db->insert_id();
//    }

    function insert_id() {
        return @mysql_insert_id($this->conn_id);
    }

}
