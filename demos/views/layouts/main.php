<?php
use yii\helpers\Html;

$this->beginPage() ?>
    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?? 'en' ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

