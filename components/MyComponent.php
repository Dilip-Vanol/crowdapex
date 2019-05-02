<?php
namespace app\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
 use app\models\Stores;
 use app\models\Products;
class MyComponent extends Component
{
 public function findstores()
 {
  	$stores = Stores::find()->all();
  	foreach ($stores as $key => $value) {
  		$retrun_array[$value->id] = $value->store_name;
  	}
  	return $retrun_array;
 }

 public function findcategories($where='1=1')
 {
  	$query = Products::find()->select(['distinct(category)'])->where($where)->limit(10)->all();
  	$retrun_array = array();
  	foreach ($query as $key => $value) {
  		$retrun_array["$value->category"] = "$value->category";
  	}
  	return $retrun_array; 
 }
 
}

?>