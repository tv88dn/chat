<?php

namespace poprigun\chat\models;

use Yii;

/**
 * This is the model class for table "poprigun_chat_user_rel".
 *
 * @property integer $id
 * @property integer $chat_id
 * @property integer $chat_user_id
 *
 * @property PoprigunChatUser $chatUser
 * @property PoprigunChat $chat
 */
class PoprigunChatUserRel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poprigun_chat_user_rel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat_id', 'chat_user_id'], 'required'],
            [['chat_id', 'chat_user_id'], 'integer'],
            [['chat_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoprigunChatUser::className(), 'targetAttribute' => ['chat_user_id' => 'id']],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoprigunChat::className(), 'targetAttribute' => ['chat_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'chat_user_id' => 'Chat User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatUser()
    {
        return $this->hasOne(PoprigunChatUser::className(), ['id' => 'chat_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(PoprigunChat::className(), ['id' => 'chat_id']);
    }

    public static function setStatus($dialog_id, $status, $user_id = null)
    {
        $user_id = $user_id ? $user_id : Yii::$app->user->id;
        $relations = self::find()->joinWith('chatUser')
            ->where(['poprigun_chat_user.user_id'=>$user_id, 'poprigun_chat_user.dialog_id'=>$dialog_id])
            ->all();
        $res = true;
        for($i = 0; $i < count($relations); $i++)
        {
            $relations[$i]->status = $status;
            $res = $res && $relations[$i]->save();
        }
        return $res;
    }
}