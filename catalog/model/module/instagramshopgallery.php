<?php
class ModelModuleInstagramShopGallery extends Model
{
    private $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/instagramshopgallery');
        $this->module = $this->config->get('instagramshopgallery');
    }

    /**
     * Used to get media with complete data
     */
    public function getMedias($page = 1, $limit = 48, $select = '*', $where = '', $clause = '')
    {
        $extra_limit = $limit + 5; // incase some image is not available in IG server

        $query = $this->db->query("SELECT " . $select . ", (SELECT COUNT(*) FROM `" . DB_PREFIX . "instagramshopgallery_to_product` isgp WHERE isgp.shortcode = isg.shortcode) as related_product
            FROM `" . DB_PREFIX . "instagramshopgallery` isg
                LEFT JOIN `" . DB_PREFIX . "instagramshopgallery_to_store` isg2s ON (isg.shortcode = isg2s.shortcode)
            WHERE isg2s.store_id = " . (int)$this->config->get('config_store_id') . "
                " . $where . "
            GROUP BY isg.shortcode
            " . $clause . "
            ORDER BY isg.timestamp DESC, isg.updated DESC
            LIMIT " . (int)(($page-1) * $limit) . ", " . (int)$extra_limit);

        return $query->rows;
    }

    public function getMediasTotal()
    {
        $query = $this->db->query(
            "SELECT COUNT(isg.shortcode) AS total
            FROM `" . DB_PREFIX . "instagramshopgallery` isg
            LEFT JOIN `" . DB_PREFIX . "instagramshopgallery_to_store` isg2s ON (isg.shortcode = isg2s.shortcode)
            WHERE isg2s.store_id = " . (int)$this->config->get('config_store_id')
        );

        return $query->row['total'];
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
            $data = array_replace_recursive($this->module['setting']['igpost'], $query->row);
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
        $this->load->model('catalog/product');

        $products = array();
        $query = $this->db->query("SELECT isgp.*
            FROM `" . DB_PREFIX . "instagramshopgallery_to_product` isgp
            LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = isgp.product_id)
            LEFT JOIN `" . DB_PREFIX . "product_to_store` p2s ON (p2s.product_id = isgp.product_id)
            WHERE isgp.shortcode = '" . $this->db->escape($shortcode) . "'
                AND p.status = '1'
                AND p.date_available <= NOW()
                AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
            LIMIT 0, 100");

        foreach ($query->rows as $product) {
            $products[] = $this->model_catalog_product->getProduct($product['product_id']);
        }

        return $products;
    }
}
