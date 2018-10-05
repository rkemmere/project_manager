<?php

// Create tables
rex_sql_table::get(rex::getTable('project_manager_domain_psi'))
    //->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('domain', 'varchar(255)', false, null, ''))->setPrimaryKey('domain')
    ->ensureColumn(new rex_sql_column('raw', 'text', true))
    ->ensureColumn(new rex_sql_column('createdate', 'timestamp', false, '0000-00-00 00:00:00', 'on update CURRENT_TIMESTAMP'))
    ->ensureColumn(new rex_sql_column('score_desktop', 'text'))
    ->ensureColumn(new rex_sql_column('score_mobile', 'text'))    
    ->ensureColumn(new rex_sql_column('status', 'text'))
    ->ensure();
