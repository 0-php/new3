<?php
return array(
    'columns' => array(
        'remind_id' => array(
            'type' => 'number',
            'required' => true,
            'autoincrement' => true,
        ),
        'member_id' => array(
            'type' => 'number',
        ),
        'product_id' => array(
            'type' => 'number',
        ),
        'goodsname' => array(
            'type' => 'string',
            'length' => 50,
        ),
        'remind_way' => array(
            'type' => 'string',
            'length' => 50,
        ),
        'goal' => array(
            'type' => 'string',
            'length' => 50,
        ),
        'savetime' => array(
            'type' => 'time',
        ),
        'remind_time' => array(
            'type' => 'time',
        ),
        'begin_time' => array(
            'type' => 'time',
        ),
    ),
    'primary' => 'remind_id',
);
