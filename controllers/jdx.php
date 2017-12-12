<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Jdx extends CI_Controller {


	function __construct(){
		parent::__construct();

		//初始化
		$this->load->model('jdx_model');
	}
	//在美团后台添加的推送地址
	//接受美团推送的信息
	//由于考虑到饿了么的转发,全部推送通过该接口
	public function jdx_push()
	{	
		$post_data = file_get_contents("php://input");
		if(!empty($post_data)){
			$json_data = json_decode($post_data, true);
			$key = $json_data['erpShopId'];//获取ERPID,识别分店
			$this->jdx_model->initialize($key);//链接对应分店数据库
			$data = json_decode($json_data['message'],true);
			$apply = 0;//不是退款操作
			foreach ($data as $key => $value) {
				if($key == 'dishes' || $key == 'activities' || $key == 'poiReceiveDetails' ){
					  $data[$key] =  json_encode($value);//json解析
				}
				if($key == 'refundStatus' && $value == 'apply'){
					$apply = 1;  //申请
				}else if($key == 'refundStatus' && $value == 'reject'){
					$apply = 2; //拒绝
				} 
			}
			$return['data'] = "NO";
			$result = false;
			if(!isset($data['status'])){
				//退款和取消
				$result = $this->jdx_model->set_wm_order_update($data,$apply,$data['orderId']);
			}else if($data['status'] == '1000'){
				//新增订单
				$result = $this->jdx_model->set_new_order($data);	
			}else{
				//更新状态
				$result = $this->jdx_model->set_wm_update_status($data);
			}
			//添加成功
			if($result){
				$return['data'] = "OK";
			}

			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($return));
		}
		
	}
	//外卖数据报表
	public function report($shop_key)
	{	
		$this->jdx_model->initialize($shop_key);
		$date = $this->input->get('date')?$this->input->get('date'):0;
		$type = $this->input->get('type')?$this->input->get('type'):0;
		$time = $this->input->get('time')?$this->input->get('time'):0;
		$export = $this->input->get('export')?$this->input->get('export'):0;

		$this->_return = $this->jdx_model->get_report_count($date,$time,$type);
		$this->_return['date']  = $date;
		$this->_return['type']  = $type;
		$this->_return['time']  = $time;
		if($export == 1){
			$filename = '外卖订单数据.xls';
			header( "Content-type:application/vnd.ms-excel" );
			header( "Content-Disposition:filename=".$filename );
			$this->load->view('jdx/excel_basic_view',$this->_return);
		}else{
			$this->load->view('jdx/wm_report',$this->_return);
		}
	}
	//接单页面显示
	public function page($shop_key)
	{	
		$this->jdx_model->initialize($shop_key);
		$this->load->library('pagination');
		$per_page = $this->input->get('per_page')?$this->input->get('per_page'):1;
		$current_status = $this->input->get('status')?$this->input->get('status'):'1000';
		$order_count = $this->jdx_model->jdx_order_count($current_status);

		$config['base_url'] = '/jdx/page/'.$shop_key.'?status='.$current_status;
		$config['total_rows'] = $order_count;
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 9;
		$config['cur_tag_open'] = '<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>';
		$config['cur_tag_close'] = '</em></span>';
		$this->pagination->initialize($config);
		$tabs = array('1000' => '未接订单','1100' => '已接订单','1101' => '配送中','1300' => '退款申请','1400' => '已取消','1200' => '已完成');
		
		$order_list = $this->jdx_model->jdx_order_list(($per_page-1)*$config['per_page'],$config['per_page'],$current_status);

		$this->_return['page'] =  $this->pagination->create_links();
		$this->_return['order_list'] = $order_list;
		$this->_return['tabs'] = $tabs;
		$this->_return['current_status'] = $current_status;
		$this->load->view('jdx/wm_page',$this->_return);
	}
	//执行订单操作(确认修改)
	public function do_jdx_action($shop_key,$action,$orderId,$reason = "",$reasonCode = 0)
	{
		$this->jdx_model->initialize($shop_key);
		$this->load->model('info_model');
		$this->info_model->initialize($shop_key);
		$shop = $this->info_model->get_one_shop(0);
		$shop_config = json_decode($shop['config'],true);
		$signKey = real_value($shop_config,'signKey')?$shop_config['signKey']:"";
		if(strlen($orderId) > 17){
			$token = real_value($shop_config,'eleme_token')?$shop_config['eleme_token']:"";
		}else{
			$token = real_value($shop_config,'meituan_token')?$shop_config['meituan_token']:"";
		}
		$timestamp = time();
		$data = "";
		$sign_array  =array();
		$result = 0;
		log_message('error', $shop_key.$action.' id = '.$orderId);
		if($action == 'confirm'){
			
			$sign_array = array("timestamp" => $timestamp,"token" => $token,"orderId" => $orderId);
		}else if($action == 'cancel'){
			if(strlen($orderId) > 17){
				$sign_array = array("timestamp" => $timestamp,"token" => $token,"orderId" => $orderId,"reasonCode" => "others","reason" => "收银取消订单");
			}else{
				$sign_array = array("timestamp" => $timestamp,"token" => $token,"orderId" => $orderId,"reasonCode" => "1204","reason" => "收银取消订单");
			}
			
		}else if($action == 'agreeRefund'){
			$sign_array = array("timestamp" => $timestamp,"token" => $token,"orderId" => $orderId,"reason" => "同意退款");
		}else if($action == 'rejectRefund'){
			$sign_array = array("timestamp" => $timestamp,"token" => $token,"orderId" => $orderId,"reason" => "拒绝退款");
		}else if($action == 'songcan'){
			$result = $this->jdx_model->jdx_songcan($orderId);
		}else if($action == 'reject_byself'){  //拒绝退款订单之后修改订单状态 , 接口平台不会自动推送商户自行拒绝的订单状态
			$result = $this->jdx_model->reject_byself($orderId);
		}else if($action == 'cancel_byself'){ //取消订单之后修改订单状态 , 接口平台不会自动推送商户自行取消的订单状态
			$result = $this->jdx_model->jdx_cancel($orderId);
		}
		if($action != 'songcan' && $action != 'cancel_byself' && $action != 'reject_byself'){
			$sign =$this->get_signature($sign_array,$signKey);
			$data  =$this->get_param($sign_array,$sign);
			$this->output->set_content_type('application/json');
			$this->output->set_output($this->do_curl($action,$data,$orderId));
		}else{
			if($result > 0){
				$this->output->set_content_type('application/json');
				$this->output->set_output('{"result":0,"message":"送餐成功"}');
			}else{
				$this->output->set_content_type('application/json');
				$this->output->set_output('{"error":{"code":"songcan_error","message":"送餐失败请重试"}}');
			}
			
		}
	}
	//curl 推送信息到接单侠
	private function do_curl($url,$data,$orderId)
	{
		$test_url = "http://qjt.open.meituan.com/order/";
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	      CURLOPT_URL => $test_url.$url,
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POSTFIELDS => $data,
	      CURLOPT_HTTPHEADER => array(
	        "cache-control: no-cache",
	        "content-type: application/x-www-form-urlencoded"
	      ),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);

	    curl_close($curl);

	    if ($err) {
	    	log_message('error', $orderId.'{"error":0,"data":'.json_encode($err).'}');
	      return '{"error":0,"data":'.json_encode($err).'}';
	    } else {
	        $r_json = json_decode($response,true);
	       if(isset($r_json['result']) && strtolower($r_json['result']) == "ok"){
	       		log_message('error', $orderId.''.$response);
	       		$this->jdx_model->set_jdx_update_status($orderId);
	       }elseif(isset($r_json['error']['code']) && strtoupper($r_json['error']['code'])  == 'BIZ_BIZ_FAILED_ORDER_STATE' ){
	       		log_message('error', $orderId.''.$response);
	       		$this->jdx_model->set_jdx_update_status($orderId);
	       }
	      return $response;
	    }
	}
	//参数组合
	function get_param(array $param,$sign)
	{
	    $string = "";
	    foreach ($param as $key => $value) {
	        $string .= $key."=".$value."&";
	    }

	    return $string."sign=".$sign;
	}
	//签名
	function get_signature(array $param,$signKey)
	{
	    ksort($param);
	    $string = "";
	    foreach ($param as $key => $value) {
	        $string .= $key.$value;
	    }
	    $splice =  $signKey.$string;
	    $md5 = strtolower(sha1($splice));

	    return $md5;
	}
	public function storebinding_callback()
	{
		$businessId = $this->input->post('businessId');
		$ePoiId = $this->input->post('ePoiId');
		$appAuthToken = $this->input->post('appAuthToken');
		if(!empty($ePoiId)){
			//保存对应的平台token
			echo '{"data":"success"}';
		}else{
			echo '{"data":"fail"}';
		}
	}
 }
 ?>