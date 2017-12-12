<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jdx_model extends CI_Model{
    private  $mde;
    function __construct(){
        parent::__construct();
        $this->mde = $this->load->database($this->config->item('mde'),TRUE);
    }

    public function initialize($db) {
        if ($this->config->item($db))
            $this->load->database($this->config->item($db)); // 根据erpShopID选择不同的数据库
        else
            exit(0);
    }
    //模拟送餐
    public function jdx_songcan($orderId)
    {
        $query  =  $this->db->query("INSERT INTO `wm_order_update`(`orderId`,`operator`,`status`,`updateTime`,`reason`,`reasonCode`,`refundStatus`,`type`) values
('".$orderId."','3','1101','".time()."','收银平台设置配送',null,null,null)");
        if($query > 0){
            $this->db->query("UPDATE  wm_order SET `status` = '1101' WHERE orderId = '".$orderId."'");
        }
        return $query;
    }
    //自动接单
    public function reject_byself($orderId)
    {
        $this->db->query("UPDATE  wm_order SET `status` = '1100' WHERE orderId = '".$orderId."'");
    }
    //执行取消订单
    public function jdx_cancel($orderId)
    {
        $query  =  $this->db->query("INSERT INTO `wm_order_update`(`orderId`,`operator`,`status`,`updateTime`,`reason`,`reasonCode`,`refundStatus`,`type`) values
('".$orderId."','3','1400','".time()."','收银平台取消订单',null,null,null)");
        if($query > 0){
            $this->db->query("UPDATE  wm_order SET `status` = '1400' WHERE orderId = '".$orderId."'");
        }
        return $query;
    }
    //判断外卖订单是否存在
    private function _is_exist_order($orderId)
    {
        $this->db->where('orderId',$orderId);
        $this->db->from('wm_order');
        $result  = $this->db->count_all_results(); 
        return $result; 
    }
    //更新的订单状态
    public function set_wm_update_status($data)
    {
        if($this->_is_exist_order($data['orderId']) > 0 ){
            if($data['status'] == 1100){
                $this->set_jdx_update_status($data['orderId']);
                return true;//跳过不存在订单
            }else{
                $this->db->trans_start();
                if($data['status'] != 1200){
                    $this->db->query("INSERT INTO wm_order_update SET `operator` = '".$data['operator']."' , `orderId` = '".$data['orderId']."', `updateTime` = '".$data['updateTime']."', `status` = '".$data['status']."'" );
                }
                $this->db->query("UPDATE  wm_order SET `status` = '".$data['status']."' WHERE orderId = '".$data['orderId']."'");
                $result = $this->db->trans_complete();
                return $result;
            }
        }else{
            return true;//跳过不存在订单
        }
    }
    //判断订单是否已经确认
    private function _is_exist_update_order($orderId)
    {
        $this->db->where('orderId',$orderId);
        $this->db->where('status','1100');
        $this->db->from('wm_order_update');
        $result  = $this->db->count_all_results(); 
        return $result; 
    }
    //确认接收外卖订单
    public function set_jdx_update_status($orderId)
    {
        
        $this->db->trans_start();
        $this->db->query("UPDATE  wm_order SET `status` = '1100' WHERE orderId = '".$orderId."'");
        if($this->_is_exist_update_order($orderId) <= 0 ){
            $this->db->query("INSERT INTO wm_order_update SET `operator` = '8' , `orderId` = '".$orderId."', `updateTime` = '".time()."', `status` = '1100'" );
        }
        $this->db->trans_complete();
        
    }
    //新增订单
    public function set_wm_order_update($insert_data,$apply,$orderId)
    {
        if($this->_is_exist_order($orderId) > 0 ){
            $this->db->trans_start();
            $sql =  $this->db->insert_string("wm_order_update",$insert_data);
            $query = $this->db->query($sql);
            if($apply == 1){
                $this->db->query("UPDATE  wm_order SET `status` = '1300' WHERE orderId = '".$orderId."'");
            }else if($apply == 2){
                $this->db->query("UPDATE  wm_order SET `status` = '1100' WHERE orderId = '".$orderId."'");
            }
            $result = $this->db->trans_complete();
            return $result;
        }else{
            return true; //跳过不存在订单
        }
    }
    //获取订单详细
    public function getOrderDetal($orderId)
    {
        $query  =  $this->db->query("SELECT * FROM wm_order where orderId = '".$orderId."'");
        $result  = $query->row_array();
        return $result;
    }
    //数据报表
    public  function get_report_count($date = 0,$time =0,$type = 0)
    {

        $start_time = array(0,0,0); 
        $end_time = array(23,59,59);
        if($time == 1){
            $start_time = array(0,0,0); 
            $end_time = array(13,59,59);
        }elseif($time == 2){
            $start_time = array(14,0,0); 
            $end_time = array(23,59,59);
        }
        $start_date = mktime($start_time[0],$start_time[1],$start_time[2],date('m'),date('d'),date('Y'));    
        $end_date = mktime($end_time[0],$end_time[1],$end_time[2],date('m'),date('d'),date('Y'));

        if($date == 1){
            $start_date = mktime($start_time[0],$start_time[1],$start_time[2],date('m'),date('d')-1,date('Y'));    
            $end_date = mktime($end_time[0],$end_time[1],$end_time[2],date('m'),date('d')-1,date('Y'));
        }elseif($date ==2){
            $start_date = mktime(0,0,0,date('m'),date('d')-date("w")+1,date('Y'));
            $end_date = mktime(23,59,59,date('m'),date('d')-date("w")+7,date('Y'));
        }else if($date == 3){
            $start_date = mktime(0,0,0,date('m'),1,date('Y'));
            $end_date = mktime(23,59,59,date('m'),date('t'),date('Y'));
        }else if($date == 4){
            $start_date = mktime(0,0,0,date('m')-1,1,date('Y'));
            $end_date = mktime(23,59,59,date('m')-1,date('t'),date('Y'));
        }else if($date == 5){
            $start_date = mktime(0,0,0,date('m')-2,1,date('Y'));
            $end_date = mktime(23,59,59,date('m'),date('t'),date('Y'));
        }
        $where = "";
        if($type == 2){
            $where = " AND wmType = 2 ";
        }else if($type ==3){
             $where = " AND wmType = 3 ";
        }

        $query  =  $this->db->query("SELECT sum(payAmount) as total,count(*) as count FROM wm_order where orderTime >= '".$start_date."' AND orderTime <= '".$end_date."' AND status != '1400' ".$where." ");
        $result = $query->row_array();

        $query_count  =  $this->db->query("SELECT count(*) as count FROM wm_order where orderTime >= '".$start_date."' AND orderTime <= '".$end_date."' ".$where."  ");
        $result_count = $query_count->row_array();

        $result['all'] = $result_count['count'];

        $product_query  =  $this->db->query("SELECT dishes,wmType,orderId,orderTime,recipientPhone,recipientName,recipientAddress,payAmount,daySeq,erp_no FROM wm_order where orderTime >= '".$start_date."' AND orderTime <= '".$end_date."' AND status != '1400' ".$where." ");
        $product_result = $product_query->result_array();
        $product_num = array();
        $product_name = array();
        foreach ($product_result as $list) {
            $product_arr = json_decode($list['dishes'],true);
            foreach ($product_arr as $product) {
                array_push($product_num, $product['quantity']);
                array_push($product_name, $product['dishName']."=".$list['wmType']);
            }
        }
        $rows = array();
        foreach ($product_name as $key => $value) {
            if(isset($rows[$value])){
                $rows[$value] = $rows[$value]+$product_num[$key];
            }else{
                $rows[$value] = $product_num[$key];
            }
            
        }
        arsort($rows);
        $result['product_list'] = $rows;
        $result['order_list'] = $product_result;
        return $result;
    }
    //外卖订单总数
    public function jdx_order_count($status)
    {
        $this->db->where('status',$status);
        $this->db->from('wm_order');
        $result  = $this->db->count_all_results(); // Produces an integer, like 17
        return $result;
    }
    //获取订单列表
    public function jdx_order_list($limit,$per_page,$status)
    {
        $this->db->select('*')->from('wm_order');
        $this->db->where('status',$status);
        $this->db->order_by('id','desc');
        $this->db->limit($per_page,$limit);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result; 
    }
}
?>