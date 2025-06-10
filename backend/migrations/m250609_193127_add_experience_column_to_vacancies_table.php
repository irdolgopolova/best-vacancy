<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%vacancies}}`.
 */
class m250609_193127_add_experience_column_to_vacancies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%vacancies}}',
            'experience',
            $this->string(100)->notNull()->defaultValue('Не требуется')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%vacancies}}', 'experience');
    }
}
