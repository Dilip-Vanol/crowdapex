<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
?>

<div class="product-grid__wrapper">
                <?php foreach ($products as $key => $value) { ?>
                    <div class="grid_1_of_4 images_1_of_4">
                     <a target="_blank" href="<?=$value->product_url?>"><img height="50" src="<?=$value->product_image?>" alt=""></a>
                     <h2><?=$value->product_name;?></h2>
                    <div class="price-details">
                       <div class="price-number">
                            <p><span class="rupees">$ <?=$value->sale_price;?></span></p>
                            <p><span class="rupees">category: <?=$value->category;?></span></p>
                            <p><span class="rupees"><strike>$ <?=$value->normal_price;?></strike></span></p>
                            <p><span class="rupees">Discount <?=$value->discount;?>%</span></p>
                        </div>
                    </div>
                     
                </div>
                <?php } ?>
                
            </div>
            <?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>