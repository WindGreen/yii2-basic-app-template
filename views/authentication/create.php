<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Authentication */

$this->title = 'Create Authentication';
$this->params['breadcrumbs'][] = ['label' => 'Authentications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authentication-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
