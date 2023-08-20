<?php
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
?>
<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2><?php echo (!empty($user_language[$user_selected]['lg_FAQ'])) ? $user_language[$user_selected]['lg_FAQ'] : $default_language['en']['lg_FAQ']; ?></h2>
				</div>
			</div>
			<div class="col-auto float-right ml-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_FAQ'])) ? $user_language[$user_selected]['lg_FAQ'] : $default_language['en']['lg_FAQ']; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="faq-card">
			
				<?php  if(!empty($pages)) { 
				$i =1;
	        	foreach ($pages as $key => $value) { ?>
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">
								<a class="collapsed" data-bs-toggle="collapse" href="#faq<?php echo $i; ?>"><?php echo ($value->page_title)?$value->page_title:''; ?></a>
							</h4>
							<div id="faq<?php echo $i; ?>" class="card-collapse collapse">
								<?php echo ($value->page_content) ? $value->page_content:''; ?>
							</div>
						</div>
					</div>
				<?php 
			$i++;
			} 
		 		}  else { ?>
		 			<h3>Details Not Found </h3>
		 		<?php } ?>				
		</div>
			</div>
		</div>
	</div>
</div>