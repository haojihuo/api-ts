<?php
return [
    'jwt_secret' => getenv('JWT_SECRET') ?: 'replace_with_strong_secret',
    'jwt_exp' => 7200,
];
