<?php

namespace app\controllers;

use Yii;
use app\models\Products;
use app\models\Prices;
use app\models\Stores;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Products::find()
        ->select('product_name,category,product_url,product_image,prices.normal_price,prices.sale_price,FLOOR(((prices.normal_price - prices.sale_price) / prices.normal_price) * 100) as discount')
        ->leftJoin('prices', 'prices.product=products.id')
        ->where(['NOT LIKE', 'category', '%luggage%']);
        if(Yii::$app->request->post()){
            $data = Yii::$app->request->post();
            $stores = $data['stores'];
            $categories = $data['categories'];
            $min_price  = $data['min_price'];
            $max_price  = $data['max_price'];
            $discount   = !empty($data['discount'])?explode('-', $data['discount']):[];
            if(isset($discount[0]) && $discount[1] && $discount[0]!=0 && $discount[1]!=0){
                $query->andwhere('FLOOR(((prices.normal_price - prices.sale_price) / prices.normal_price) * 100) >'.$discount[0].' AND FLOOR(((prices.normal_price - prices.sale_price) / prices.normal_price) * 100) <'.$discount[1]);
            }
            if(!empty($stores)){
                $query->andwhere('products.store_id IN('.$stores.')');
            }
            if(!empty($categories)){
                $ary = explode(',', $categories);
                $or_ary = array('or');
                foreach ($ary as $key => $value) {
                    $or_ary[]=['LIKE', 'category', $value];
                }
                $query->andwhere($or_ary);
            }
            if(!empty($min_price) && !empty($max_price)){
                $query->andwhere("prices.sale_price>".$min_price.' AND prices.sale_price <'.$max_price);
            }else if(!empty($min_price)){
                $query->andwhere("prices.sale_price>".$min_price);
            }else if(!empty($max_price)){
                $query->andwhere('prices.sale_price <'.$max_price);
            }
            
        }
        $pages = new Pagination(['totalCount' => $query->count()]);
        $products = $query->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
        //echo "<pre>";print_r($query->createCommand()->getRawSql());die();
        if(Yii::$app->request->isAjax){
            return $this->renderPartial('product-listings', [
                'products' => $products,
                'pages'=>$pages
            ]);
        }else{
            return $this->render('index', [
                'products' => $products,
                'pages'=>$pages
            ]);
        }
        
       
        //echo "<pre>";print_r($products);die();
        
    }

    public function actionGetCategory(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $categories = Yii::$app->mycomponent->findcategories('store_id IN('.$data['stores'].')');
        return $categories;
    }
    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
