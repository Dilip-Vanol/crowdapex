<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$stores = Yii::$app->mycomponent->findstores();
$categories = Yii::$app->mycomponent->findcategories();
?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="main">
 <!---728x90--->

    <div class="content">
        <div class="header_slide">
            <div class="header_bottom_left">                
                <div class="categories">
                  <ul>
                    <h3>Search</h3>
                      <li><a href="#">Stores</a>
                        <?= Html::dropDownList('stores', null,$stores,['id'=>'store_select','multiple'=>'multiple']) ?>
                        
                      </li>
                      <li><a href="#">Categories</a>
                        <div id="cat-container">
                            <?= Html::checkboxList('categories', [], $categories,['itemOptions'=>['class' => 'cat_checkbox']]) ?>
                        </div>

                      </li>
                      <li><a href="#">Discount %</a>
                        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                        <div id="slider-range"></div>

                      </li>
                      <li><a href="#">Price</a>
                        <p>Min : <?= Html::textInput('min_price','',['id'=>'min_price']) ?></p>
                        <p>Max : <?= Html::textInput('max_price','',['id'=>'max_price']) ?></p>
                      </li>

                  </ul>
                </div>                  
             </div>
        <div class="clear"></div>                         
        </div>
             
          <div class="section group" id="product-container">
             <?=$this->render('product-listings',['products' => $products,
            'pages'=>$pages])?> 
        </div>
    </div>
 </div>

<script>
  //$( document ).ready(function() {

    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 100,
      values: [ 0, 0 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
        searchProduct();
      },
      stop:function( event, ui ) {
        searchProduct();
      },
    });
    $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
      " - " + $( "#slider-range" ).slider( "values", 1 ) );
  //} );
    

    $(document).on('change', '#store_select', function() {
        var stores = $(this).val().toString();
        var check = '';
        if(stores.length==0){
        console.log(stores.length);
            return false;
        }
        $.ajax({
            method: "POST",
            url: 'index.php?r=products/get-category',
            type : 'json',
            data: { stores: stores},
            success: function (data) {
                
                //console.log(data);
                $.each( data, function( index, val ) {
                    check += '<label><input class="cat_checkbox" type="checkbox" name="categories[]" value="'+index+'"> '+val+'</label>';
                });
                $( "#cat-container" ).html(check);
                searchProduct();
            }
        });
    });
    $(document).on('change', '.cat_checkbox', function() {
        searchProduct();
    });
    $(document).on('blur', '#min_price', function() {
        searchProduct();
    });
    $(document).on('blur', '#max_price', function() {
        searchProduct();
    });
    function searchProduct(){
        var stores = $('#store_select').val().toString();
        var page = $('.pagination .active a').data('page');
        //console.log(stores);
        var ct = [];
        $.each($(".cat_checkbox:checked"), function(){            
            ct.push($(this).val());
        });
        var amount = $("#amount").val();
        var categories = ct.toString();
        var min_price = $('#min_price').val();
        //console.log(min_price);
        var max_price = $('#max_price').val();
        //console.log(max_price);
        $.ajax({
            method: "POST",
            url: 'index.php?r=products/index&page='+(page+1),
            
            data: {'discount':amount,'stores': stores,'categories':categories,'min_price':min_price,'max_price':max_price},
            success: function (data) {
                $('#product-container').html(data);
            }
        });
    }

    $(document).on('click', '.pagination li a', function(e) {
        $('.pagination li').removeClass('active');
        $(this).parent().addClass('active');
        e.preventDefault();
        searchProduct();
    });
  </script>