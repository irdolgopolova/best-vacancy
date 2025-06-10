<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель вакансий
 *
 * @property int    $id          ID вакансии
 * @property string $title       Название вакансии
 * @property string $description Описание вакансии
 * @property int    $salary      Зарплата
 * @property string $experience  Требуемый опыт работы
 * @property string $created_at  Дата создания записи
 * @property string $updated_at  Дата обновления записи
 */
class Vacancy extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'vacancies';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'salary'], 'required'],
            [['title'], 'string', 'min' => 3, 'max' => 255],
            [['description'], 'string', 'min' => 10, 'max' => 65535],
            [['title', 'description'], 'trim'],
            [['salary'], 'integer', 'min' => 1000, 'max' => 10000000],
            [['experience'], 'string', 'max' => 100],
            [['experience'], 'default', 'value' => 'Не требуется'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название вакансии',
            'description' => 'Описание',
            'salary' => 'Зарплата',
            'experience' => 'Требуемый опыт',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        return [
            'id',
            'title',
            'description',
            'salary',
            'created_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function extraFields(): array
    {
        return [
            'experience',
            'updated_at',
        ];
    }
}