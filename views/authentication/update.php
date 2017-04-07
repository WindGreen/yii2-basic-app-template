<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Authentication */

$this->title = 'Update Authentication: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Authentications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'identity_type' => $model->identity_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authentication-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
