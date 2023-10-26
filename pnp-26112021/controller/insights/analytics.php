<?php
class ControllerInsightsAnalytics extends Controller {
    public function index() {

        $this->load->language('common/dashboard');

        $this->document->setTitle('Analytics');

        $data['heading_title'] = 'Analytics';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
            $data['error_install'] = $this->language->get('error_install');
        } else {
            $data['error_install'] = '';
        }
        // Check CRON job for low stock notification
        if ($this->config->get('config_low_stock_notify')) {
            $data['warning_cron_stock'] = nl2br($this->language->get('warning_cron_stock'));
        } else {
            $data['warning_cron_stock'] = '';
        }

        $data = $this->load->controller('common/common', $data);

        /* Get total sales */
        $data['total_sales_chart'] = array();
        $data['total_sales_chart']['total_sum'] = 0;
        $total_sales_query = $this->db->query('
                    SELECT o.date_added AS date_ordered,
                    SUM(o.total) AS total_sum
                    FROM `' . DB_PREFIX . 'order` o
                    WHERE o.order_status_id = 2 OR o.order_status_id = 5
                    GROUP BY YEAR(date_ordered), MONTH(date_ordered)
                    ORDER BY date_ordered'
        );
//        debug($total_sales_query);
        if ($total_sales_query->num_rows) {

            $current_month = date('F Y', strtotime('now'));

            $data['total_sales_chart']['months'] = array();
            $data['total_sales_chart']['values'] = array();
            $data['total_sales_chart']['top_performing_month'] = null;
            $data['total_sales_chart']['top_performing_month_value'] = null;
            $data['total_sales_chart']['worst_performing_month'] = null;
            $data['total_sales_chart']['worst_performing_month_value'] = null;
            $data['total_sales_chart']['display_values'] = array();

            foreach ($total_sales_query->rows as $row) {
                $data['total_sales_chart']['months'][] = date('F Y', strtotime($row['date_ordered']));
                $data['total_sales_chart']['values'][] = $row['total_sum'];
                $data['total_sales_chart']['display_values'][] = '$' . number_format($row['total_sum'], 2);
                $data['total_sales_chart']['total_sum'] += (float)$row['total_sum'];

                /* Compare for highest value */
                if (!$data['total_sales_chart']['top_performing_month_value']) {
                    $data['total_sales_chart']['top_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_sales_chart']['top_performing_month_value'] = (float)$row['total_sum'];
                    $data['total_sales_chart']['worst_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_sales_chart']['worst_performing_month_value'] = (float)$row['total_sum'];
                }

                if ((float)$row['total_sum'] > $data['total_sales_chart']['top_performing_month_value']) {
                    $data['total_sales_chart']['top_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_sales_chart']['top_performing_month_value'] = (float)$row['total_sum'];
                }

                /* Compare for lowest value */
                if ($current_month != date('F Y', strtotime($row['date_ordered']))) {
                    if ((float)$row['total_sum'] < $data['total_sales_chart']['worst_performing_month_value']) {
                        $data['total_sales_chart']['worst_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                        $data['total_sales_chart']['worst_performing_month_value'] = (float)$row['total_sum'];
                    }
                }
            }
            $data['total_sales_chart']['total_sum_display'] = '$' . number_format($data['total_sales_chart']['total_sum'], 2);
            $data['total_sales_chart']['average_sum'] = $data['total_sales_chart']['total_sum'] / count($data['total_sales_chart']['months']);
            $data['total_sales_chart']['average_sum_display'] = '$' . number_format($data['total_sales_chart']['average_sum'], 2);
            $data['total_sales_chart']['top_performing_month_values_display'] = '$' . number_format($data['total_sales_chart']['top_performing_month_value'], 2);
            $data['total_sales_chart']['worst_performing_month_values_display'] = '$' . number_format($data['total_sales_chart']['worst_performing_month_value'], 2);
            $data['total_sales_chart']['chart_labels'] = json_encode($data['total_sales_chart']['months']);
            $data['total_sales_chart']['chart_values'] = json_encode($data['total_sales_chart']['values']);
        }
        /* END Get Total Sales */

        /* Get Total Orders */
        $data['total_orders_chart'] = array();
        $data['total_orders_chart']['total_orders'] = 0;
        $total_orders_query = $this->db->query('
                    SELECT o.date_added AS date_ordered, 
                           COUNT(o.order_id) AS total_orders 
                    FROM `' . DB_PREFIX . 'order` o 
                    WHERE o.order_status_id = 2 OR o.order_status_id = 5 
                    GROUP BY YEAR(date_ordered), MONTH(date_ordered) 
                    ORDER BY date_ordered'
        );

        if ($total_orders_query->num_rows) {
            $data['total_orders_chart']['months'] = array();
            $data['total_orders_chart']['values'] = array();
            $data['total_orders_chart']['top_performing_month'] = null;
            $data['total_orders_chart']['top_performing_month_value'] = null;
            $data['total_orders_chart']['worst_performing_month'] = null;
            $data['total_orders_chart']['worst_performing_month_value'] = null;
            $data['total_orders_chart']['display_values'] = array();

            foreach ($total_orders_query->rows as $row) {
                $data['total_orders_chart']['months'][] = date('F Y', strtotime($row['date_ordered']));
                $data['total_orders_chart']['values'][] = $row['total_orders'];
                $data['total_orders_chart']['display_values'][] = (float)$row['total_orders'];
                $data['total_orders_chart']['total_orders'] += (float)$row['total_orders'];

                /* Compare for highest value */
                if (!$data['total_orders_chart']['top_performing_month_value']) {
                    $data['total_orders_chart']['top_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_orders_chart']['top_performing_month_value'] = (float)$row['total_orders'];
                    $data['total_orders_chart']['worst_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_orders_chart']['worst_performing_month_value'] = (float)$row['total_orders'];
                }

                if ((float)$row['total_orders'] > $data['total_orders_chart']['top_performing_month_value']) {
                    $data['total_orders_chart']['top_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                    $data['total_orders_chart']['top_performing_month_value'] = (float)$row['total_orders'];
                }

                /* Compare for lowest value */
                if ($current_month != date('F Y', strtotime($row['date_ordered']))) {
                    if ((float)$row['total_orders'] < $data['total_orders_chart']['worst_performing_month_value']) {
                        $data['total_orders_chart']['worst_performing_month'] = date('F Y', strtotime($row['date_ordered']));
                        $data['total_orders_chart']['worst_performing_month_value'] = (float)$row['total_orders'];
                    }
                }
            }
            $data['total_orders_chart']['total_sum_display'] = (float)$data['total_orders_chart']['total_orders'];
            $data['total_orders_chart']['average_sum'] = number_format($data['total_orders_chart']['total_orders'] / count($data['total_orders_chart']['months']),0);
            $data['total_orders_chart']['average_sum_display'] = (float)$data['total_orders_chart']['average_sum'];
            $data['total_orders_chart']['average_order_value'] = (float)$data['total_sales_chart']['total_sum'] / (float)$data['total_orders_chart']['total_orders'];
            $data['total_orders_chart']['average_order_value_display'] = '$' . number_format($data['total_orders_chart']['average_order_value'], 2);
            $data['total_orders_chart']['top_performing_month_values_display'] = (float)$data['total_orders_chart']['top_performing_month_value'];
            $data['total_orders_chart']['worst_performing_month_values_display'] = (float)$data['total_orders_chart']['worst_performing_month_value'];
            $data['total_orders_chart']['chart_labels'] = json_encode($data['total_orders_chart']['months']);
            $data['total_orders_chart']['chart_values'] = json_encode($data['total_orders_chart']['values']);
        }
        /* END Get Total Orders */

        /* Get Checkouts for Orders */
        $data['total_checkouts_chart'] = array();
        $data['total_checkouts_chart']['guest'] = array();
        $data['total_checkouts_chart']['customer'] = array();
        $data['total_checkouts_chart']['guest_total'] = 0;
        $data['total_checkouts_chart']['guest_total_percentage'] = 0;
        $data['total_checkouts_chart']['customer_total'] = 0;
        $data['total_checkouts_chart']['customer_total_percentage'] = 0;

        $guest_checkouts_query = $this->db->query('
        SELECT o.date_added AS date_ordered, 
               COUNT(o.order_id) AS total_orders 
        FROM `'. DB_PREFIX .'order` o 
        WHERE (o.order_status_id = 2 OR o.order_status_id = 5) 
        AND o.customer_id = 0 
        GROUP BY YEAR(date_ordered), MONTH(date_ordered) 
        ORDER BY date_ordered
        ');

        if ($guest_checkouts_query->num_rows) {
            $data['total_checkouts_chart']['guest']['months'] = array();
            $data['total_checkouts_chart']['guest']['values'] = array();
            foreach ($guest_checkouts_query->rows as $row) {
                $data['total_checkouts_chart']['guest']['months'][] = date('F Y', strtotime($row['date_ordered']));
                $data['total_checkouts_chart']['guest']['values'][] = (float)$row['total_orders'];
                $data['total_checkouts_chart']['guest_total'] += (float)$row['total_orders'];
            }
        }

        $customer_checkouts_query = $this->db->query('
        SELECT o.date_added AS date_ordered, 
               COUNT(o.order_id) AS total_orders 
        FROM `'. DB_PREFIX .'order` o 
        WHERE (o.order_status_id = 2 OR o.order_status_id = 5) 
        AND o.customer_id > 0 
        GROUP BY YEAR(date_ordered), MONTH(date_ordered) 
        ORDER BY date_ordered
        ');

        if ($customer_checkouts_query->num_rows) {
            $data['total_checkouts_chart']['customer']['months'] = array();
            $data['total_checkouts_chart']['customer']['values'] = array();
            foreach ($customer_checkouts_query->rows as $row) {
                $data['total_checkouts_chart']['customer']['months'][] = date('F Y', strtotime($row['date_ordered']));
                $data['total_checkouts_chart']['customer']['values'][] = (float)$row['total_orders'];
                $data['total_checkouts_chart']['customer_total'] += (float)$row['total_orders'];
            }
        }
        $total_checkouts = $data['total_checkouts_chart']['customer_total'] + $data['total_checkouts_chart']['guest_total'];
        $data['total_checkouts_chart']['guest_total_percentage'] =  $data['total_checkouts_chart']['guest_total'] * 100 / $total_checkouts;
        $data['total_checkouts_chart']['customer_total_percentage'] =  $data['total_checkouts_chart']['customer_total'] * 100 / $total_checkouts;
        $data['total_checkouts_chart']['guest_total_percentage_display'] = number_format($data['total_checkouts_chart']['guest_total_percentage'], 2) . '%';
        $data['total_checkouts_chart']['customer_total_percentage_display'] = number_format($data['total_checkouts_chart']['customer_total_percentage'], 2). '%';
        $data['total_checkouts_chart']['guest']['chart_labels'] = json_encode($data['total_checkouts_chart']['guest']['months']);
        $data['total_checkouts_chart']['guest']['chart_values'] = json_encode($data['total_checkouts_chart']['guest']['values']);
        $data['total_checkouts_chart']['customer']['chart_labels'] = json_encode($data['total_checkouts_chart']['customer']['months']);
        $data['total_checkouts_chart']['customer']['chart_values'] = json_encode($data['total_checkouts_chart']['customer']['values']);
        /* END Get Checkouts for Orders */

        /* Get top grossing products */
        $data['top_grossing_products_chart'] = array();
        $data['top_grossing_products_chart']['top_product'] = null;
        $data['top_grossing_products_chart']['top_product_value'] = null;

        $top_grossing_query = $this->db->query('
        SELECT SUM(total) AS product_gross, product_id, `name`
        FROM `' . DB_PREFIX . 'order_product`
        WHERE order_id IN (
            SELECT order_id FROM `' . DB_PREFIX . 'order` WHERE order_status_id = 2 OR order_status_id = 5
        )
        GROUP BY product_id
        ORDER BY product_gross DESC
        LIMIT 5
        ');

        if ($top_grossing_query->num_rows) {
            $data['top_grossing_products_chart']['product'] = array();
            $data['top_grossing_products_chart']['values'] = array();

            foreach ($top_grossing_query->rows as $row) {
                $data['top_grossing_products_chart']['product'][] = $row['name'];
                $data['top_grossing_products_chart']['values'][] = (float)$row['product_gross'];

                if (!$data['top_grossing_products_chart']['top_product']) {
                    $data['top_grossing_products_chart']['top_product'] = $row['name'] . ' #' . $row['product_id'];
                    $data['top_grossing_products_chart']['top_product_value'] = '$' . number_format((float)$row['product_gross'], 2);
                }
            }

            $data['top_grossing_products_chart']['chart_labels'] = json_encode($data['top_grossing_products_chart']['product']);
            $data['top_grossing_products_chart']['chart_values'] = json_encode($data['top_grossing_products_chart']['values']);
        }
        /* END Get top grossing products */



        /* Get top grossing products */
        $data['top_selling_products_chart'] = array();
        $data['top_selling_products_chart']['top_product'] = null;
        $data['top_selling_products_chart']['top_product_value'] = null;

        $top_selling_query = $this->db->query('SELECT SUM(quantity) AS product_qty, product_id, `name`
            FROM `' . DB_PREFIX . 'order_product`
            WHERE order_id IN (
                SELECT order_id FROM `' . DB_PREFIX . 'order` 
                WHERE order_status_id = 2 OR order_status_id = 5
            )
            GROUP BY product_id
            ORDER BY product_qty DESC
            LIMIT 5');

        if ($top_selling_query->num_rows) {
            $data['top_selling_products_chart']['product'] = array();
            $data['top_selling_products_chart']['values'] = array();

            foreach ($top_selling_query->rows as $row) {
                $data['top_selling_products_chart']['product'][] = $row['name'];
                $data['top_selling_products_chart']['values'][] = (float)$row['product_qty'];

                if (!$data['top_selling_products_chart']['top_product']) {
                    $data['top_selling_products_chart']['top_product'] = $row['name'] . ' #' . $row['product_id'];
                    $data['top_selling_products_chart']['top_product_value'] = (float)$row['product_qty'];
                }
            }

            $data['top_selling_products_chart']['chart_labels'] = json_encode($data['top_selling_products_chart']['product']);
            $data['top_selling_products_chart']['chart_values'] = json_encode($data['top_selling_products_chart']['values']);
        }
        /* END Get top grossing products */


        /* Get repeat customers */
        $data['total_customers_chart'] = array();
        $data['total_customers_chart']['total_customers'] = 0;
        $data['total_customers_chart']['new_customers'] = 0;
        $data['total_customers_chart']['repeat_customers'] = 0;
        $data['total_customers_chart']['repeat_customers_percentage'] = 0;
        $data['total_customers_chart']['repeat_customers_percentage_display'] = null;
        $data['total_customers_chart']['new_customer_ids'] = '';
        $data['total_customers_chart']['repeat_customer_ids'] = '';
        $data['total_customers_chart']['new_customer']['months'] = array();
        $data['total_customers_chart']['new_customer']['values'] = array();
        $data['total_customers_chart']['repeat_customer']['months'] = array();
        $data['total_customers_chart']['repeat_customer']['values'] = array();

        $total_customers_query = $this->db->query('
            SELECT COUNT(order_id) AS orders, o.customer_id
            FROM `' . DB_PREFIX . 'order` o, `' . DB_PREFIX . 'customer` c
            WHERE (o.order_status_id = 2 OR o.order_status_id = 5)
            AND o.customer_id = c.customer_id
            GROUP BY (o.customer_id)
            ORDER BY orders DESC
        ');

        $total_customers = $total_customers_query->num_rows;
        if ($total_customers) {
            $data['total_customers_chart']['total_customers'] = (float)$total_customers;
            foreach ($total_customers_query->rows as $row) {
                if ($row['orders'] > 1) {
                    $data['total_customers_chart']['repeat_customers']++;

                    if (empty($data['total_customers_chart']['repeat_customer_ids'])) {
                        $data['total_customers_chart']['repeat_customer_ids'] .= $row['customer_id'];
                    } else {
                        $data['total_customers_chart']['repeat_customer_ids'] .= ',' . $row['customer_id'];
                    }

                } else if ($row['orders'] == 1) {
                    $data['total_customers_chart']['new_customers']++;

                    if (empty($data['total_customers_chart']['new_customer_ids'])) {
                        $data['total_customers_chart']['new_customer_ids'] .= $row['customer_id'];
                    } else {
                        $data['total_customers_chart']['new_customer_ids'] .= ',' . $row['customer_id'];
                    }
                }
            }
            $data['total_customers_chart']['repeat_customers_percentage']
                = $data['total_customers_chart']['repeat_customers'] * 100 / $data['total_customers_chart']['total_customers'];
            $data['total_customers_chart']['repeat_customers_percentage_display'] = number_format((float)$data['total_customers_chart']['repeat_customers_percentage'], 2) . '%';

            $repeat_customer_over_time_query = $this->db->query('
            SELECT COUNT(customer_id) AS customers, date_added
            FROM `' . DB_PREFIX . 'customer`
            WHERE customer_id IN (' . $data['total_customers_chart']['repeat_customer_ids'] . ')
            GROUP BY YEAR(date_added), MONTH(date_added)
            ');

            foreach ($repeat_customer_over_time_query->rows as $row) {
                $data['total_customers_chart']['repeat_customer']['months'][] = date('F Y', strtotime($row['date_added']));
                $data['total_customers_chart']['repeat_customer']['values'][] = (float)$row['customers'];
            }

            $new_customer_over_time_query = $this->db->query('
            SELECT COUNT(customer_id) AS customers, date_added
            FROM `' . DB_PREFIX . 'customer`
            WHERE customer_id IN (' . $data['total_customers_chart']['new_customer_ids'] . ')
            GROUP BY YEAR(date_added), MONTH(date_added)
            ');

            foreach ($new_customer_over_time_query->rows as $row) {
                $data['total_customers_chart']['new_customer']['months'][] = date('F Y', strtotime($row['date_added']));
                $data['total_customers_chart']['new_customer']['values'][] = (float)$row['customers'];
            }

            $data['total_customers_chart']['repeat_customer']['chart_labels'] = json_encode($data['total_customers_chart']['repeat_customer']['months']);
            $data['total_customers_chart']['repeat_customer']['chart_values'] = json_encode($data['total_customers_chart']['repeat_customer']['values']);
            $data['total_customers_chart']['new_customer']['chart_labels'] = json_encode($data['total_customers_chart']['new_customer']['months']);
            $data['total_customers_chart']['new_customer']['chart_values'] = json_encode($data['total_customers_chart']['new_customer']['values']);
        }
        /* END get repeat customers */


        // Run currency update
        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');

            $this->model_localisation_currency->refresh();
        }

        $this->response->setOutput($this->load->view('analytics/sales', $data));
    }
}
?>