<?php

/* @var \yii\base\View $this */
/* @var \app\modules\catalog\models\Product $model */

use yii\helpers\Html;
?>

<div class="col-md-4">
    <div class="product">
        <div class="product-description">
            <div class="product-category"><?php echo $model->categoryList() ?></div>
            <div class="product-title">
                <h3><?php echo Html::a($model->name, ['/catalog/catalog/single', 'id' => $model->id]) ?>
            </div>
            <div class="product-price">
                <ins><?php echo Html::encode($model->price) ?></ins>
            </div>
        </div>
    </div>
</div>