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
  'readOrder' => 
  array (
    'type' => 0,
    'description' => 'Просмотр заявок',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateOrder' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение заявок',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readPayment' => 
  array (
    'type' => 0,
    'description' => 'Просмотр платежей',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updatePayment' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление платежей',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readDictionary' => 
  array (
    'type' => 0,
    'description' => 'Просмотр справочников',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updatePoint' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение точек маршрута',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateDictionary' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение справочников',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'readAgency' => 
  array (
    'type' => 0,
    'description' => 'Просмотр агентств',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'updateAgency' => 
  array (
    'type' => 0,
    'description' => 'Создание/изменение/удаление агентств',
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
  ),
  'orderAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readOrder',
      1 => 'updateOrder',
    ),
  ),
  'paymentAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readPayment',
      1 => 'updatePayment',
    ),
  ),
  'dictionaryAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readDictionary',
      1 => 'updateDictionary',
    ),
  ),
  'agencyAdmin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readAgency',
      1 => 'updateAgency',
    ),
  ),
  'manager' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'orderAdmin',
    ),
    'assignments' => 
    array (
      18 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'director' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'manager',
      1 => 'paymentAdmin',
    ),
  ),
  'admin' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'readDictionary',
      1 => 'updatePoint',
      2 => 'director',
      3 => 'userAdmin',
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
      0 => 'admin',
      1 => 'dictionaryAdmin',
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
