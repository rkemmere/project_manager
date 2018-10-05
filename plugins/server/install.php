<?php

// Create tables
rex_sql_table::get(rex::getTable('project_manager_domain'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('name', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('domain', 'varchar(255)', true))
    ->ensureColumn(new rex_sql_column('api_key', 'varchar(32)', true))
    ->ensureColumn(new rex_sql_column('is_ssl', 'tinyint', true))
    ->ensureColumn(new rex_sql_column('description', 'text'))
    ->ensureColumn(new rex_sql_column('cms', 'tinyint', true))
    ->ensureColumn(new rex_sql_column('status', 'text'))
    ->ensureColumn(new rex_sql_column('createdate', 'timestamp', false, '0000-00-00 00:00:00', 'default CURRENT_TIMESTAMP'))
    ->ensureColumn(new rex_sql_column('updatedate', 'timestamp', false, '0000-00-00 00:00:00', 'on update CURRENT_TIMESTAMP'))
    ->ensureColumn(new rex_sql_column('logdate', 'timestamp', false, '0000-00-00 00:00:00', 'default CURRENT_TIMESTAMP'))
    ->ensure();

rex_sql_table::get(rex::getTable('project_manager_logs'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('domain_id', 'int(10) unsigned', false))
   ->ensureForeignKey(new rex_sql_foreign_key('domain_id', rex::getTable('project_manager_domain'), ['domain_id' => 'id']))
    ->ensureColumn(new rex_sql_column('raw', 'text'))
    ->ensureColumn(new rex_sql_column('createdate', 'timestamp', false, '0000-00-00 00:00:00', 'default CURRENT_TIMESTAMP'))
    ->ensure();