<?php

require_once JWTPBM_PLUGIN_DIR . '/api/jwt_manager.php';
require_once JWTPBM_PLUGIN_DIR . '/api/db_tables.php';

// posts
require_once JWTPBM_PLUGIN_DIR . '/api/posts.php';
require_once JWTPBM_PLUGIN_DIR . '/api/wp_options.php';


// Register Routes
require_once JWTPBM_PLUGIN_DIR . '/api/authenticaton.php';
require_once JWTPBM_PLUGIN_DIR . '/api/routes.php';
