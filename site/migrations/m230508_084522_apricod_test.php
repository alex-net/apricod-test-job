<?php

use yii\db\Migration;

/**
 * Class m230508_084522_apricod_test
 */
class m230508_084522_apricod_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('studio', [
            'id' => $this->primaryKey()->comment('Ключки студии'),
            'name' => $this->string(50)->comment('Наименование'),
        ]);
        $this->addCommentOnTable('studio', 'Студи-разработчики игр');
        $this->createIndex('studio-name-ind', 'studio', ['name']);

        $this->createTable('cat', [
            'id' => $this->primaryKey()->comment('Ключик категории'),
            'name' => $this->string(50)->comment('Наименование'),
        ]);
        $this->addCommentOnTable('cat', 'Категории игр');
        $this->createIndex('cat-name-ind', 'cat', ['name']);


        $this->createTable('game', [
            'id' => $this->primaryKey()->comment('Ключик игры'),
            'name' => $this->string(100)->notNull()->comment('Наисенование'),
            'studio_id' => $this->integer()->notNull()->comment('ссылка на студию'),
        ]);
        $this->addCommentOnTable('game', 'Игры');
        $this->addForeignKey('game-studio-fk', 'game', ['studio_id'], 'studio', ['id'], 'CASCADE', 'CASCADE');
        $this->createIndex('game-name-ind', 'game', ['name']);



        $this->createTable('game_cat', [
            'id' => $this->primaryKey()->comment('Ключик связки'),
            'game_id' => $this->integer()->notNull()->comment('ссылка на игру'),
            'cat_id' => $this->integer()->notNull()->comment('ссылка на категорию'),
        ]);
        $this->addForeignKey('game_cats-game-fk', 'game_cat', ['game_id'], 'game', ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('game_cats-cat-fk', 'game_cat', ['cat_id'], 'cat', ['id'], 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (['game_cat', 'cat', 'game', 'studio'] as $tbl) {
            $this->dropTable($tbl);
        }
    }
}
