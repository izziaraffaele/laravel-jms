<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configuring a Cache Directory
    | The serializer collects several metadata about your objects from various sources such as YML, 
    | XML, or annotations. In order to make this process as efficient as possible, it is 
    | encourage to let the serializer cache that information. For that, you can 
    | configure a cache directory:
    */

    'cache'       => storage_path('app/jms/cache'),

    /*
    |--------------------------------------------------------------------------
    | Custom handlers
    |--------------------------------------------------------------------------
    |
    | Handlers allow you to change the serialization, or deserialization process for a single type/format combination.
    | Add each custom handler in the array below:
    */
    'handlers'    => [
      
    ],
];
