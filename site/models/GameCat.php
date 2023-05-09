<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * модель-связка объектов Игра и  категория ..
 *
 * @property int $id Ключик записи = нужен для ActiveRecord
 * @property int $game_id Клюючик игры
 * @property int $cat_id Ключик категоии
 */
class GameCat extends ActiveRecord
{
    public function rules()
    {
        return [
            [['game_id', 'cat_id'], 'required'],
            [['game_id', 'cat_id'], 'integer'],
            ['game_id', 'exist', 'targetClass' => Game::class, 'targetAttribute' => 'id'],
            ['cat_id', 'exist', 'targetClass' => Cat::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getCat()
    {
        return $this->hasOne(Cat::class, ['id' => 'cat_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }
}