


<?php 

$user_id = $this->uri->segment('2');
$user_details = $this->db->where('id',$user_id)->get('employee_basic_details')->row_array();

if($user_details['experience'] == '30+') {
	$exp = $user_details['experience'].'Yrs'; 
} else {
	if($user_details['exp_month'] != '0') { 
		$exp = $user_details['experience']. '.'.$user_details['exp_month'].'Yrs'; 
	} else {
		$exp = $user_details['experience'].'Yrs'; 
	}
}
$availability_details = json_decode($user_details['availability'],true);

$serv_detail = $this->db->from('employee_services_list')->where(array('provider_id'=> $user_details['provider_id'],'emp_id'=> $user_details['id']))->get()->result_array();

$category = '';
$subcategory = '';
if(!empty($user_details['category'])){
$category = $this->db->select('category_name')->where('id',$user_details['category'])->get('categories')->row()->category_name;
}
if(!empty($user_details['subcategory'])){
$subcategory = $this->db->select('subcategory_name')->where('id',$user_details['subcategory'])->get('subcategories')->row()->subcategory_name;
}

$date=date(settingValue('date_format'), strtotime($user_details['dob']));
?>

<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Staff Details</h3>
				</div>
				<div class="text-right mb-3">
				<a href="<?php echo base_url()?>staff-lists" class="btn btn-primary float-end">Back</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-body text-center">
						<?php if($user_details['profile_img'] != '' && file_exists($user_details['profile_img']))
						{?>
						<img class="rounded-circle img-fluid mb-3" alt="User Image" src="<?php echo $base_url.$user_details['profile_img'] ?>">
						<?php } else { ?>
						<img class="rounded-circle img-fluid mb-3" alt="User Image" src="<?php echo $base_url?>assets/img/user.jpg">
						<?php } ?>
						<h5 class="card-title text-center">
							<span>Account Status</span>
						</h5>
						<?php
						if($user_details['status']==1) {
							$val='checked';
						}
						else {
							$val='';
						}
						?>
						<?php if($user_details['status'] == 1) { ?>
						<i class="fas fa-user-check btn btn-success"> Active</i> 
						<?php } else { ?>
						<i class="fas fa-user-check btn btn-danger"> Inactive</i> 
						<?php } ?>
					</div>
				</div>
			</div>
			
			<div class="col-lg-8">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Personal Details</span>
						</h5>
						<div class="row">
							<p class="col-sm-4 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
							<p class="col-sm-8"><?php echo $user_details['first_name']." ".$user_details['last_name'] ?></p>
						</div>
						<div class="row">
							<p class="col-sm-4 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
							<p class="col-sm-8"><?php echo $user_details['email']?></p>
						</div>
						<div class="row">
							<p class="col-sm-4 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
							<p class="col-sm-8"><?php echo $user_details['country_code']?>-<?php echo $user_details['contact_no']?></p>
						</div>
						<div class="row">
							<p class="col-sm-4 text-muted text-sm-end mb-0 mb-sm-3">Date of Birth</p>
							<p class="col-sm-8"><?php echo $date;?></p>
						</div>
						<div class="row">
							<p class="col-sm-4 text-muted text-sm-end mb-0 mb-sm-3">&nbsp;</p>
							<p class="col-sm-8">&nbsp;</p>
						</div>
					</div>
				</div>              
			</div>
		</div>
		
		<div class="row">			
			<div class="col-md-4">
				<div class="card available-widget">
					<div class="card-body">
						<h5 class="card-title">Staff Availability</h5>
						<ul>
							<?php
					if(!empty($availability_details))
					{
					foreach ($availability_details as $availability) {

					$day = $availability['day'];
					$from_time = $availability['from_time'];
					$to_time = $availability['to_time'];

					  if($day == '1')
					  {
						$weekday = 'Monday';
					  }
					  elseif($day == '2')
					  {
						$weekday = 'Tuesday';
					  }
					  elseif($day == '3')
					  {
						$weekday = 'Wednesday';
					  }
					  elseif($day == '4')
					  {
						$weekday = 'Thursday';
					  }
					  elseif($day == '5')
					  {
						$weekday = 'Friday';
					  }
					  elseif($day == '6')
					  {
						$weekday = 'Saturday';
					  }
					  elseif($day == '7')
					  {
						$weekday = 'Sunday';
					  }
					  elseif($day == '0')
					  {
						$weekday = 'Sunday';
					  }
					 
					echo '<li><span>'.$weekday.'</span>'.$from_time.' - '.$to_time.'</li>'; 
					}
					}
					else
					{
						echo '<li class="text-center">No Details found</li>';
					}
					
					?>
						</ul>
					
					</div>
				</div> 
			</div>
		</div>
	</div>
</div>