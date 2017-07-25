<?php

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\Product */

$this->title = Yii::t('app', 'Create Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-create">
    <?= $this->render('_form', [
        'model' => $model,
        'categoryList' => $categoryList
    ]) ?>

</div>