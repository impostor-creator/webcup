<?php
const DB_HOST = 'localhost';
const DB_NAME = 'serveur14_novasphere';
const DB_USER = 'serveur14_admin';     // <- from cPanel (usually prefixed)
const DB_PASS = 'root';       // <- from cPanel

const BASE_URL = 'https://serveur14.webcup.hodi.cloud';

if (session_status() === PHP_SESSION_NONE) session_start();

