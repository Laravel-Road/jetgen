<?php

return [
    'blueprint_types' => [
        'increments', 'integerIncrements', 'tinyIncrements', 'smallIncrements', 'mediumIncrements', 'bigIncrements',
        'char', 'string', 'text', 'mediumText', 'longText',
        'integer', 'tinyInteger', 'smallInteger', 'mediumInteger', 'bigInteger',
        'unsignedInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger', 'unsignedBigInteger',
        'float', 'double', 'decimal', 'unsignedDecimal',
        'boolean',
        'enum', 'set',
        'json', 'jsonb',
        'date', 'dateTime', 'dateTimeTz',
        'time', 'timeTz', 'timestamp', 'timestampTz', 'timestamps',
        'timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz',
        'year',
        'binary',
        'uuid',
        'ipAddress',
        'macAddress',
        'geometry', 'point', 'lineString', 'polygon', 'geometryCollection', 'multiPoint', 'multiLineString', 'multiPolygon', 'multiPolygonZ',
        'computed',
        'morphs', 'nullableMorphs', 'uuidMorphs', 'nullableUuidMorphs',
        'rememberToken',
        'foreign', 'foreignId', 'foreignIdFor', 'foreignUuid',
    ],

    'string_types' => [
        'char', 'string', 'text', 'mediumText', 'longText', 'json', 'jsonb',
    ],

    'integer_types' => [
        'increments', 'integerIncrements', 'tinyIncrements', 'smallIncrements', 'mediumIncrements', 'bigIncrements',
        'integer', 'tinyInteger', 'smallInteger', 'mediumInteger', 'bigInteger',
        'unsignedInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger', 'unsignedBigInteger',
    ],

    'float_types' => [
        'float', 'double', 'decimal', 'unsignedDecimal',
    ],

    'date_types' => [
        'date', 'dateTime', 'dateTimeTz',
        'time', 'timeTz', 'timestamp', 'timestampTz', 'timestamps',
        'timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz',
        'year',
    ],

    'foreign_types' => [
        'foreign', 'foreignId', 'foreignIdFor', 'foreignUuid',
    ],

];
