<?php

return [
  'authorization' => env('AUTHORIZATION', false),
  'token_expired' => 60 * 24,
  'admin_role' => 'admin',
  'paginate' => 50,
  'cache_expired' => [
    'default' => 24 * 60,
  ],
];
