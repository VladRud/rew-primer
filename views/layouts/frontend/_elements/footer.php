<footer class="text-grey" id="footer" style="display: none">
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="float-left footer-button">
                        <?php echo \yii\helpers\Html::a('<span>Contact Us</span>', ['/contact/index/index'], ['class' => 'button black button-3d rounded icon-left'])?>
                        <a href="/faq" class="button black button-3d rounded icon-left"><span>FAQ</span></a>
                        <?php echo \yii\helpers\Html::a('<span>How it works</span>', ['/site/about'], ['class' => 'button black button-3d rounded icon-left'])?>
                        <?php echo \yii\helpers\Html::a('<span>Gift Cards</span>', ['/catalog/catalog/index'], ['class' => 'button black button-3d rounded icon-left'])?>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <?php echo \yii\helpers\Html::a('SIGN UP', ['/user/account/sign-up'], ['class' => 'button black button-3d rounded icon-left']) ?>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <?php echo \yii\helpers\Html::a('SIGN IN', ['/user/account/login'], ['class' => 'button black button-3d rounded icon-left']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-content">
        <div class="container">
            <div class="row">
                <div class="copyright-text col-md-4"> © 2017 RewardBucks.</div>
                <div class="col-privacy-links col-md-4">
                    <span class="terms-privacy-links clearfix">
                        <a href="<?= \yii\helpers\Url::to(['/site/terms', '#' => 'terms']); ?>">Terms</a>
                        <a href="<?= \yii\helpers\Url::to(['/site/terms', '#' => 'Privacy_policy']); ?>">Privacy Policy</a>
                    </span>
                </div>
                <div class="col-md-4">
                    <div class="social-icons">
                        <ul>
                            <li class="social-facebook" ><a href="<?= Yii::$app->keyStorage->get('social.fb', '#') ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li class="social-twitter"><a href="<?= Yii::$app->keyStorage->get('social.twitter', '#') ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li class="social-google"><a href="<?= Yii::$app->keyStorage->get('social.google', '#') ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li class="social-youtube"><a href="<?= Yii::$app->keyStorage->get('social.youtube', '#') ?>" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
<!--                             <li class="social-pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
                            <li class="social-vimeo"><a href="#"><i class="fa fa-vimeo-square"></i></a></li>
                            <li class="social-linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li class="social-dribbble"><a href="#"><i class="fa fa-dribbble"></i></a></li>
                            <li class="social-youtube"><a href="#"><i class="fa fa-youtube-play"></i></a></li>
                            <li class="social-rss"><a href="#"><i class="fa fa-rss"></i></a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>