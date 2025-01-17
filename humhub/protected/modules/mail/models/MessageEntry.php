<?php

namespace humhub\modules\mail\models;

use humhub\modules\mail\live\NewUserMessage;
use humhub\modules\mail\live\UserMessageDeleted;
use humhub\modules\mail\notifications\MailNotificationCategory;
use humhub\modules\notification\targets\BaseTarget;
use humhub\modules\notification\targets\MailTarget;
use Yii;
use humhub\components\ActiveRecord;
use humhub\modules\user\models\User;
use humhub\models\Setting;
use humhub\modules\mail\models\Message;

/**
 * This is the model class for table "message_entry".
 *
 * The followings are the available columns in table 'message_entry':
 * @property integer $id
 * @property integer $message_id
 * @property integer $user_id
 * @property integer $file_id
 * @property string $content
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property Message $message
 * @property User $user
 * @property File $file
 *
 * @package humhub.modules.mail.models
 * @since 0.5
 */
class MessageEntry extends ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'message_entry';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['message_id', 'user_id', 'content'], 'required'],
            [['message_id', 'user_id', 'file_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getMessage()
    {
        return $this->hasOne(Message::class, ['id' => 'message_id']);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

            // Updates the updated_at attribute
            $this->message->save();
        }

        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        foreach ($this->message->users as $user) {
            Yii::$app->live->send(new UserMessageDeleted([
                'contentContainerId' => $user->contentcontainer_id,
                'message_id' => $this->message_id,
                'entry_id' => $this->id,
                'user_id' => $user->id
            ]));
        }


        parent::afterDelete();
    }

    /**
     * Returns the first two lines of this entry.
     * Used in Inbox Overview.
     *
     * @return string
     */
    public function getSnippet()
    {

        $snippet = "";
        $lines = explode("\n", $this->content);

        if (isset($lines[0]))
            $snippet .= $lines[0] . "\n";
        if (isset($lines[1]))
            $snippet .= $lines[1] . "\n";

        return $snippet;
    }

    /**
     * Notify User in this message entry
     */
    public function notify()
    {
        $senderName = $this->user->displayName;
        
        foreach ($this->message->users as $user) {

            Yii::$app->live->send(new NewUserMessage([
                'contentContainerId' => $user->contentcontainer_id,
                'message_id' => $this->message_id,
                'user_id' => $user->id
            ]));

            /* @var $mailTarget BaseTarget */
            $mailTarget = Yii::$app->notification->getTarget(MailTarget::class);

            if(!$mailTarget || !$mailTarget->isCategoryEnabled(new MailNotificationCategory(), $user)) {
                return;
            }

            if ($user->id == $this->user_id) {
                continue;
            }

            Yii::setAlias('@mailmodule', Yii::$app->getModule('mail')->getBasePath());

            $mail = Yii::$app->mailer->compose([
                'html' => '@mailmodule/views/emails/NewMessageEntry',
                'text' => '@mailmodule/views/emails/plaintext/NewMessageEntry'
            ], [
                'message' => $this->message,
                'entry' => $this,
                'user' => $user,
                'sender' => $this->user,
                'originator' => $this->message->originator,
            ]);

            $mail->setFrom([Yii::$app->settings->get('mailer.systemEmailAddress') => Yii::$app->settings->get('mailer.systemEmailName')]);
            $mail->setTo($user->email);
            $mail->setSubject(Yii::t('MailModule.models_MessageEntry', 'New message in discussion from %displayName%', array('%displayName%' => $senderName)));
            $mail->send();
        }
    }

    public function canEdit()
    {
        return $this->created_by == Yii::$app->user->id;
    }
}
