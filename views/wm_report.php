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
     <style type="text/css">
      .col-85 .col-50{
        padding: 1rem 2rem;
      }
      .layui-bg-red{background-color: #da251c !important; color: #FFF!important;text-align: center;padding: 0.5rem} /*赤*/
      .layui-bg-orange{background-color: #FFB800!important; color: #fff!important;text-align: center;padding: 0.5rem} /*橙*/
      .layui-bg-green{background-color: #009688!important; color: #fff!important;text-align: center;padding: 0.5rem} /*绿*/
      .layui-bg-cyan{background-color: #2F4056!important; color: #fff!important;text-align: center;padding: 0.5rem} /*青*/
      .layui-bg-blue{background-color: #1E9FFF!important; color: #fff!important;text-align: center;padding: 0.5rem} /*蓝*/
      .layui-bg-black{background-color: #393D49!important; color: #fff!important;text-align: center;padding: 0.5rem} /*黑*/
      .layui-bg-gray{background-color: #eee!important; color: #666!important;text-align: center;padding: 0.5rem} /*灰*/
    /* 超小屏幕(手机) */@media screen and (max-width: 768px) {
        .col-50{width: 100% !important;}
        .col-15{width: 30% !important;}
        .col-85{width: 70% !important;}
      }
     </style>
  </head>
  <body>
    <div class="page-group">
        <div class="page page-current" id="page-fixed-tab-infinite-scroll" >
            <div class="content native-scroll" >
              <div class="page-index ">
                <div class="row  no-gutter">
                  <!-- 右边 -->
                  <div class="col-15 buttons-fixed" style="top: 0px;">
                    <a href="close://" style="height: 2rem;line-height: 2rem;border-radius: 0;" class="button button-big button-fill button-danger">返回</a>
                    <div class="list-block" style="margin-top:0px">
                    <form id="form">
                          <ul>
                           <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="type"  <?php echo  $type == 0?"checked":"" ?> value="0">
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
                                <input type="radio" name="type" <?php echo  $type == 3?"checked":"" ?>  value="3">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">饿了么</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="type" <?php echo  $type == 2?"checked":"" ?> value="2">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">美团</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                            <label class="label-checkbox item-content"><div class="item-title">时间段</div></label></li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="time" <?php echo  $time == 0?"checked":"" ?> value="0">
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">整天</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="time" value="1" <?php echo  $time == 1?"checked":"" ?>>
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">上午</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <label class="label-checkbox item-content">
                                <input type="radio" name="time" value="2"  <?php echo  $time == 2?"checked":"" ?>>
                                <div class="item-media"><i class="icon icon-form-checkbox"></i></div>
                                <div class="item-inner">
                                  <div class="item-title-row">
                                    <div class="item-title">下午</div>
                                  </div>
                                </div>
                              </label>
                            </li>
                            <li>
                              <a style="height: 2rem;line-height: 2rem;border-radius: 0;"  href="javascript:$('input[name=\'export\']').val(0);$('#form').submit();" class="button button-big button-fill button-warning">筛选</a>
                              </li>
                              <li>
                              <input type="hidden" name="export" value="0">
                              <a style="height: 2rem;line-height: 2rem;border-radius: 0;" href="javascript:$('input[name=\'export\']').val(1);$('#form').submit();" class="button button-big button-fill">导出</a>
                              </li>
                          </ul>
                          <input type="hidden" value="<?php echo $date; ?>" name="date">
                        </form>
                    </div>
                  </div>
                  <div class="col-15" style="visibility: hidden;">
                    <a href="close://"  style="height: 2rem;line-height: 2rem;border-radius: 0;"class="button button-big button-fill button-danger">隐藏</a>
                  </div>
                  <?php 
                    $active0 = "";
                    $active1 = "";
                    $active2 = "";
                    $active3 = "";
                    $active4 = "";
                    $active5 = "";
                    switch ($date) {
                      case 0:
                        $active0 = "active";
                        break;
                      case 1:
                        $active1 = "active";
                        break;
                      case 2:
                        $active2 = "active";
                        break;
                      case 3:
                        $active3 = "active";
                        break;
                      case 4:
                        $active4 = "active";
                        break;
                      case 5:
                        $active5 = "active";
                        break;
                      default:
                        # code...
                        break;
                    }
                   ?>
                  <!-- 左边 -->
                  <div class="col-85">
                      <div class="buttons-tab">
                        <a href="?date=0" class="tab-link button <?php echo $active0 ?>">今天</a>
                        <a href="?date=1" class="tab-link button <?php echo $active1 ?>">昨天</a>
                        <a href="?date=2" class="tab-link button <?php echo $active2 ?>">本周</a>
                        <a href="?date=3" class="tab-link button <?php echo $active3 ?>">本月</a>
                        <a href="?date=4" class="tab-link button <?php echo $active4 ?>">上月</a>
                        <a href="?date=5" class="tab-link button <?php echo $active5 ?>">近三月</a>
                      </div>
                      <div class="row">
                        <div class="col-50 ">
                          <div class="layui-bg-blue">
                            <h3>成交单数</h>
                            <h1><?php echo $count ?></h1>
                          </div>
                        </div>
                        <div class="col-50">
                          <div class="layui-bg-green">
                            <h3>总销售额</h>
                            <h1><?php echo $total?$total:0 ?></h1>
                          </div>
                        </div>
                        
                      </div>
                      <div class="row">
                        <div class="col-50 ">
                          <div class="layui-bg-cyan">
                            <h3>总单数</h>
                            <h1><?php echo $all ?></h1>
                          </div>
                        </div>
                        <div class="col-50">
                          <div class="layui-bg-orange">
                            <h3>取消单数</h>
                            <h1><?php echo ($all-$count) ?></h1>
                          </div>
                        </div>
                      </div>
                      <div class="list-block">
                        <ul>
                        <?php foreach ($product_list as $key => $value): ?>
                          <?php 
                            $arr = explode("=", $key);
                            $type = '<a href="javascript:void(0)" class="button badge-eleme">饿 &nbsp;了&nbsp; 么</a>';
                            if($arr[1] == 2){
                              $type = '<a href="javascript:void(0)" class="button badge-meituan">美团外卖</a>';
                            }
                           ?>
                          <li class="item-content">
                            <div class="item-media">
                              <?php echo $type ; ?>
                            </div>
                            <div class="item-inner">
                              <div class="item-title"><?php echo $arr[0] ?></div>
                              <div class="item-after"><?php echo $value ?></div>
                            </div>
                          </li>
                        <?php endforeach ?>
                        </ul>
                      </div>
                  </div>
                </div>
            </div> 
        </div>
    </div>
    <script type='text/javascript' src='/public/jdx/zepto.min.js' charset='utf-8'></script>
  </body>
</html>