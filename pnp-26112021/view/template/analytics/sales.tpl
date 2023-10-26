<?= $header; ?>
<?= $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?= $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div id="total-sales-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-shopping-cart"></i>
                        <div class="card-values">
                            <span class="value-title">Total Sales</span>
                            <span class="value">SGD <?= $total_sales_chart['total_sum_display'] ?></span>
                        </div>
                        <!--                        <div class="card-values-change">-->
                        <!--                            <i class="fa fa-arrow-up"></i>-->
                        <!--                            <span class="value">22.3%</span>-->
                        <!--                        </div>-->
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Sales Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Top Performing Month:</div>
                                <div class="summary-entry-text"><?= $total_sales_chart['top_performing_month'] ?></div>
                                <div class="summary-entry-value"><?= $total_sales_chart['top_performing_month_values_display'] ?></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Worst Performing Month:</div>
                                <div class="summary-entry-text"><?= $total_sales_chart['worst_performing_month'] ?></div>
                                <div class="summary-entry-value"><?= $total_sales_chart['worst_performing_month_values_display'] ?></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Average Sales per Month:</div>
                                <div class="summary-entry-text"><?= $total_sales_chart['average_sum_display'] ?></div>
                                <div class="summary-entry-value"></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Sales over Time
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="sales-total-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="total-orders-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-shopping-cart"></i>
                        <div class="card-values">
                            <span class="value-title">Total Orders</span>
                            <span class="value"><?= $total_orders_chart['total_sum_display'] ?></span>
                        </div>
                        <!--                        <div class="card-values-change">-->
                        <!--                            <i class="fa fa-arrow-up"></i>-->
                        <!--                            <span class="value">22.3%</span>-->
                        <!--                        </div>-->
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Order Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Top Performing Month:</div>
                                <div class="summary-entry-text"><?= $total_orders_chart['top_performing_month'] ?></div>
                                <div class="summary-entry-value"><?= $total_orders_chart['top_performing_month_value'] ?> orders</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Worst Performing Month:</div>
                                <div class="summary-entry-text"><?= $total_orders_chart['worst_performing_month'] ?></div>
                                <div class="summary-entry-value"><?= $total_orders_chart['worst_performing_month_value'] ?> orders</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Average Orders per Month:</div>
                                <div class="summary-entry-text"><?= $total_orders_chart['average_sum_display'] ?></div>
                                <div class="summary-entry-value"></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Average Value per Order:</div>
                                <div class="summary-entry-text"><?= $total_orders_chart['average_order_value_display'] ?></div>
                                <div class="summary-entry-value"></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Orders over Time
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="orders-total-chart"></canvas>
                        </div>

                        <div class="value-title text-uppercase" style="margin-top: 30px; font-weight:600;">
                            Checkouts Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Guest Checkouts:</div>
                                <div class="summary-entry-text"><?= $total_checkouts_chart['guest_total'] ?> orders</div>
                                <div class="summary-entry-value"><?= $total_checkouts_chart['guest_total_percentage_display'] ?></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Customer Checkouts:</div>
                                <div class="summary-entry-text"><?= $total_checkouts_chart['customer_total'] ?> orders</div>
                                <div class="summary-entry-value"><?= $total_checkouts_chart['customer_total_percentage_display'] ?></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Checkouts over Time
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="checkouts-total-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="top-grossing-products-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-shopping-cart"></i>
                        <div class="card-values">
                            <span class="value-title">Top Grossing Product</span>
                            <span class="value">Top 5</span>
                        </div>
                        <div class="card-values-change">
                            <!--                            <i class="fa fa-arrow-up"></i>-->
                            <!--                            <span class="value">22.3%</span>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Product Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Top Grossing Product:</div>
                                <div class="summary-entry-text"><?= $top_grossing_products_chart['top_product'] ?></div>
                                <div class="summary-entry-value"><?= $top_grossing_products_chart['top_product_value'] ?></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Top 5 Products
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="top-grossing-products-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="top-sold-products-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-shopping-cart"></i>
                        <div class="card-values">
                            <span class="value-title">Most Sold Product</span>
                            <span class="value">Top 5</span>
                        </div>
                        <div class="card-values-change">
                            <!--                            <i class="fa fa-arrow-up"></i>-->
                            <!--                            <span class="value">22.3%</span>-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Product Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Most Sold Product:</div>
                                <div class="summary-entry-text"><?= $top_selling_products_chart['top_product'] ?></div>
                                <div class="summary-entry-value"><?= $top_selling_products_chart['top_product_value'] ?> units</div>
                            </div>
                            <!--                            <div class="summary-entry">-->
                            <!--                                <div class="summary-entry-title">Least Sold Product:</div>-->
                            <!--                                <div class="summary-entry-text">Lychee Cruffin</div>-->
                            <!--                                <div class="summary-entry-value">3 units</div>-->
                            <!--                            </div>-->
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Top 5 Products
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="top-products-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div id="total-store-visits-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-user"></i>
                        <div class="card-values">
                            <span class="value-title">Total Store Visits</span>
                            <span class="value">1927</span>
                        </div>
                        <div class="card-values-change">
                            <i class="fa fa-arrow-up"></i>
                            <span class="value">33.2%</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Visitor Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Most Visited Month:</div>
                                <div class="summary-entry-text">December, 2020</div>
                                <div class="summary-entry-value">82 visits</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Least Visited Month:</div>
                                <div class="summary-entry-text">November, 2020</div>
                                <div class="summary-entry-value">12 visits</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Average Visits per Month:</div>
                                <div class="summary-entry-text">48</div>
                                <div class="summary-entry-value"></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Visits over Time
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="visit-total-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="conversion-rate-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-check"></i>
                        <div class="card-values">
                            <span class="value-title">Conversion Rate</span>
                            <span class="value">5.13%</span>
                        </div>
                        <div class="card-values-change">
                            <i class="fa fa-arrow-up"></i>
                            <span class="value">22.1%</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Conversion Funnel
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Added to Cart</div>
                                <div class="summary-entry-text">34 sessions</div>
                                <div class="summary-entry-value">23%</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Reached checkout</div>
                                <div class="summary-entry-text">18 sessions</div>
                                <div class="summary-entry-value">10%</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Converted</div>
                                <div class="summary-entry-text">5 sessions</div>
                                <div class="summary-entry-value">5.13%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="recurring-customers-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-users"></i>
                        <div class="card-values">
                            <span class="value-title">Repeat Customer Rate</span>
                            <span class="value"><?= $total_customers_chart['repeat_customers_percentage_display'] ?></span>
                        </div>
                        <!--                        <div class="card-values-change">-->
                        <!--                            <i class="fa fa-arrow-up"></i>-->
                        <!--                            <span class="value">22.3%</span>-->
                        <!--                        </div>-->
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Customers Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Total Customers:</div>
                                <div class="summary-entry-text"><?= $total_customers_chart['total_customers'] ?> customers</div>
                                <div class="summary-entry-value"></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">New Customers:</div>
                                <div class="summary-entry-text"><?= $total_customers_chart['new_customers'] ?> customers</div>
                                <div class="summary-entry-value"></div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Repeat Customers:</div>
                                <div class="summary-entry-text"><?= $total_customers_chart['repeat_customers'] ?> customers</div>
                                <div class="summary-entry-value"></div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Customers over Time
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="customers-total-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="store-visits-by-device-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-mobile"></i>
                        <div class="card-values">
                            <span class="value-title">Store Visits by device types</span>
                            <span class="value">1927 visitors</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Device Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Most Accessed Device:</div>
                                <div class="summary-entry-text">Desktop</div>
                                <div class="summary-entry-value">927 visits</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Least Accessed Device:</div>
                                <div class="summary-entry-text">Tablet</div>
                                <div class="summary-entry-value">237 visits</div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Device Distribution
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="visit-total-device-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="conversions-by-device-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-mobile"></i>
                        <div class="card-values">
                            <span class="value-title">Conversions by device types</span>
                            <span class="value">5 conversions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Device Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Top Converting Device:</div>
                                <div class="summary-entry-text">Desktop</div>
                                <div class="summary-entry-value">4 conversions</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Least Converting Device:</div>
                                <div class="summary-entry-text">Tablet</div>
                                <div class="summary-entry-value">0 conversions</div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Device Distribution
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="conversion-total-device-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div id="conversion-value-by-device-card" class="card">
                    <div class="card-title">
                        <i class="fa fa-mobile"></i>
                        <div class="card-values">
                            <span class="value-title">Conversion Value by device types</span>
                            <span class="value">SGD $100,000.00</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="value-title text-uppercase" style="margin-top: 15px; font-weight:600;">
                            Device Value Summary
                        </div>
                        <div class="summary">
                            <div class="summary-entry">
                                <div class="summary-entry-title">Top Grossing Device:</div>
                                <div class="summary-entry-text">Desktop</div>
                                <div class="summary-entry-value">$92,387.00</div>
                            </div>
                            <div class="summary-entry">
                                <div class="summary-entry-title">Least Grossing Device:</div>
                                <div class="summary-entry-text">Tablet</div>
                                <div class="summary-entry-value">$0.00</div>
                            </div>
                        </div>
                        <div class="value-title text-uppercase" style="margin-top:10px; font-weight:600;">
                            Device Value Distribution
                        </div>
                        <div class="chart-container" style="position:relative; height: 300px;">
                            <canvas width="100" height="300" id="conversion-value-device-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

            </div>
            <div class="col-lg-12">
                <div class="chart-container" style="position:relative; height: 300px;">
                    <canvas width="100" height="300" id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $footer; ?>
