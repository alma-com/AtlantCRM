<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date_start',
        'deadline',
        'planned_duration',
        'project_id',
    ];

    /**
     * Set permission
     * Command: php artisan permission:update 'App\Task'
     */
    public static function setPermissions()
    {
        return [
            'group' => ['tasks' => 'Задачи'],
            'permissions' => [
                'show' => 'Просмотр',
                'add' => 'Добавление',
                'edit' => 'Редактирование',
                'delete' => 'Удаление',
            ],
        ];
    }

    /**
     * assign director
     */
    public function assignDirector($director = '')
    {
        return $this;
    }


    /**
     * delete director
     */
    public function deleteDirector($director = '')
    {
        return $this;
    }


    /**
     * assign employee
     */
    public function assignEmployee($employee = '')
    {
        return $this;
    }


    /**
     * delete employee
     */
    public function deleteEmployee($employee = '')
    {
        return $this;
    }


    /**
     * assign watcher
     */
    public function assignWatchers($watcher = '')
    {
        return $this;
    }


    /**
     * delete watcher
     */
    public function deleteWatchers($watcher = '')
    {
        return $this;
    }

}
