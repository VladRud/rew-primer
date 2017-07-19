<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\FrontAsset;

FrontAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?= $this->render('_elements/head'); ?>
<body class="wide">
<?php $this->beginBody(); ?>
    <?= $this->render('_elements/header'); ?>
    <?= $content ?>
    <?= $this->render('_elements/footer'); ?>
    <a class="gototop gototop-button" href="#"><i class="fa fa-chevron-up"></i></a>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>