<?php

declare(strict_types=1);

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Модель поиска вакансий
 *
 * @property int    $salary_from  Минимальная зарплата для фильтрации
 * @property int    $salary_to    Максимальная зарплата для фильтрации
 * @property string $sort_by      Поле для сортировки (created_at, salary, title)
 * @property string $sort_order   Порядок сортировки (asc, desc)
 */
class VacancySearch extends Vacancy
{
    public $salary_from;
    public $salary_to;
    public $sort_by;
    public $sort_order;


    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['title', 'description'], 'safe'],
            [['salary', 'salary_from', 'salary_to'], 'integer', 'min' => 0],
            [['sort_by'], 'in', 'range' => ['created_at', 'salary', 'title']],
            [['sort_order'], 'in', 'range' => ['asc', 'desc']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Поиск вакансий с фильтрацией и сортировкой
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = []): ActiveDataProvider
    {
        $query = Vacancy::find();

        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortOrder = $params['sort_order'] ?? 'desc';

        $allowedSortFields = ['created_at', 'salary', 'title'];
        $allowedSortOrders = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['per_page'] ?? 10,
                'page' => isset($params['page']) ? $params['page'] - 1 : 0,
            ],
            'sort' => [
                'defaultOrder' => [$sortBy => $sortOrder === 'desc' ? SORT_DESC : SORT_ASC],
                'attributes' => [
                    'id',
                    'title',
                    'salary',
                    'created_at',
                    'updated_at'
                ],
            ],
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'salary' => $this->salary,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        if (!empty($this->salary_from)) {
            $query->andFilterWhere(['>=', 'salary', $this->salary_from]);
        }

        if (!empty($this->salary_to)) {
            $query->andFilterWhere(['<=', 'salary', $this->salary_to]);
        }

        return $dataProvider;
    }

    /**
     * Получение статистики по вакансиям
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $query = Vacancy::find();

        return [
            'total_vacancies' => (int) $query->count(),
            'average_salary' => (float) $query->average('salary') ?: 0,
            'min_salary' => (int) $query->min('salary') ?: 0,
            'max_salary' => (int) $query->max('salary') ?: 0,
        ];
    }
}