<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}else if ($type == 'freelancer') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];

$btntxt = (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit'];
$usrbtntxt = (!empty($user_language[$user_selected]['lg_Send_To_User'])) ? $user_language[$user_selected]['lg_Send_To_User'] : $default_language['en']['lg_Send_To_User'];

?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Products'])) ? $user_language[$user_selected]['lg_My_Products'] : $default_language['en']['lg_My_Products']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						
						<li class="breadcrumb-item"><a href="<?php echo base_url()."my-products/".$shop_id; ?>"><?php echo (!empty($user_language[$user_selected]['lg_My_Products'])) ? $user_language[$user_selected]['lg_My_Products'] : $default_language['en']['lg_My_Products']; ?></a></li>
						
                        <li class="breadcrumb-item active" aria-current="page"><?php echo ($product_id=='' ? 'Add Product':'Edit Product')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-header text-center">
                    <?php
                     $add_product = (!empty($user_language[$user_selected]['lg_add_product'])) ? $user_language[$user_selected]['lg_add_product'] : $default_language['en']['lg_add_product']; 
                     $edit_product = (!empty($user_language[$user_selected][''])) ? $user_language[$user_selected][''] : $default_language['en']['']; 
                     ?>
                    <h2><?php echo ($product_id=='' ? $add_product: $edit_product)?></h2>
                </div>
                <form method="post" action="<?php echo base_url()?>save-my-product" enctype="multipart/form-data" autocomplete="off" id="<?php echo ($product_id=='' ? 'add_product':'edit_product')?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
                    <input class="form-control" type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                    <input class="form-control" id="hproduct_id" type="hidden" name="hproduct_id" value="<?php echo $product_id?>">
					<div class="service-fields mb-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_category'])) ? $user_language[$user_selected]['lg_category'] : $default_language['en']['lg_category']; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Category" name="products[category]" id="product_category" required>
									<?php
                                    if (!empty($catlist)) {
                                         foreach ($catlist as $c) {
                                            ?>
                                            <option value="<?php echo $c['id']?>" <?php echo ($c['id']==$product['category'] ? 'selected' : '')?>><?php echo $c['category_name']?></option>
                                            <?php 
                                         }
                                    } 
                                    ?>
									</select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Sub Category" name="products[subcategory]" id="subcategory"  required>
									<?php
                                    if (!empty($sublist)) {
                                         foreach ($sublist as $s) {
                                            ?>
                                            <option value="<?php echo $s['id']?>" <?php echo ($s['id']==$product['subcategory'] ? 'selected' : '')?>><?php echo $s['subcategory_name']?></option>
                                            <?php 
                                         }
                                    } 
                                    ?>
									</select>
                                </div>
                            </div>
                        </div>
                    </div>

				   <div class="service-fields mb-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_product_name'])) ? $user_language[$user_selected]['lg_product_name'] : $default_language['en']['lg_product_name']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[product_name]" id="product_name" value="<?php echo $product['product_name']?>" required>
                                </div>
                            </div>                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_unit_value'])) ? $user_language[$user_selected]['lg_unit_value'] : $default_language['en']['lg_unit_value']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[unit_value]" id="unit_value" value="<?php echo $product['unit_value']?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-fields mb-3">
                         <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_unit'])) ? $user_language[$user_selected]['lg_unit'] : $default_language['en']['lg_unit']; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Unit" name="products[unit]" id="unit" required>
                                    <?php
                                    if (!empty($unitlist)) {
                                         foreach ($unitlist as $u) {
                                            ?>
                                            <option value="<?php echo $u['id']?>" <?php echo ($u['id']==$product['unit'] ? 'selected' : '')?>><?php echo $u['unit_name']?></option>
                                            <?php 
                                         }
                                    } 
                                    ?>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_price'])) ? $user_language[$user_selected]['lg_price'] : $default_language['en']['lg_price']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[price]" id="price" value="<?php echo $product['price']?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <div class="service-fields mb-3">
                         <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_discount'])) ? $user_language[$user_selected]['lg_discount'] : $default_language['en']['lg_discount']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[discount]" id="discount" value="<?php echo ($product['discount'])?$product['discount']:'0'; ?>" required>
                                </div>
                            </div>                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_sale_price'])) ? $user_language[$user_selected]['lg_sale_price'] : $default_language['en']['lg_sale_price']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[sale_price]" id="sale_price" value="<?php echo $product['sale_price']?>" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="service-fields mb-3">
                         <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_manufactured_by'])) ? $user_language[$user_selected]['lg_manufactured_by'] : $default_language['en']['lg_manufactured_by']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[manufactured_by]" id="manufactured_by" value="<?php echo $product['manufactured_by']?>" required>
                                </div>
                            </div>                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_short_description'])) ? $user_language[$user_selected]['lg_short_description'] : $default_language['en']['lg_short_description']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="products[short_description]" id="short_description" value="<?php echo $product['short_description']?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                         <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?> <span class="text-danger">*</span></label>
                                    <textarea name="products[description]" id="description" class="form-control" rows="4" cols="2"><?php echo $product['description']?></textarea>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['product_image'])) ? $user_language[$user_selected]['product_image'] : $default_language['en']['product_image']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="service-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span><?php echo (!empty($user_language[$user_selected]['lg_Upload_Image'])) ? $user_language[$user_selected]['lg_Upload_Image'] : $default_language['en']['lg_Upload_Image']; ?> *</span>
                                    <input type="file" name="images[]" id="images" accept="image/jpeg, image/png, image/gif,">
                                </div>
                                <div id="uploadPreview">
                                    <?php
                                    if (!empty($product_images)) 
                                    {
                                        ?>
                                        <ul class="upload-wrap">
                                        <?php 
                                        foreach ($product_images as $img) 
                                        {
                                            ?>
                                            <li>
                                                <div class=" upload-images">
                                                    <img alt="Product Image" src="<?php echo base_url() . $img['thumb_image']; ?>">
                                                </div>
                                            </li>
                                            <?php 
                                        }
                                        ?>
                                        </ul>
                                    <?php 
                                    } 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit"><?php echo $btntxt; ?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>