<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Protagonist Default Settings
    |--------------------------------------------------------------------------
    |
    | Default values for the player character (主人公).
    | Family name: 風呂蔵（ふろくら）
    | Given name:  明空（あき）
    | Gender:      男
    |
    */

    'protagonist' => [
        'last_name'       => '風呂蔵',
        'last_name_kana'  => 'ふろくら',
        'first_name'      => '明空',
        'first_name_kana' => 'あき',
        'full_name'       => '風呂蔵 明空',
        'gender'          => 'male',
    ],

    /*
    |--------------------------------------------------------------------------
    | Initial Player Stats
    |--------------------------------------------------------------------------
    */

    'initial_stats' => [
        'hp'              => 100,
        'max_hp'          => 100,
        'academic_power'  => 0,
        'frontend_power'  => 0,
        'backend_power'   => 0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Game Timeline
    |--------------------------------------------------------------------------
    */

    'total_days'    => 30,
    'start_day'     => 1,
    'start_phase'   => 'morning',
    'start_weekday' => 'monday',

];
