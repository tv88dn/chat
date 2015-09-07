<?php

use yii\db\Schema;
use yii\db\Migration;

class m150907_115345_poprigun_chat_user_rel_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=0';
        }

        $this->createTable('{{%poprigun_chat_user_rel}}', [
            'id'                    => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'chat_id'               => 'INT(11) NOT NULL',
            'chat_user_id'          => 'INT(11) NOT NULL',
            'status'                => 'TINYINT DEFAULT '.\poprigun\chat\interfaces\StatusInterface::STATUS_ACTIVE
        ], $tableOptions);

        $this->createIndex('idx-poprigun_chat_user_rel','{{%poprigun_chat_user_rel}}','chat_id, chat_user_id');
        $this->addForeignKey('fk-poprigun_chat-chat_id', '{{%poprigun_chat_user_rel}}', 'chat_id', '{{%poprigun_chat}}', 'id','CASCADE','CASCADE');
        $this->addForeignKey('fk-poprigun_chat-user-chat_user_id', '{{%poprigun_chat_user_rel}}', 'chat_user_id', '{{%poprigun_chat_user}}', 'id','CASCADE','CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk-poprigun_chat-chat_id', '{{%poprigun_chat_user_rel}}');
        $this->dropForeignKey('fk-poprigun_chat-user-chat_user_id', '{{%poprigun_chat_user_rel}}');
        $this->dropTable('{{%poprigun_chat_user_rel}}');
    }
}
