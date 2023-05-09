<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

abstract class BaseAr extends ActiveRecord
{

    protected  static function getQuery()
    {
        return static::find();
    }

    public static function list($params = [])
    {
        return new ActiveDataProvider([
            'query' => static::getQuery($params),
            'pagination' => [
                'pageParam' => 'p',
                'pageSizeParam' => 'pp',
            ]
        ]);
    }

    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'string', 'max' => 50],
            ['name', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
        ];
    }
}