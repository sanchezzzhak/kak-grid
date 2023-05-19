<?php

use kak\widgets\grid\GridView;
?>

<?= GridView::widget([
        'dataProvider' => $provider,
])?>