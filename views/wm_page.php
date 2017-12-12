<?php ini_set('date.timezone','Asia/Shanghai'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>埋单易外卖接单测试版</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" href="/public/jdx/sm.min.css">
    <link rel="stylesheet" href="/public/jdx/sm-extend.min.css">
     <link rel="stylesheet" href="/public/jdx/index.css">

  </head>
  <body>
    <div class="page">
        <div  id="page-fixed-tab-infinite-scroll" >
            <div class="content native-scroll" >
              <div class="page-index ">
                <div class="row  no-gutter">
                  <!-- 右边 -->
                  <div class="col-15 buttons-fixed" style="top: 0px;">
                    <a href="javascript:close()" style="height: 2rem;line-height: 2rem;border-radius: 0;" class="button button-big button-fill button-danger">隐藏</a>
                    <div class="list-block" style="margin-top:0px">
                          <ul>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="type" checked="" value="0">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">全部</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="type" value="2">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">美团</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="type" value="3">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">饿了么</div>
                                  </div>
                                </div>
                              </label>
                              <br/>
                              <br/>
                            </li>
                            <li>
                              <a style="height: 2rem;line-height: 2rem;border-radius: 0;"  href="javascript:open_noprinter()" class="button button-big button-fill">未打印</a>
                              </li>
                              <li>
                              <a style="height: 2rem;line-height: 2rem;border-radius: 0;"  href="javascript:window.location.reload()" class="button button-big button-fill button-warning">刷新</a>
                              </li>
                          </ul>
                    </div>
                  </div>
                  <div class="col-15" style="visibility: hidden;">
                    <a href="close://"  style="height: 2rem;line-height: 2rem;border-radius: 0;"class="button button-big button-fill button-danger">隐藏</a>
                  </div>

                  <!-- 左边 -->

                    <div class="col-85">
                       <div class="buttons-tab fixed-tab">
                        <?php foreach ($tabs as $k => $v): ?>
                          <a href="?status=<?php echo $k ?>" class="<?php if($current_status == $k): ?> active <?php endif;?> button">
                            <?php echo $v ?>
                          </a>
                        <?php endforeach ?>
                      </div>  
                    <div class="tabs">
                        <div id="tab<?php echo $current_status; ?>" class="tab active">
                          <div class="list-container row" >
                               <?php foreach ($order_list as $key => $data): ?>
                                  <?php 
                                    $platform = "美团";
                                    $platform_class = "badge-meituan";
                                    $card_class = "card-meituan";
                                    if($data['wmType'] ==3){
                                      $platform = "饿了么";
                                      $platform_class = "badge-eleme";
                                      $card_class = "card-eleme";
                                    }
                                    $book = "立即送达";
                                    if($data['book'] == 1){ 
                                      $book = "预订单:".date('Y-m-d H:i:s',$data['deliveryTime']);
                                    }
                                    $pay = "货到付款";
                                    if($data['onlinePaid'] == 1){
                                      $pay = "在线支付";
                                    }
                                    switch ($data['status']) {
                                      case 1000:
                                        $status = "新单-待处理订单";
                                        break;
                                      case 1100:
                                        $status = "已接单";
                                        break;
                                      case 1101:
                                        $status = "配送中";
                                        break;
                                      case 1300:
                                        $status = "退款申请";
                                        break;
                                      case 1400:
                                        $status = "订单已取消";
                                        break;
                                      case 1200:
                                        $status = "已完成订单";
                                        break;
                                      default:
                                        $status = "其他";
                                        break;
                                    }
                                   ?>
                                  <div class="col-33 card <?php echo $card_class ?>" id ="orderId_<?php echo $data['orderId'] ?>" data-id="<?php echo $data['id'] ?>">
                                      <div class="card-header">
                                        #<?php echo $data['erp_no'] ?><a href="javascript:void(0)" class="button <?php echo $platform_class;  ?>"><?php echo $platform ?>#<?php echo $data['daySeq'] ?></a>
                                      </div>
                                      <div class="card-content">
                                        <div class="card-content-inner">
                                          <div class="detail">
                                            <p class="wm-date"><span class="icon icon-clock "></span><?php echo date('Y-m-d H:i:s',$data['orderTime']) ?>(<?php echo $book ?>)</p>
                                            <p > <span class="icon icon-phone"></span><?php echo $data['recipientName'] ?>(<?php echo $data['recipientPhone'] ?>)</p>
                                            <p class="color-red wm-address"><span class="icon icon-edit"><?php echo $data['recipientAddress'] ?></span> </p>
                                            <p ><span class="icon icon-star"></span><span class="jdx_status"><?php echo $status ?></span></p>
                                            <p class="color-gray">预算收入:<?php echo $data['payAmount'] ?> (<?php echo $pay ?>)</p>
                                            <span style="display:none" class="detail_json"><?php echo json_encode($data); ?></span>
                                          </div>
                                        </div>
                                      </div>
                                        <div class="row card-footer">
                                              <div class="col-50 jdx_butotn jdx_cancel_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>" class="jdx_cancel   button button-big button-fill button-danger">拒单</a></div>
                                              <div class="col-50 margin-left jdx_butotn jdx_comfirm_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>" class="jdx_comfirm   button button-big button-fill button-dark">接单</a></div>
                                              <div class="col-50 jdx_butotn jdx_rejectrefund_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>"  class="jdx_rejectrefund  button button-big button-fill button-danger">拒绝</a></div>
                                              <div class="col-50 margin-left jdx_butotn jdx_agreerefund_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>"  class="jdx_agreerefund   button button-big button-fill button-info">同意</a></div>
                                              <div class="col-50 jdx_butotn jdx_printer_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>"  class="jdx_printer button button-big button-fill button-light">打印</a></div>
                                              <div class="col-50 margin-left jdx_butotn jdx_songcan_button"><a href="javascript:void(0)" data-id="o<?php echo $data['orderId']; ?>"  class="jdx_songcan button button-big button-fill button-info">送餐</a></div>
                                        </div>
                                    </div>
                               <?php endforeach ?>
                          </div>
                          <!-- 加载提示符 -->
                          <div class="row layui-laypage">
                            <?php echo $page ?>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div> 
        </div>
    </div>


    <script type='text/javascript' src='/public/jdx/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/public/jdx/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='/public/jdx/sm-extend.min.js' charset='utf-8'></script>
    <script type="text/javascript">
          function open_noprinter() {
              try{
                  window.mde.actionFromJsOpen();
                }catch(e){

                }
            }
            function do_jdx_action (action,orderId) {
                $.showPreloader('正在操作中...')
                $.ajax({
                    url : '/jdx/do_jdx_action/<?php echo $shop_key ?>/'+action+'/'+orderId,
                    type : 'get',
                    timeout : 0,
                    success : function(data){
                        if(data.error){
                          $.alert(data.error.message);
                        }else{
                          if(action == 'cancel' || action == 'agreeRefund'){
                            $.post('/jdx/do_jdx_action/<?php echo $shop_key ?>/cancel_byself/'+orderId);
                          }else if(action == 'rejectRefund'){
                            $.post('/jdx/do_jdx_action/<?php echo $shop_key ?>/reject_byself/'+orderId,{},function() {
                              $("#orderId_"+orderId).prependTo('#tab1100 .list-container');
                            });
                          }
                          $.alert("操作成功");
                        }
                        $.hidePreloader();
                    },
                    error : function(error){
                        $.alert('网络失去连接,请重试');
                        $.hidePreloader();
                    }
                });
            }
            
            function close () {
                try{
                  window.mde.actionFromJsToClose();
                }catch(e){

                }
            }
            function printer (id,style) {
              var data = $("#orderId_"+id).find('.detail_json').text();
              try{
                  if(style == 1){
                    style  = parseInt('<?php echo  $kitchen; ?>');
                  }
                  window.mde.actionFromJsToPrint(data,style,'',"","<?php echo $shop_name ?>");
                }catch(e){
                  console.log(e);
                }
              
            }
            function getReceiveDetail(code) {
               if(code == 'income'){
                  return '商家实收/预计收入';
               }else if(code == 'onlinePay'){
                  return '在线支付金额';
               }else if(code == 'serviceFee'){
                  return '平台服务费';
               }else if(code == 'shippingFee'){
                  return '配送费';
               }else if(code == 'shopPart'){
                  return '商户承担活动补贴';
               }else if(code == 'wmPart'){
                  return '外卖平台承担活动补贴';
               }
            }
            function timeStamp2String(time){  
                var datetime = new Date();  
                datetime.setTime(time);  
                var year = datetime.getFullYear();  
                var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;  
                var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();  
                var hour = datetime.getHours()< 10 ? "0" + datetime.getHours() : datetime.getHours();  
                var minute = datetime.getMinutes()< 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();  
                var second = datetime.getSeconds()< 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();  
                return year + "-" + month + "-" + date+" "+hour+":"+minute+":"+second;  
            }  
            // 创建长时间的连接
            $(function () {
                'use strict';


                $(document).on('click','.jdx_cancel',function () {
                      var id =$(this).data('id');
                      $.confirm('确定要拒收订单吗', '提示', function () {
                        do_jdx_action('cancel',id.substring(1,id.length));
                      });
                });
                $(document).on('click','.jdx_comfirm',function () {
                    var id =$(this).data('id');
                    $.confirm('确定要接收订单吗', '提示', function () {
                        do_jdx_action('confirm',id.substring(1,id.length));
                    });
                });
                $(document).on('click','.jdx_agreerefund',function () {
                      var id =$(this).data('id');
                      $.confirm('确定要同意退款吗', '提示', function () {
                        do_jdx_action('agreeRefund',id.substring(1,id.length));
                      });
                });
                $(document).on('click','.jdx_rejectrefund',function () {
                      var id =$(this).data('id');
                      $.confirm('确定要拒绝退款吗', '提示', function () {
                        do_jdx_action('rejectRefund',id.substring(1,id.length));
                      });
                });
                $(document).on('click','.jdx_songcan',function () {
                      var id =$(this).data('id');
                      do_jdx_action('songcan',id.substring(1,id.length));
                });
                $(document).on('click','.jdx_printer',function () {
                    var id =$(this).data('id');
                    $.modal({
                      title:  '选择打印',
                      text: null,
                      close:true,
                      verticalButtons: true,
                      buttons: [
                        {
                          text: '打印小票',
                          onClick: function() {
                             printer(id.substring(1,id.length),0);
                          }
                        },
                        {
                          text: '打印厨房小票',
                          onClick: function() {
                             printer(id.substring(1,id.length),1);
                          }
                        },
                        {
                          text: '取消'
                        },
                      ]
                    })

                });
                $(document).on('click','.jdx_p_ticket',function () {
                    var id =$(this).data('id');
                    printer(id.substring(1,id.length),0);

                });
                $(document).on('click','.jdx_p_kitchen',function () {
                    var id =$(this).data('id');
                    printer(id.substring(1,id.length),1);

                });

                $(document).on('click', "input[type='radio']", function(e){
                    if($(this).val() == 2){
                      $('.card-eleme').css('display','none');
                       $('.card-meituan').css('display','block');
                    }else if($(this).val() == 3){
                      $('.card-eleme').css('display','block');
                       $('.card-meituan').css('display','none');
                    }else{
                      $('.card-eleme').css('display','block');
                      $('.card-meituan').css('display','block');
                    }
                    return;
                });
                $(document).on('click','.detail', function () {
                    var data_json = $(this).find('.detail_json').text();
                    var status = $(this).find('.jdx_status').text();
                    var data  = $.parseJSON(data_json);
                    var products_html  = "";
                    var receive_html  = "";
                    var total_html  = "";
                    var activity_html  = "";
                    $.each($.parseJSON(data.dishes), function(index, item){
                                                  products_html +=  '<li class="item-content">'+
                                                                     '<div class="item-inner">'+
                                                                       '<div class="item-title">'+item.dishName+item.spec+'</div>'+
                                                                       '<div class="item-after">'+item.price+' X'+item.quantity+'</div>'+
                                                                      '</div>'+
                                                                     '</li>';
                                                  });
                    $.each($.parseJSON(data.poiReceiveDetails), function(key, value){
                                                  if(value > 0 ){
                                                      receive_html +=  ''+getReceiveDetail(key)+':'+value/100+'<br />';
                                                     }
                                              });
                    total_html += '<li class="item-content"><div class="item-inner">'+
                                      '<div class="item-title color-red">配送费</div>'+
                                       '<div class="item-after  color-red"><span class="color-red">'+data.shippingFee+'</span></div>'+
                                         '</div></li><li class="item-content">'+
                                             '<div class="item-inner"><div class="item-title color-red">餐盒费</div>'+
                                                '<div class="item-after  color-red"><span class="color-red">'+data.packageFee+'</span></div>'+
                                                  '</div></li><li class="item-content"><div class="item-inner">'+
                                                  '<div class="item-title color-red">实际支付价</div>'+
                                                    '<div class="item-after"><span class="color-red">'+data.payAmount+'</span></div>'+
                                                    '</div>'+
                                                    '</li>';
                    $.each($.parseJSON(data.activities), function(index, item){
                                                  activity_html +=  item.info+':'+item.reduce+'<br />';
                                                  });
                    var invoice  ="";
                    if(data.needInvoice == 1){
                        invoice = '<p class="color-red">发票抬头:'+data.invoiceTitle+'</p>';
                    }
                     var popupHTML = '<div class="popup">'+
                                      '<div class="content-block">'+
                                        '<a href="javascript:void(0)" class="button button-big button-fill close-popup"><span class="icon icon-down"></span>&nbsp;关闭</a>'+
                                        '<div class="card">'+
                                          '<div class="card-header">#'+data.erp_no+'<a href="javascript:void(0)" class="button ">'+status+'</a></div>'+
                                            '<div class="card-content row">'+
                                              '<div class="col-50">'+
                                                '<p class="color-gray">当天流水号:#'+data.daySeq+'<br />'+
                                                '客户端订单号:'+data.orderViewId+'<br />'+
                                                '单号:'+data.orderId+'<br />'+
                                                '下单时间:'+timeStamp2String(data.orderTime*1000)+'<br />'+
                                                '联系人:'+data.recipientName+'<br />'+
                                                '手机:'+data.recipientPhone+'<br />'+
                                                '地址:'+data.recipientAddress+'<br />'+
                                                '备注:'+data.comment+'</p>'+
                                                '<p>'+invoice+'</p><p>'+activity_html+'</p><p>'+receive_html+'</p>'+
                                              '</div>'+
                                            '<div class="col-50">'+
                                               '<div class="row  card-footer">'+
                                                '<div class="col-50"><a href="javascript:void(0)" data-id="o'+data.orderId+'" class="jdx_p_ticket button button-big button-fill button-info">打印小票</a></div>'+
                                                 '<div class="col-50"><a href="javascript:void(0)" data-id="o'+data.orderId+'" class="jdx_p_kitchen button button-big button-fill button-dark">打印厨房小票</a></div>'+
                                               '</div>'+
                                               '<div class="list-block">'+
                                                    '<ul>'+products_html+total_html+'</ul>'+
                                               '</div>'+
                                            '</div>'+
                                         '</div>'+
                                          '</div>'+
                                        '</div>'+
                                      '</div>'+
                                    '</div>'
                    $.popup(popupHTML);
                  });
            })
      
    </script>
  </body>
</html>