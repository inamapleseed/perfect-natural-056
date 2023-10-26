<?php
$_['instagramshopgallery'] = array(
    'title'     => 'InstagramShopGallery',
    'name'      => $name = 'instagramshopgallery',
    'version'   => '2.3.1',

    // Internal
    'token'     => '',
    'store_id'  => 0,
    'code'      => $name,
    'path'      => 'extension/module/' . $name,
    'model'     => 'model_extension_module_' . $name,
    'ext_link'  => 'extension/extension',
    'ext_type'  => '&type=module',

    // Default setting
    'setting'   => array(
        'status'             => 0,
        'validate_media'     => 0,
        'api_source'         => 'basic',
        // Basic
        'basic_app_id'       => '',
        'basic_app_secret'   => '',
        'basic_access_token' => '',
        'basic_token_expire' => '',
        'basic_meta'         => array(),
        // Graph
        'graph_app_id'       => '',
        'graph_app_secret'   => '',
        'graph_access_token' => '',
        'graph_token_expire' => '',
        'graph_meta'         => array(),
        'hashtag'            => '',

        'module'    => array(
            'title'         => '',
            'status'        => 0,
            'visibility'    => 'approve',
            'limit'         => 12,
            'extra_image'   => '',
            'extra_link'    => '',
            'custom_css'    => '',
        ),
        'page'      => array(
            'title'         => '',
            'status'        => 0,
            'navbar'        => 1,
            'visibility'    => 'approve',
            'limit'         => 18,
            'banner'        => '',
            'banner_link'   => '',
            'custom_css'    => '',
            'meta_title'    => '',
            'meta_desc'     => '',
            'meta_keyword'  => '',
            'seo_url'       => ''
        ),
        'igpost'    => array(
            'media_id'          => '',
            'approve'           => 0,
            'shortcode'         => '',
            'permalink'         => '',
            'media_type'        => '',
            'media_url'         => '',
            'username'          => '',
            'caption'           => '',
            'timestamp'         => '',
            'stores'            => array(),
            'related_products'  => array()
        )
    )
);
