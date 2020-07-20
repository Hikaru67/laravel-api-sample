<?php

return [
  'authorization' => env('AUTHORIZATION', false),
  'token_expired' => 60 * 24,
  'admin_role' => 'admin',
  'paginate' => 50,
  'encode_condition' => env('ENCODE_CONDITION', true),
  'cache_expired' => [
    'default' => 24 * 60,
  ],
];
