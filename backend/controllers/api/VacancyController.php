<?php

declare(strict_types=1);

namespace app\controllers\api;

use Yii;
use app\models\Vacancy;
use app\models\VacancySearch;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\Cors;

class VacancyController extends Controller
{
    public $modelClass = 'app\models\Vacancy';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Добавляем CORS фильтр
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:3000'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        return $behaviors;
    }

    /**
     * Обработка OPTIONS запросов для CORS
     */
    public function actionOptions()
    {
        return '';
    }

    /**
     * Получение списка вакансий
     */
    public function actionIndex(): array
    {
        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagination = $dataProvider->pagination;

        return [
            'data' => $this->processFields($dataProvider->getModels()),
            'meta' => [
                'current_page' => $pagination->page + 1,
                'last_page' => $pagination->pageCount,
                'per_page' => $pagination->pageSize,
                'total' => $pagination->totalCount,
                'from' => $pagination->offset + 1,
                'to' => min($pagination->offset + $pagination->pageSize, $pagination->totalCount),
            ]
        ];
    }

    /**
     * Получение статистики вакансий
     */
    public function actionStats(): array
    {
        $searchModel = new VacancySearch();
        return $searchModel->getStatistics();
    }

    /**
     * Получение вакансии по ID
     */
    public function actionView(int $id): array
    {
        return $this->processFields($this->getModel($id));
    }

    /**
     * Создание новой вакансии
     */
    public function actionCreate(): Vacancy
    {
        $model = new Vacancy();

        if (!$model->load(Yii::$app->request->post(), '')) {
            throw new BadRequestHttpException('Некорректные данные запроса');
        }

        if (!$model->save()) {
            Yii::$app->response->statusCode = 422;
            throw new BadRequestHttpException('Ошибка валидации данных: ' . implode(', ', $model->getFirstErrors()));
        }

        Yii::$app->response->statusCode = 201;
        return $model;
    }

    /**
     * Обновление вакансии
     */
    public function actionUpdate(int $id): Vacancy
    {
        $model = $this->getModel($id);

        if (!$model->load(Yii::$app->request->post(), '')) {
            throw new BadRequestHttpException('Некорректные данные запроса');
        }

        if (!$model->save()) {
            Yii::$app->response->statusCode = 422;
            throw new BadRequestHttpException('Ошибка валидации данных: ' . implode(', ', $model->getFirstErrors()));
        }

        return $model;
    }

    /**
     * Удаление вакансии
     */
    public function actionDelete(int $id): array
    {
        $model = $this->getModel($id);

        if (!$model->delete()) {
            throw new BadRequestHttpException('Не удалось удалить вакансию');
        }

        Yii::$app->response->statusCode = 200;
        return ['success' => true];
    }

    /**
     * @param array|Vacancy $data
     * @return array
     */
    private function processFields($data): array
    {
        $fieldsParam = trim(Yii::$app->request->get('fields', ''));

        if (!$fieldsParam) {
            return is_array($data)
                ? array_map(function($model) { return $model->toArray(); }, $data)
                : $data->toArray();
        }

        $fields = array_map('trim', explode(',', $fieldsParam));

        return is_array($data)
            ? array_map(function($model) use ($fields) { return $model->toArray([], $fields); }, $data)
            : $data->toArray([], $fields);
    }

    /**
     * Метод для получения модели вакансии по id
     *
     * @param int $id
     * @return Vacancy
     * @throws NotFoundHttpException
     */
    private function getModel(int $id): Vacancy
    {
        if ($model = Vacancy::findOne($id)) {
            return $model;
        }

        throw new NotFoundHttpException('Вакансия не найдена');
    }
}