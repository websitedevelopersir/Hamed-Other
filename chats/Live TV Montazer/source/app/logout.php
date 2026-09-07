<?php
require __DIR__.'/bootstrap.php';
use TV\Core\Auth; use TV\Core\Helpers;
Auth::logout();Helpers::redirect(Helpers::url('login.php'));