<style>
    .card {
        margin: 1rem 0;
        padding: 24px !important;
        border-radius: 6px !important;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
        background: white;
        border-top: #ffffff solid 5px;
    }

    .card:hover {
        border-top: #36a4f7 solid 5px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.42), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
    }

    .card.contrast {
        background: linear-gradient(90deg,#1e5190,#36a4f7)!important;
    }

    .card .card-title {
        display: flex;
        align-items: center;
    }

    .card .card-values {
        margin-left: 15px;
    }

    .card .card-title > i {
        padding: 8px;
        /*background-color: #f3f1f1;*/
        /*background: linear-gradient(0deg,#1e5190,#36a4f7);*/
        background: #36a4f7;
        border-radius: 50%;
        font-size: 24px;
        /*color: #36a4f7!important;*/
        /*color: #ffc107!important;*/
        color: white;
        height:43.5px!important;
        width:43.5px!important;
        text-align: center;
    }

    .card-values-change {
        display: flex;
        font-size: 15px!important;
        line-height: 1.15;
        color: green;
        margin-left: 15px;
    }

    .card-values-change .value {
        margin-left:5px;
    }

    .card.contrast i {
        color: rgba(255, 99, 132, 1);
    }

    .card .card-values .value {
        color: black!important;
        /*color: #1e5190!important;*/
        font-weight: bold;
        font-size: 1.5rem;
        letter-spacing: 1.1px;
        line-height:110%;
        display:block!important;
        margin-bottom: 2.5%;
    }

    .card.contrast .card-values .value {
        color: white!important;
    }

    .card .value-title {
        font-size: 15px!important;
        line-height: 1.5;
    }

    .card.contrast .value-title {
        color: white!important;
    }

    .summary {
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        font-size: 13.5px;
    }

    .summary-entry {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin-top:10px;
    }

    .summary-entry > * {
        flex: 1 1 auto;
        width: 33%;
    }

    .summary-entry-title {
        font-weight: 600;
    }

    .summary-entry-text {
        text-align: left;
    }

    .summary-entry-value {
        text-align: left;
    }

    #container {
        background: #f9f9f9;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script>
    Chart.plugins.unregister(ChartDataLabels);

    var checkouts_total_chart_ctx = document.getElementById('checkouts-total-chart').getContext('2d');
    var checkouts_total_chart = new Chart(checkouts_total_chart_ctx, {
        type: 'line',
        data: {
            labels: <?= $total_checkouts_chart['guest']['chart_labels'] ?>,
            datasets: [{
                label: 'Guest',
                data: <?= $total_checkouts_chart['guest']['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 3,
                pointBackgroundColor: "rgba(255, 99, 132, 1)",
                pointBorderColor: "rgba(255, 99, 132, 1)",
                pointBorderFill: 'rgba(255, 99, 132, 1)',
                pointHighlightFill: 'rgba(255, 99, 132, 1)',
                pointHoverBackgroundColor: 'rgba(255, 99, 132, 1)',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }, {
                label: 'Customers',
                data: <?= $total_checkouts_chart['customer']['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "#0288d1",
                borderWidth: 3,
                pointBackgroundColor: "#0288d1",
                pointBorderColor: "#0288d1",
                pointBorderFill: '#0288d1',
                pointHighlightFill: '#0288d1',
                pointHoverBackgroundColor: '#0288d1',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            padding: 10,
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 1,
                            zeroLineColor: "#e5e5e5"
                        }
                    }
                ]
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var customers_total_chart_ctx = document.getElementById('customers-total-chart').getContext('2d');
    var customers_total_chart = new Chart(customers_total_chart_ctx, {
        type: 'line',
        data: {
            labels: <?= $total_customers_chart['new_customer']['chart_labels'] ?>,
            datasets: [{
                label: 'New',
                data: <?= $total_customers_chart['new_customer']['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 3,
                pointBackgroundColor: "rgba(255, 99, 132, 1)",
                pointBorderColor: "rgba(255, 99, 132, 1)",
                pointBorderFill: 'rgba(255, 99, 132, 1)',
                pointHighlightFill: 'rgba(255, 99, 132, 1)',
                pointHoverBackgroundColor: 'rgba(255, 99, 132, 1)',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }, {
                label: 'Repeat',
                data: <?= $total_customers_chart['repeat_customer']['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "#0288d1",
                borderWidth: 3,
                pointBackgroundColor: "#0288d1",
                pointBorderColor: "#0288d1",
                pointBorderFill: '#0288d1',
                pointHighlightFill: '#0288d1',
                pointHoverBackgroundColor: '#0288d1',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            padding: 10,
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 1,
                            zeroLineColor: "#e5e5e5"
                        }
                    }
                ]
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var conversion_value_by_device_chart_ctx = document.getElementById('conversion-value-device-chart').getContext('2d');
    var conversion_value_by_device_chart = new Chart(conversion_value_by_device_chart_ctx, {
        plugins: [ChartDataLabels],
        type: 'doughnut',
        data: {
            datasets: [
                {
                    label: 'Value',
                    data: [92387.23, 0, 7621.44],
                    backgroundColor: [
                        'rgba(30,109,163,1)',
                        '#26c6da',
                        'rgba(2,136,209,0.5)',
                        'rgba(2,136,209,1.0)',
                        'rgba(2,136,209,0.5)',
                        'rgba(23,130,203,1)',
                    ]
                },
            ],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Desktop',
                'Tablet',
                'Mobile',
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, chart){
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        console.log(tooltipItem);
                        console.log(tooltipItem.datasetIndex);
                        return datasetLabel + ': $' + number_format(chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], 2);
                    }
                }
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        if (value === 0) {
                            return '';
                        }
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            },
            legend: {
                position: 'bottom',
            }
        }
    });

    var conversion_by_device_chart_ctx = document.getElementById('conversion-total-device-chart').getContext('2d');
    var conversion_by_device_chart = new Chart(conversion_by_device_chart_ctx, {
        plugins: [ChartDataLabels],
        type: 'doughnut',
        data: {
            datasets: [
                {
                    label: 'Conversions',
                    data: [4, 0, 1],
                    backgroundColor: [
                        'rgba(30,109,163,1)',
                        '#26c6da',
                        'rgba(2,136,209,0.5)',
                        'rgba(2,136,209,1.0)',
                        'rgba(2,136,209,0.5)',
                        'rgba(23,130,203,1)',
                    ]
                },
            ],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Desktop',
                'Tablet',
                'Mobile',
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        if (value === 0) {
                            return '';
                        }
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            },
            legend: {
                position: 'bottom',
            }
        }
    });

    var visit_total_by_device_chart_ctx = document.getElementById('visit-total-device-chart').getContext('2d');
    var visit_total_by_device_chart = new Chart(visit_total_by_device_chart_ctx, {
        plugins: [ChartDataLabels],
        type: 'doughnut',
        data: {
            datasets: [
                {
                    label: 'Visits',
                    data: [927, 237, 722],
                    backgroundColor: [
                        'rgba(30,109,163,1)',
                        '#26c6da',
                        'rgba(2,136,209,0.5)',
                        'rgba(2,136,209,1.0)',
                        'rgba(2,136,209,0.5)',
                        'rgba(23,130,203,1)',
                    ]
                },
            ],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Desktop',
                'Tablet',
                'Mobile',
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        if (value === 0) {
                            return '';
                        }
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            },
            legend: {
                position: 'bottom',
            }
        }
    });

    var top_grossing_products_chart_ctx = document.getElementById('top-grossing-products-chart').getContext('2d');
    var top_grossing_products_chart = new Chart(top_grossing_products_chart_ctx, {
        type: 'horizontalBar',
        data: {
            datasets: [
                {
                    label: 'Amount',
                    data: <?= $top_grossing_products_chart['chart_values'] ?>,
                    backgroundColor: [
                        'rgba(30,109,163,1)',
                        '#26c6da',
                        'rgba(2,136,209,0.5)',
                        'rgba(2,136,209,1.0)',
                        'rgba(2,136,209,0.5)',
                        'rgba(23,130,203,1)',
                    ]
                },
            ],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: <?= $top_grossing_products_chart['chart_labels'] ?>
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                    ticks: {
                        padding: 10,
                        callback: function(value, index, values) {
                            return '$' + number_format(value);
                        },
                        fontColor: "#9e9e9e"
                    },
                    gridLines: {
                        display: true,
                        drawBorder: false,
                        lineWidth: 1,
                        zeroLineColor: "#e5e5e5"
                    }
                }],
                yAxes: [{
                    stacked: true,
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, chart){
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': $' + number_format(tooltipItem.xLabel, 2);
                    }
                }
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var top_products_chart_ctx = document.getElementById('top-products-chart').getContext('2d');
    var top_products_chart = new Chart(top_products_chart_ctx, {
        type: 'horizontalBar',
        data: {
            datasets: [
                {
                    label: 'Units',
                    data: <?= $top_selling_products_chart['chart_values'] ?>,
                    backgroundColor: [
                        'rgba(30,109,163,1)',
                        '#26c6da',
                        'rgba(2,136,209,0.5)',
                        'rgba(2,136,209,1.0)',
                        'rgba(2,136,209,0.5)',
                        'rgba(23,130,203,1)',
                    ]
                },
            ],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: <?= $top_selling_products_chart['chart_labels'] ?>
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            },
        }
    });

    var orders_chart_ctx = document.getElementById('orders-total-chart').getContext('2d');
    var orders_chart = new Chart(orders_chart_ctx, {
        type: 'line',
        data: {
            labels: <?= $total_orders_chart['chart_labels'] ?>,
            datasets: [{
                label: 'Orders',
                data: <?= $total_orders_chart['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "#0288d1",
                borderWidth: 3,
                pointBackgroundColor: "#0288d1",
                pointBorderColor: "#0288d1",
                pointBorderFill: '#0288d1',
                pointHighlightFill: '#0288d1',
                pointHoverBackgroundColor: '#0288d1',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            padding: 10,
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 1,
                            zeroLineColor: "#e5e5e5"
                        }
                    }
                ]
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var visitor_chart_ctx = document.getElementById('visit-total-chart').getContext('2d');
    var visitor_chart = new Chart(visitor_chart_ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January'],
            datasets: [{
                label: 'Store Visits',
                data: [48, 22, 21, 4, 38, 12, 11, 62, 50, 44, 12, 82, 15],
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "#0288d1",
                borderWidth: 3,
                pointBackgroundColor: "#0288d1",
                pointBorderColor: "#0288d1",
                pointBorderFill: '#0288d1',
                pointHighlightFill: '#0288d1',
                pointHoverBackgroundColor: '#0288d1',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            padding: 10,
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 1,
                            zeroLineColor: "#e5e5e5"
                        }
                    }
                ]
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var revenue_chart_ctx = document.getElementById('sales-total-chart').getContext('2d');
    var revenue_chart = new Chart(revenue_chart_ctx, {
        type: 'line',
        data: {
            labels: <?= $total_sales_chart['chart_labels'] ?>,
            datasets: [{
                label: 'Sales',
                data: <?= $total_sales_chart['chart_values'] ?>,
                fill: false,
                pointRadius: 3,
                pointBorderWidth: 1,
                borderColor: "#0288d1",
                borderWidth: 3,
                pointBackgroundColor: "#0288d1",
                pointBorderColor: "#0288d1",
                pointBorderFill: '#0288d1',
                pointHighlightFill: '#0288d1',
                pointHoverBackgroundColor: '#0288d1',
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderWidth: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        display: true
                    }
                ],
                yAxes: [
                    {
                        ticks: {
                            padding: 10,
                            callback: function(value, index, values) {
                                return '$' + number_format(value);
                            },
                            fontColor: "#9e9e9e"
                        },
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            lineWidth: 1,
                            zeroLineColor: "#e5e5e5"
                        }
                    }
                ]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, chart){
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel, 2);
                    }
                }
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: false,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    },
                    gridLines: {
                        display: true,
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }]
            },
        }
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
// *     example: number_format(1234.56, 2, ',', ' ');
// *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>