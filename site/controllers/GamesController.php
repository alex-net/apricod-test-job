<?php

namespace app\controllers;

use app\models\GameCat;

/**
 * контроллер для управления записями сущности Игра
 */
class GamesController extends ObjController
{
    /**
     * обновление модели данными из post запроса
     *
     * @param      <type>  $model Объект модели, который надо заполнить данными  из post запроса
     *
     * @return     array   ( description_of_the_return_value )
     */
    protected function putPostDataToModel($model)
    {
        // для существующей модели ... убираем прежние связки
        if (!$model->isNewRecord) {
            GameCat::deleteAll(['game_id' => $model->id]);
        }
        $model->attributes = $this->request->post();
        if ($model->save()) {
            $ret = ['ok' => true, 'id' => $model->id];
            $catErrs = $model->setBindCats($this->request->post('cat_id', []));
            if ($catErrs) {
                $ret['cat-bind-errs'] = $catErrs;
            }
            return $ret;
        }
        return ['ok' => false, 'errors' => $model->errors];
    }
}