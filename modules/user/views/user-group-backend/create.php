<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserGroup */

$this->title = 'Create User Group';
$this->params['breadcrumbs'][] = [
    'label' => 'User Groups',
    'url' => ['index'],
    'template' => '<li> {link} <i class="fa fa-circle"></i></li>'
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-group-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>


