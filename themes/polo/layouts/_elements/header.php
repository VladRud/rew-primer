<?php

/* @var \yii\web\View $this */


use yii\helpers\Url;
use yii\widgets\Menu;
?>
<header id="header" class="header-light">
    <div id="header-wrap">
        <div class="container">

            <!--LOGO-->
            <div id="logo">
                <a href="/" class="logo" data-dark-logo="/images/logo-dark.png">
                    <img src="/images/logo.png" alt="Polo Logo">
                </a>
            </div>
            <!--END: LOGO-->

            <!--MOBILE MENU -->
            <div class="nav-main-menu-responsive">
                <button class="lines-button x">
                    <span class="lines"></span>
                </button>
            </div>
            <!--END: MOBILE MENU -->


            <!--NAVIGATION-->
            <div class="navbar-collapse collapse main-menu-collapse navigation-wrap">
                <div class="container">
                    <nav id="mainMenu" class="main-menu mega-menu">
                        <?php
                        $module = Yii::$app->controller->module->id;
                        $controller = Yii::$app->controller->id;
                        $action = Yii::$app->controller->action->id;

                        echo Menu::widget([
                            'options' => [
                                'class' => 'main-menu nav nav-pills',
                            ],
                            'encodeLabels' => false,
                            'activeCssClass' => 'active',
                            'submenuTemplate' => "<ul class=\"dropdown-menu\">{items}</ul>",
                            'items' => [
                                ['label' => '<i class="fa fa-home"></i>', 'url' => ['/site/index']],
                                ['label' => Yii::t('app', 'Contact Us'), 'url' => ['/site/contact-us']],
                                ['label' => Yii::t('app', 'FAQ') , 'url' => ['/site/faq']],
                                [
                                    'label' => Yii::t('app', 'Sign Up'),
                                    'url' => ['/user/account/sign-up'],
                                    'active' => $module == 'user' && $controller == 'account' && ($action == 'invitation-request' || $action == 'sign-up'),
                                    'visible' => Yii::$app->user->isGuest,
                                ],
                                Yii::$app->user->isGuest ?
                                    [
                                        'label' => Yii::t('app', 'Sign In'),
                                        'url' => ['/user/account/login'],
                                        'active' => $module == 'user' && $controller == 'account' && $action == 'login',
                                        'visible' => Yii::$app->user->isGuest,
                                    ] :
                                    [
                                        'label' => Yii::$app->getUser()->getIdentity()->email,
                                        'url' => '#',
                                        'options' => [
                                            'class' => 'dropdown'
                                        ],
                                        'items' => [
                                            [
                                                'label' => \Yii::t('app', 'My Account'),
                                                'url' => ['/profile/index/account'],
                                            ],
                                            [
                                                'label' => \Yii::t('app', 'Log Out'),
                                                'url' => ['/user/account/logout'],
                                            ],
                                        ],
                                        'visible' => !Yii::$app->user->isGuest,
                                    ],
                            ],
                        ]);
                        ?>
                    </nav>
                </div>
            </div>
            <!--END: NAVIGATION-->
        </div>
    </div>
</header>
<div class="row">
    <?php
    if (Yii::$app->session->getFlash('success')) {
        ?>
        <div class="alert alert-success" role="alert">
            <strong><?= Yii::t('app', 'Well done!')?></strong>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
        <?php
    }
    ?>
</div>
<!-- END: HEADER -->