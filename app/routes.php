<?php 

$app->get('/', function () use ($app) 			{ (new Home())->welcome(); 		})->name('welcome');
$app->get('/home/', function () use ($app) 		{ (new Home())->index(); 		})->name('home');
$app->get('/dosen/', function () use ($app) 		{ (new Home())->tampildosen(); 		})->name('home');
$app->get('/matakuliah/', function () use ($app) 		{ (new Home())->tampilmatkul(); 		})->name('home');
$app->get('/logout/', function () use ($app) 	{ (new Home())->logout(); 		})->name('logout');
$app->get('/rinciandosen/', function () use ($app) 		{ (new Home())->tampilrinciandosen(); 		})->name('home');
$app->get('/rincianmatkul/', function () use ($app) 		{ (new Home())->tampilrincianmatkul(); 		})->name('home');