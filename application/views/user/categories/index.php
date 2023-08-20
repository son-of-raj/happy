<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?></h2>
				</div>
			</div>
			<div class="col-auto float-end ms-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<div class="container">
		<div class="">
			<?php 
			$pagination=explode('|',$this->ajax_pagination->create_links());
			?>
		</div>					
		<div class="catsec">
			<div class="row" id="dataList">

			<?php
			if(!empty($category)) {
				foreach ($category as $crows) {
					$category_name=strtolower($crows['category_name']);
					$category_slug=strtolower($crows['category_slug']);
					
					if(!empty($crows['category_image']) && (@getimagesize(base_url().$crows['category_image']))){
						$category_image = base_url().$crows['category_image'];
					}else{
						$category_image = "https://via.placeholder.com/381x286.png?text=Category%20Image";
					} 
				?>
			<div class="col-lg-2 col-md-3 col-sm-4">
				<div class="card category-card">
					<div class="card-body">
						<div class="cate-icon">
							<a href="<?php echo base_url();?>search/<?php echo str_replace(' ', '-', $category_slug)?>">
								<img alt="" src="<?php echo $category_image;?>">
							</a>
						</div>
						<div class="cate-content">
							<a href="<?php echo base_url();?>search/<?php echo str_replace(' ', '-', $category_slug)?>">
								<span><?php echo ucfirst($crows['category_name']);?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php } }
			else { 

			echo '<div class="col-lg-12">
			<div class="category">
			No Categories Found
			</div>
			</div>';
			} 

			echo $this->ajax_pagination->create_links();
			?>
			</div>
		</div>
	</div>
</div>