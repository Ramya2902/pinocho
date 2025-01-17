<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\dashboard\controllers;

use humhub\components\Controller;
use humhub\modules\dashboard\components\actions\DashboardStreamAction;
use Yii;

class DashboardController extends Controller
{
    public function init()
    {
        $this->appendPageTitle(Yii::t('DashboardModule.base', 'Dashboard'));
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::class,
                'guestAllowedActions' => [
                    'index',
                    'stream'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => DashboardStreamAction::class,
                'activity' => false
            ],
            'activity-stream' => [
                'class' => DashboardStreamAction::class,
                'activity' => true
            ]

        ];
    }

    /**
     * Dashboard Index
     *
     * Show recent wall entries for this user
     */
    public function actionIndex()
    {
        $user_type = 'external';
        if(Yii::$app->user->getIdentity()->getGroups()->where("name = 'Internal'")->exists()) {
            $user_type = 'internal';
        }

        if (Yii::$app->user->isGuest) {
            return $this->render('index_guest', []);
        } else if (Yii::$app->user->isAdmin()) {
            return $this->render('index_admin', [
                'showProfilePostForm' => Yii::$app->getModule('dashboard')->settings->get('showProfilePostForm'),
                'contentContainer' => Yii::$app->user->getIdentity(),
                'user_id' => Yii::$app->user->id
            ]);
        }else {
            return $this->render('index', [
                'showProfilePostForm' => Yii::$app->getModule('dashboard')->settings->get('showProfilePostForm'),
                'contentContainer' => Yii::$app->user->getIdentity(),
                'user_id' => Yii::$app->user->id,
                'user_type' => $user_type
            ]);
        }
    }

}
