<?php
return array (
  'readUser' => 
  array (
    'type' => 0,
    'description' => 'Просмотр пользователей',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateUser' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление пользователей',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readSection' => 
  array (
    'type' => 0,
    'description' => 'Просмотр групп бизнеса',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateSection' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление групп бизнеса',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readDept' => 
  array (
    'type' => 0,
    'description' => 'Просмотр подразделений',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateDept' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление подразделений',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readAll' => 
  array (
    'type' => 0,
    'description' => 'Только редактирование',
    'bizRule' => NULL,
    'data' => NULL,
    'assignments' => 
    array (
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      8 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      9 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      17 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'updateAll' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление',
    'bizRule' => NULL,
    'data' => NULL,
    'assignments' => 
    array (
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      8 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'getNotify' => 
  array (
    'type' => 0,
    'description' => 'Получение уведомлений',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readLog' => 
  array (
    'type' => 0,
    'description' => 'Просмотр журнала',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'debug' => 
  array (
    'type' => 0,
    'description' => 'Отладка',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'userAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readUser',
      1 => 'updateUser',
    ),
    'assignments' => 
    array (
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'sectionAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readSection',
      1 => 'updateSection',
    ),
    'assignments' => 
    array (
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      17 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'deptAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readDept',
      1 => 'updateDept',
    ),
    'assignments' => 
    array (
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      17 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'notify' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'getNotify',
    ),
    'assignments' => 
    array (
      2 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'root' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readLog',
      1 => 'debug',
      2 => 'userAdmin',
      3 => 'sectionAdmin',
      4 => 'deptAdmin',
      5 => 'notify',
      6 => 'updateAll',
      7 => 'readAll',
    ),
    'assignments' => 
    array (
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
);
