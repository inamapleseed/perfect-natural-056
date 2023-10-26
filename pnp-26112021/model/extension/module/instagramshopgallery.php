<?php
class ModelExtensionModuleInstagramShopGallery extends Model
{
    private $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/instagramshopgallery');
        $this->module = $this->config->get('instagramshopgallery');
    }

    /**
     * Get media data (fetch Database)
     */
    public function getMedias($page = 1, $store_id = 0, $limit = 36, $select = '*, media_id AS id')
    {
        $query = $this->db->query(
            "SELECT " . $select . ", (SELECT COUNT(*) FROM `" . DB_PREFIX . "instagramshopgallery_to_product` isgp WHERE isgp.shortcode = isg.shortcode) as product_count
            FROM `" . DB_PREFIX . "instagramshopgallery` isg
                LEFT JOIN `" . DB_PREFIX . "instagramshopgallery_to_store` isg2s ON (isg.shortcode = isg2s.shortcode)
            WHERE isg2s.store_id = " . (int)$store_id . "
            GROUP BY isg.shortcode
            ORDER BY isg.timestamp DESC, isg.updated DESC
            LIMIT " . (int)(($page-1) * $limit) . ", " . (int)$limit
        );

        return $query->rows;
    }

    public function getMediasTotal($store_id = 0)
    {
        $query = $this->db->query(
            "SELECT COUNT(isg.shortcode) AS total
            FROM `" . DB_PREFIX . "instagramshopgallery` isg
            LEFT JOIN `" . DB_PREFIX . "instagramshopgallery_to_store` isg2s ON (isg.shortcode = isg2s.shortcode)
            WHERE isg2s.store_id = " . (int)$store_id
        );

        return $query->row['total'];
    }

    /**
     * Get all media summary
     * (For fetch Instagram media badge info)
     */
    public function getMediaSummary($store_id)
    {
        $data = array();
        $results = $this->getMedias(1, $store_id, 5000, 'isg.media_id, isg.shortcode, isg.approve');

        foreach ($results as $result) {
            $data[$result['shortcode']] = $result;
        }

        return $data;
    }

    /**
     * Get media detailed info
     */
    public function getMedia($shortcode, $param)
    {
        $data = array();
        $query = $this->db->query(
            "SELECT *
            FROM `" . DB_PREFIX . "instagramshopgallery` isg
            WHERE isg.shortcode = '" . $this->db->escape($shortcode) . "'"
        );

        if ($query->num_rows) {
            $data = array_replace_recursive($this->module['setting']['igpost'], $query->row, $param);
            $data['related_products'] = $this->getRelatedProduct($shortcode);
            $data['stores'] = $this->getMediaStore($shortcode);
        } else {
            $data = array_replace_recursive($this->module['setting']['igpost'], $param);
        }

        return $data;
    }

    /**
     * Get media related product
     */
    public function getRelatedProduct($shortcode)
    {
        $products = array();
        $query = $this->db->query(
            "SELECT isgp.*, pd.name
            FROM `" . DB_PREFIX . "instagramshopgallery_to_product` isgp
            LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = isgp.product_id)
            WHERE isgp.shortcode = '" . $this->db->escape($shortcode) . "'
                AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            LIMIT 0, 100"
        );

        foreach ($query->rows as $product) {
            $products[] = $product;
        }

        return $products;
    }

    public function getMediaStore($shortcode)
    {
        $store = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instagramshopgallery_to_store WHERE shortcode = '" . $this->db->escape($shortcode) . "'");

        foreach ($query->rows as $result) {
            $store[] = $result['store_id'];
        }

        return $store;
    }

    public function updateMedia($param)
    {
        $this->deleteMedia($param['data']['shortcode']);

        $param = array_replace_recursive($this->module['setting']['igpost'], $param);

        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "instagramshopgallery` SET
            `media_id`      = '" . $this->db->escape($param['data']['media_id']) . "',
            `approve`        = '" . (int)$param['approve'] . "',
            `shortcode`      = '" . $this->db->escape($param['data']['shortcode']) . "',
            `permalink`      = '" . $this->db->escape($param['data']['permalink']) . "',
            `media_type`     = '" . $this->db->escape(strtolower($param['data']['media_type'])) . "',
            `media_url`     = '" . $this->db->escape($param['data']['media_url']) . "',
            `username`       = '" . $this->db->escape($param['data']['username']) . "',
            `caption`        = '" . $this->db->escape($param['data']['caption']) . "',
            `timestamp`      = '" . $this->db->escape($param['data']['timestamp']) . "',
            `updated`        = NOW()"
        );

        if (!empty($param['related_products'])) {
            foreach ($param['related_products'] as $product_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "instagramshopgallery_to_product` SET shortcode = '" . $this->db->escape($param['data']['shortcode']) . "', product_id = '" . (int)$product_id . "'");
            }
        }

        if (!empty($param['stores'])) {
            foreach ($param['stores'] as $store_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "instagramshopgallery_to_store` SET shortcode = '" . $this->db->escape($param['data']['shortcode']) . "', store_id = '" . (int)$store_id . "'");
            }
        }
    }

    public function deleteMedia($shortcode)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "instagramshopgallery` WHERE shortcode = '" . $this->db->escape($shortcode) . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "instagramshopgallery_to_product` WHERE shortcode = '" . $this->db->escape($shortcode) . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "instagramshopgallery_to_store` WHERE shortcode = '" . $this->db->escape($shortcode) . "'");
    }

    public function haveMigrateData()
    {
        return $this->db->query("SELECT key_id FROM " . DB_PREFIX . "instagramshopgallery WHERE media_id IS NULL OR media_id = '' OR media_id = 0")->num_rows;
    }

    public function install($drop = false)
    {
        if ($drop) {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery_to_product`");
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery_to_store`");
        }

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "instagramshopgallery` (
                `key_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `media_id` VARCHAR(64) NULL DEFAULT NULL,
                `approve` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                `shortcode` VARCHAR(128) NOT NULL COMMENT 'Unique media code',
                `permalink` VARCHAR(255) NULL DEFAULT NULL,
                `media_type` VARCHAR(64) NULL DEFAULT NULL,
                `media_url` TEXT NULL DEFAULT NULL,
                `username` VARCHAR(128) NOT NULL,
                `caption` TEXT NOT NULL,
                `timestamp` VARCHAR(64) NULL DEFAULT NULL,
                `updated` DATETIME NOT NULL,
                PRIMARY KEY (`key_id`),
                INDEX `approve` (`approve`),
                INDEX `shortcode` (`shortcode`)
            )
            ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "instagramshopgallery_to_product` (
                `shortcode` VARCHAR(128) NOT NULL COMMENT 'Unique media code',
                `product_id` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`product_id`, `shortcode`)
            )
            ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
        );

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "instagramshopgallery_to_store` (
                `shortcode` VARCHAR(128) NOT NULL COMMENT 'Unique media code',
                `store_id` INT(11) UNSIGNED NOT NULL,
                PRIMARY KEY (`store_id`, `shortcode`)
            )
            ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
        );
    }

    public function update($drop = false)
    {
        // v2.2.1/ v3.2.1
        if (!$this->checkTable('instagramshopgallery_to_store')) {
            $this->db->query(
                "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "instagramshopgallery_to_store` (
                    `shortcode` VARCHAR(128) NOT NULL COMMENT 'Unique media code',
                    `store_id` INT(11) UNSIGNED NOT NULL,
                    PRIMARY KEY (`store_id`, `shortcode`)
                )
                ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
            );

            $query = $this->db->query("SELECT shortcode FROM `" . DB_PREFIX . "instagramshopgallery` LIMIT 0, 1000");
            foreach ($querys->rows as $row) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "instagramshopgallery_to_store` SET shortcode = '" . $this->db->escape($row['shortcode']) . "', store_id = 0");
            }
        }

        // v2.3.0/ v3.3.0
        if (!$this->checkTableColumn('instagramshopgallery', 'media_id')) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "instagramshopgallery` DROP COLUMN `owner`, DROP COLUMN `fullname`, DROP COLUMN `image_thumb`, DROP COLUMN `image_large`, DROP COLUMN `image_original`, DROP COLUMN `timestamp`;");

            $this->db->query("ALTER TABLE `" . DB_PREFIX . "instagramshopgallery` ADD COLUMN `media_id` VARCHAR(64) NULL DEFAULT NULL AFTER `key_id`, ADD COLUMN `permalink` VARCHAR(255) NULL DEFAULT NULL AFTER `shortcode`, ADD COLUMN `media_type` VARCHAR(64) NULL DEFAULT NULL AFTER `permalink`, ADD COLUMN `media_url` TEXT NULL DEFAULT NULL AFTER `media_type`, ADD COLUMN `timestamp` VARCHAR(64) NULL DEFAULT NULL AFTER `caption`;");

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "instagramshopgallery LIMIT 5000");
            if ($query->num_rows) {
                foreach ($query->rows as $row) {
                    if (empty($row['permalink']) && !empty($row['shortcode'])) {
                        $this->db->query("UPDATE `" . DB_PREFIX . "instagramshopgallery` SET `permalink`='https://www.instagram.com/p/" . $row['shortcode'] . "/', `media_type`='image' WHERE  `key_id`=" . (int)$row['key_id']);
                    }
                }
            }
        }
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery_to_product`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "instagramshopgallery_to_store`");
    }

    public function checkTable($table)
    {
        $tables = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "';");

        if ($tables->num_rows) {
            return true;
        }

        return false;
    }

    public function checkTableColumn($table, $column)
    {
        if ($this->checkTable($table)) {
            $results = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "';");

            if ($results->num_rows) {
                return true;
            }
        }

        return false;
    }
}
