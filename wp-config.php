<?php
/**
 * Основные параметры WordPress.
 *
 * Этот файл содержит следующие параметры: настройки MySQL, префикс таблиц,
 * секретные ключи и ABSPATH. Дополнительную информацию можно найти на странице
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Кодекса. Настройки MySQL можно узнать у хостинг-провайдера.
 *
 * Этот файл используется скриптом для создания wp-config.php в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать этот файл
 * с именем "wp-config.php" и заполнить значения вручную.
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'eNotary_new');

/** Имя пользователя MySQL */
define('DB_USER', 'enots');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'Lazy54321');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'u/zwOc^$(`IdlU.?0,rwLX5#4T-c&T<VDhqv4nl bYMP~+Ed-*-<.Da:;D@5R qg');
define('SECURE_AUTH_KEY',  '#:i)yGApTLd?frhgOPIT6:JH)6Zi-ivck@bzT+NK;C(BX[}m8wZSmL(A:Er-S9g$');
define('LOGGED_IN_KEY',    '~u6-g|q:%y[fN|[l,J1o@Ra$>{!qumP%tQUmRMsG(yvi p{n1tI;j*>p_{W#D:6H');
define('NONCE_KEY',        'iv7tu3p_Gw^x:Q|=*L0|Q2h4!-U{qlu]|&+X-|8GIKm6?`)4l|lL KlQ={ZIlwSp');
define('AUTH_SALT',        'TN>E[k/Hk{uW+GPy]cpz3)sidGijiU|c&bzCMi]%Y_!g{vQ[T#2<OHeAH?.2b7a$');
define('SECURE_AUTH_SALT', 'C(}N]O[`JF(@&BXx3se2f`JFu7/e|&.)]UZ<~%y+KfaV3$Qt1A|57?|0W6!gXcO8');
define('LOGGED_IN_SALT',   '+JIR?(V?JWf{Zl&Oe#$+Y>y;Hb>-` qYX/xm@cc W|.b02a/.fxH?g+%Goza(yrT');
define('NONCE_SALT',       'S.,-wcd8+0+4nfBj7H<Br)!=iomIN:|i)!l1&knma{3!g.?e!Tb}PPUG[h3_Hb7|');
define( 'WP_MEMORY_LIMIT', '256M' );
/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'en_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
