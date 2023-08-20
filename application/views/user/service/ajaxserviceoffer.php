<div class="table-responsive">
<?php 
		if(count($offers)>0){?>
<table class="table mb-0">
<?php }else{?>
	<table class="table mb-0" >
<?php }?>
	<thead>
		<tr>
			<th>Service</th>
			<th>Amount</th>
			<th>Offer</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Created Date</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(count($offers)>0){
			
			foreach ($offers as $val) {
				?>
				<tr>
					<td><?php echo $val['service_title']?></td>
					<td><?php echo currency_conversion($val['currency_code']).$val['service_amount'];?></td>
					<td><?php echo $val['offer_percentage']?>%</td>
					<td><?php echo date("d-m-Y", strtotime($val['start_date']))?></td>
					<td><?php echo date("d-m-Y", strtotime($val['end_date']))?></td>
					<td><?php echo date("d-m-Y h:i A", strtotime($val['created_at']))?></td>
					<td><a href="#" class="btn btn-sm btn-info">Edit</a></td>
				</tr>
				<?php 
			}
		}
		?>
		</tbody>
	</table>
</div>