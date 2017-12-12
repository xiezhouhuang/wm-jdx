<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<title>Excel</title>
<style>
td{
		text-align:center;
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif;
		border:#000 1px solid;
		color:#152122;
		width:100px;
}
table,tr{
		border-style:none;
}
</style>
</head>
<body>
<pre><?php //print_r($shops) ?></pre>
<table>
<thead>
<tr>
<th>订单号</th>
<th>订单时间</th>
<th>埋单易流水号</th>
<th>外卖平台</th>
<th>联系人</th>
<th>联系电话</th>
<th>地址</th>
<th>实际支付价钱</th>
<th>产品</th>
<th>数量</th>
<th>价钱</th>
</tr>
</thead>
<tbody>
<?php foreach ($order_list as $item):  ?>
	<?php 
	 $product_arr = json_decode($item['dishes'],true);
	 $product_size = sizeof($product_arr);
     foreach ($product_arr as $k => $product):
     ?>
 	 <?php if ($k== 0):  ?>
 	 <tr >
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['orderId'] ?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo date('Y-m-d H:i:s',$item['orderTime'])?></td>
		<td rowspan="<?php echo $product_size; ?>">#<?php echo $item['erp_no']?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['wmType'] == 2?'美团#'.$item['daySeq']:'饿了么#'.$item['daySeq']; ?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['recipientName'] ?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['recipientPhone'] ?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['recipientAddress'] ?></td>
		<td rowspan="<?php echo $product_size; ?>"><?php echo $item['payAmount'] ?></td>
		<td><?php echo $product['dishName'].$product['spec'] ?></td>
		<td><?php echo $product['quantity'] ?></td>
		<td><?php echo $product['price'] ?></td>
	</tr>   
	<?php else: ?>
	 <tr>
		<td ><?php echo $product['dishName'].$product['spec'] ?></td>
		<td><?php echo $product['quantity'] ?></td>
		<td><?php echo $product['price'] ?></td>
	</tr> 
	<?php endif ?>
 <?php endforeach; ?>

  

<?php endforeach ?>
</tbody>
</table>

</body>
</html>