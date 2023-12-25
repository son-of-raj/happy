<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2><?php echo $cat_name;?> - <?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?> </h2>
				</div>
			</div>
			<div class="col-auto float-end ms-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?></li>
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
					$category_name=strtolower($crows['subcategory_name']);
					$category_slug=strtolower($crows['subcategory_slug']);
					
					if(!empty($crows['subcategory_image']) && (@getimagesize(base_url().$crows['subcategory_image']))){
						$category_image = base_url().$crows['subcategory_image'];
					}else{
						$category_image = "https://via.placeholder.com/381x286.png?text=SubCategory%20Image";
					} 
					$crowcategory = strtolower($cat_name);
					$crowcategory_slg = strtolower($cat_slug);
				?>
			<div class="col-lg-4 col-md-6">
					<div class="cate-widget">
						<img src="<?php echo $category_image;?>" alt="">
						<div class="cate-title">
							<a href="javascript:void(0)"><h3><span><i class="fas fa-circle"></i> <?php echo ucfirst($crows['subcategory_name']);?></span></h3></a>
						</div>
						<div class="cate-count">
							<a class="text-white" href="<?php echo base_url();?>search/<?php echo str_replace(' ', '-', $crowcategory_slg)?>/<?php echo str_replace(' ', '-', $category_slug)?>"><i class="fas fa-clone"></i> <?php echo $crows['category_count'];?></a>
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