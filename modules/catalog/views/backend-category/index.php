<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\search\CategoryProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['pageTitle'] = $this->title;
$this->params['pageSmallTitle'] = Yii::t('admin', 'manage');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('actions') ?>
<?= Html::a('<i class="fa fa-plus"></i> <span class="">'.Yii::t('user/admin', 'New Category').'</span>', ['create'], ['class' => 'btn btn-info btn-circle']); ?>
<?php $this->endBlock() ?>

<div class="category-product-index">
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'active',
                'format' => 'boolean',
                'filter' => [1 => 'Yes', 0 => 'No']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'min-width:100px;width:100px'],
                'template' => '{update} {delete}',
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>