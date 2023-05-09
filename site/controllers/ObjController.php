<?php

namespace app\controllers;

use yii\web\Controller;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Базовый класс контроллекра для управления данными моделей через Rest API
 */
abstract class ObjController extends Controller
{
    public $enableCsrfValidation = false;
    protected $modelClass;


    public function init()
    {
        parent::init();
        // определение формата ответа ..
        $format = ltrim($this->request->get('format', 'xml'), '.');
        if (!in_array($format, ['json', 'xml'])) {
            $format =  Yii::$app->params['default-format'];
        }
        $this->response->format = constant(Response::class . '::FORMAT_' . strtoupper($format));
        // определение ккласса используемой модели
        $this->modelClass = 'app\\models\\' . Inflector::id2camel( rtrim($this->id, 's'));
    }


    /**
     * коррекция кода ответа в зависимости от результата ответа ...
     *
     * @param      \yii\base\Action   $act    Объект  тействия
     * @param      mixed  $res    результат выполнения действвия
     *
     * @return     mixed  ( description_of_the_return_value )
     */
    public function afterAction($act, $res)
    {
        $res = parent::afterAction($act, $res);
        if (empty($res['ok'])) {
            $this->response->statusCode = 500;
        }
        return $res;
    }

    /**
     * запрос обънкта модели . по идентификатору ..
     *
     * @throws     \yii\web\NotFoundHttpException  Если ничего найти не получилось ... - страшно ругаемся .. !
     *
     * @return     \yii\db\ActiveRecord                          Объект модели
     */
    protected function getModel()
    {
        $id = $this->request->get('id');
        if ($id) {
            $m = $this->modelClass::find()->where(['id' => $id])->limit(1)->one();
        }
        if (empty($m)) {
            throw new NotFoundHttpException('Страница не нейдена');
        }
        return $m;
    }

    /**
     *
     * отображение списка сущностей .. для модели..
     *
     * @return     array  ( description_of_the_return_value )
     */
    public function actionList()
    {
        return [
            'ok' => true,
            'list' => $this->modelClass::list($this->request->get())->models,
        ];
    }

    /**
     * обновляем объект модели из post данных запроса ...
     *
     * @param      <type>  $model  Объект модели для обновления полей ...
     *
     * @return     array   ( description_of_the_return_value )
     */
    protected function putPostDataToModel($model)
    {
        $model->attributes = $this->request->post();
        if ($model->save()) {
            return ['ok' => true, 'id' => $model->id];
        }
        return ['ok' => false, 'errors' => $model->errors];
    }


    /**
     * Добавление новых лементов в таблицу сущностей ...
     *
     * @return     array  Результат работы добавления записи
     */
    public function actionAdd()
    {
        $model = new $this->modelClass;
        return $this->putPostDataToModel($model);
    }


    /**
     * обновление существующей записи
     *
     * @return     array  Результат работы обновления записи
     */
    public function actionUpdate()
    {
        $model = $this->getModel();
        $res = $this->putPostDataToModel($model);
        unset($res['id']);
        return $res;
    }


    /**
     * Удаление записи
     *
     * @return     array  ( description_of_the_return_value )
     */
    public function actionDelete()
    {
        $model = $this->getModel();
        if ($model->delete()) {
            return ['ok' => true];
        }
        return ['ok' => 'false', 'error' => 'Сущность не найдена'];
    }
}