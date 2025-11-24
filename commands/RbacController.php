<?php

namespace app\commands;

use app\rbac\rules\BookOwnerRule;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        $rule = new BookOwnerRule();
        $auth->add($rule);

        $ownBook = $auth->createPermission('ownBook');
        $ownBook->ruleName = $rule->name;
        $auth->add($ownBook);
    }
}