<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\BackendAsset;

BackendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <?php $this->beginBody() ?>
        <!-- BEGIN HEADER -->
        <?= $this->render('_elements/header'); ?>
        <!-- END HEADER -->

        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->

        <!-- BEGIN CONTAINER -->
        <div class="page-container">

            <!-- BEGIN SIDEBAR -->
            <?= $this->render('_elements/main_menu'); ?>
            <!-- END SIDEBAR -->

            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <?php
                        if (isset($this->params['breadcrumbs']) && count($this->params['breadcrumbs']) > 0) {
                            $this->params['breadcrumbs'] = array_merge([[
                            'label' => 'Home',
                            'url' => '/admin',
                            'template' => '<li> {link} <i class="fa fa-circle"></i></li>',
                                ]], $this->params['breadcrumbs']);
                        } else {
                            $this->params['breadcrumbs'] = [[
                            'label' => 'Home',
                            'url' => '/admin',
                            'template' => '<li> {link} </li>',
                            ]];
                        }

                        echo yii\widgets\Breadcrumbs::widget([
                            'options' => [
                                'class' => 'page-breadcrumb'
                            ],
                            'homeLink' => false,
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ])
                        ?>

                        <div class="page-toolbar"></div>
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title">
                        <?= isset($this->params['pageTitle']) ? $this->params['pageTitle'] : '' ?> <small><?= isset($this->params['pageSmallTitle']) ? $this->params['pageSmallTitle'] : '' ?></small>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <?= $content ?>
                </div> 
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->

        </div>
        <!-- END CONTAINER -->

        <?= $this->render('_elements/footer'); ?>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
