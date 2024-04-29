<?php 

class JWTPBM_DbTables {
    public static $tbl_refresh_tokens = 'refresh_tokens';


    public function __construct() {
    }

     /****
     * @param $table_name 
     * @return void 
    */
    public static function create_refresh_tokens_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$tbl_refresh_tokens;

        try {
            if (self::is_table_exists($table_name)) return;

            $sql = "CREATE TABLE $table_name (
            `id` int NOT NULL AUTO_INCREMENT,
            `user_id` int NOT NULL,
            `token` varchar(255) NOT NULL,
            `expires` bigint unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
            )";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        } catch (\Exception $e) {
            return new WP_Error('table_creatioin_faild', $e->getMessage(), array('status' => 500));
        }
    }

    /****
     * @param $table_name 
     * @return Boolean 
    */
    public static function is_table_exists($table_name) {
        global $wpdb;
        $sql = "SHOW TABLES LIKE '$table_name'";
        $result = $wpdb->get_var($sql);
        return $result === $table_name;
    }
    

    

}