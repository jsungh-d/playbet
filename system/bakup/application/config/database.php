<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  | DATABASE CONNECTIVITY SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings needed to access your database.
  |
  | For complete instructions please consult the 'Database Connection'
  | page of the User Guide.
  |
  | -------------------------------------------------------------------
  | EXPLANATION OF VARIABLES
  | -------------------------------------------------------------------
  |
  |	['dsn']      The full DSN string describe a connection to the database.
  |	['hostname'] The hostname of your database server.
  |	['username'] The username used to connect to the database
  |	['password'] The password used to connect to the database
  |	['database'] The name of the database you want to connect to
  |	['dbdriver'] The database driver. e.g.: mysqli.
  |			Currently supported:
  |				 cubrid, ibase, mssql, mysql, mysqli, oci8,
  |				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
  |	['dbprefix'] You can add an optional prefix, which will be added
  |				 to the table name when using the  Query Builder class
  |	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
  |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
  |	['cache_on'] TRUE/FALSE - Enables/disables query caching
  |	['cachedir'] The path to the folder where cache files should be stored
  |	['char_set'] The character set used in communicating with the database
  |	['dbcollat'] The character collation used in communicating with the database
  |				 NOTE: For MySQL and MySQLi databases, this setting is only used
  | 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
  |				 (and in table creation queries made with DB Forge).
  | 				 There is an incompatibility in PHP with mysql_real_escape_string() which
  | 				 can make your site vulnerable to SQL injection if you are using a
  | 				 multi-byte character set and are running versions lower than these.
  | 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
  |	['swap_pre'] A default table prefix that should be swapped with the dbprefix
  |	['encrypt']  Whether or not to use an encrypted connection.
  |	['compress'] Whether or not to use client compression (MySQL only)
  |	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
  |							- good for ensuring strict SQL while developing
  |	['failover'] array - A array with 0 or more data for connections if the main should fail.
  |	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
  | 				NOTE: Disabling this will also effectively disable both
  | 				$this->db->last_query() and profiling of DB queries.
  | 				When you run a query, with this setting set to TRUE (default),
  | 				CodeIgniter will store the SQL statement for debugging purposes.
  | 				However, this may cause high memory usage, especially if you run
  | 				a lot of SQL queries ... disable this to avoid that problem.
  |
  | The $active_group variable lets you choose which connection group to
  | make active.  By default there is only one group (the 'default' group).
  |
  | The $query_builder variables lets you determine whether or not to load
  | the query builder class.
 */

$active_group = 'default';
$query_builder = TRUE;

$db['TIMING_CUSTOMER']['port'] = 13306;
$db['TIMING_CUSTOMER']['hostname'] = '222.239.193.9';
$db['TIMING_CUSTOMER']['username'] = 'webusr';
$db['TIMING_CUSTOMER']['password'] = 'webusr@PW';
$db['TIMING_CUSTOMER']['database'] = 'timing_customer';
$db['TIMING_CUSTOMER']['dbdriver'] = 'mysqli';
$db['TIMING_CUSTOMER']['dbprefix'] = '';
$db['TIMING_CUSTOMER']['pconnect'] = FALSE;
$db['TIMING_CUSTOMER']['db_debug'] = TRUE;
$db['TIMING_CUSTOMER']['cache_on'] = FALSE;
$db['TIMING_CUSTOMER']['cachedir'] = '';
$db['TIMING_CUSTOMER']['char_set'] = 'utf8';
$db['TIMING_CUSTOMER']['dbcollat'] = 'utf8_general_ci';
$db['TIMING_CUSTOMER']['swap_pre'] = '';
$db['TIMING_CUSTOMER']['autoinit'] = TRUE;
$db['TIMING_CUSTOMER']['stricton'] = FALSE;

$db['TIMING_NEWS']['port'] = 13306;
$db['TIMING_NEWS']['hostname'] = '222.239.193.9';
$db['TIMING_NEWS']['username'] = 'webusr';
$db['TIMING_NEWS']['password'] = 'webusr@PW';
$db['TIMING_NEWS']['database'] = 'timing_news';
$db['TIMING_NEWS']['dbdriver'] = 'mysqli';
$db['TIMING_NEWS']['dbprefix'] = '';
$db['TIMING_NEWS']['pconnect'] = FALSE;
$db['TIMING_NEWS']['db_debug'] = TRUE;
$db['TIMING_NEWS']['cache_on'] = FALSE;
$db['TIMING_NEWS']['cachedir'] = '';
$db['TIMING_NEWS']['char_set'] = 'utf8';
$db['TIMING_NEWS']['dbcollat'] = 'utf8_general_ci';
$db['TIMING_NEWS']['swap_pre'] = '';
$db['TIMING_NEWS']['autoinit'] = TRUE;
$db['TIMING_NEWS']['stricton'] = FALSE;

$db['TIMING_STATS']['port'] = 13306;
$db['TIMING_STATS']['hostname'] = '222.239.193.9';
$db['TIMING_STATS']['username'] = 'webusr';
$db['TIMING_STATS']['password'] = 'webusr@PW';
$db['TIMING_STATS']['database'] = 'timing_stats';
$db['TIMING_STATS']['dbdriver'] = 'mysqli';
$db['TIMING_STATS']['dbprefix'] = '';
$db['TIMING_STATS']['pconnect'] = FALSE;
$db['TIMING_STATS']['db_debug'] = TRUE;
$db['TIMING_STATS']['cache_on'] = FALSE;
$db['TIMING_STATS']['cachedir'] = '';
$db['TIMING_STATS']['char_set'] = 'utf8';
$db['TIMING_STATS']['dbcollat'] = 'utf8_general_ci';
$db['TIMING_STATS']['swap_pre'] = '';
$db['TIMING_STATS']['autoinit'] = TRUE;
$db['TIMING_STATS']['stricton'] = FALSE;

$db['FX_TIMIBRKA']['port'] = 13306;
$db['FX_TIMIBRKA']['hostname'] = '222.239.193.9';
$db['FX_TIMIBRKA']['username'] = 'webusr';
$db['FX_TIMIBRKA']['password'] = 'webusr@PW';
$db['FX_TIMIBRKA']['database'] = 'fx_timbrka';
$db['FX_TIMIBRKA']['dbdriver'] = 'mysqli';
$db['FX_TIMIBRKA']['dbprefix'] = '';
$db['FX_TIMIBRKA']['pconnect'] = FALSE;
$db['FX_TIMIBRKA']['db_debug'] = TRUE;
$db['FX_TIMIBRKA']['cache_on'] = FALSE;
$db['FX_TIMIBRKA']['cachedir'] = '';
$db['FX_TIMIBRKA']['char_set'] = 'utf8';
$db['FX_TIMIBRKA']['dbcollat'] = 'utf8_general_ci';
$db['FX_TIMIBRKA']['swap_pre'] = '';
$db['FX_TIMIBRKA']['autoinit'] = TRUE;
$db['FX_TIMIBRKA']['stricton'] = FALSE;

//ci_sessions 테이블 사용을 위해

$db['default']['port'] = 13306;
$db['default']['hostname'] = '222.239.193.9';
$db['default']['username'] = 'webusr';
$db['default']['password'] = 'webusr@PW';
$db['default']['database'] = 'timing_customer';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */