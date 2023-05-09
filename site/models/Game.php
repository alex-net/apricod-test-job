<?php

namespace app\models;

/**
 * Молель Игры
 *
 * @property int $id Ключик игры
 * @property string $name Наименование игры
 * @property int $studio_id Ссылка на студию-разработчика игры
 *
 */
class Game extends BaseAr
{

    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'string', 'max' => 100],
            ['studio_id', 'integer'],
            ['studio_id', 'exist', 'targetClass' => Studio::class, 'targetAttribute' => 'id'],

            [['name', 'studio_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'studio_id' => 'Студия',
        ]);
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'studioName' => function($m) {
                return $m->studio->name;
            },
            'cats' => function ($m) {
                $list = [];
                foreach ($m->cats as $cat) {
                    $list[] = $cat->name;
                }
                return $list;
            }
        ];
    }

    protected static function getQuery($filters = [])
    {
        $q = static::find()->with('studio', 'cats');
        if (!empty($filters['cat'])) {
            $q->joinWith('cats');
            $q->where(['cat.id' => $filters['cat']]);
        }
        return $q;
    }

    public function getStudio()
    {
        return $this->hasOne(Studio::class, ['id' => 'studio_id']);
    }

    public function getCats()
    {
        return $this->hasMany(Cat::class, ['id' => 'cat_id'])->via('gameCats');
    }

    public function getGameCats()
    {
        return $this->hasMany(GameCat::class, ['game_id' => 'id']);
    }

    public function setBindCats($catIds)
    {
        if (empty($catIds)) {
            return [];
        }
        if (!is_array($catIds)) {
            $catIds = [$catIds];
        }

        $catIds = array_unique($catIds);

        $errs = [];

        foreach ($catIds as $catId) {
            $gameCat = new GameCat([
                'cat_id' => $catId,
                'game_id' => $this->id,
            ]);
            if (!$gameCat->save()) {
                $errs[] = $gameCat->errors;
            }
        }

        return $errs;
    }

}