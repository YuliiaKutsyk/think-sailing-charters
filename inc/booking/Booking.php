<?php

class Booking
{
    function __construct()
    {
        add_action('admin_menu', [$this, 'add_page']);
    }

    function add_page()
    {
        $icon = '<span class="dashicons dashicons-calendar-alt"></span>';
        // acf_add_options_page(array(
        //     'page_title'  => 'Booking',
        //     'menu_title'  => 'Booking Settings',
        //     'menu_slug'   => 'bookings',
        //     'capability'  => 'edit_posts',
        //     'position'    => '5',
        //     'redirect'    => false
        // ));
        // acf_add_options_sub_page([
        //     'page_title'  => 'Test',
        //     'parent_slug' => 'bookings',
        // ]);
        add_menu_page('Bookings', 'Bookings', 'manage_options', 'booking', [$this, 'set_booking_page'], $icon, 26);
        add_submenu_page('booking', 'January', 'January', 'manage_options', 'january', [$this, 'set_january_page']);
        add_submenu_page('booking', 'February', 'February', 'manage_options', 'february', [$this, 'set_february_page']);
        add_submenu_page('booking', 'March', 'March', 'manage_options', 'march', [$this, 'set_march_page']);
        add_submenu_page('booking', 'April', 'April', 'manage_options', 'april', [$this, 'set_april_page']);
        add_submenu_page('booking', 'May', 'May', 'manage_options', 'may', [$this, 'set_may_page']);
        add_submenu_page('booking', 'June', 'June', 'manage_options', 'june', [$this, 'set_june_page']);
        add_submenu_page('booking', 'July', 'July', 'manage_options', 'july', [$this, 'set_july_page']);
        add_submenu_page('booking', 'August', 'August', 'manage_options', 'august', [$this, 'set_august_page']);
        add_submenu_page('booking', 'September', 'September', 'manage_options', 'september', [$this, 'set_september_page']);
        add_submenu_page('booking', 'October', 'October', 'manage_options', 'october', [$this, 'set_october_page']);
        add_submenu_page('booking', 'November', 'November', 'manage_options', 'november', [$this, 'set_november_page']);
        add_submenu_page('booking', 'December', 'December', 'manage_options', 'december', [$this, 'set_december_page']);
    }

    function set_booking_page()
    {
        ?>
        <div class="wrap">
            <h1>Booking</h1>
            <form method="post" action="options.php">
                <?php
                // settings_fields( 'booking_group' );
                // do_settings_sections( 'booking' );
                // submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    function set_january_page()
    {
        $this->set_month_page('01');
    }

    function set_February_page()
    {
        $this->set_month_page('02');
    }

    function set_march_page()
    {
        $this->set_month_page('03');
    }

    function set_april_page()
    {
        $this->set_month_page('04');
    }

    function set_may_page()
    {
        $this->set_month_page('05');
    }

    function set_june_page()
    {
        $this->set_month_page('06');
    }

    function set_july_page()
    {
        $this->set_month_page('07');
    }

    function set_august_page()
    {
        $this->set_month_page('08');
    }

    function set_september_page()
    {
        $this->set_month_page('09');
    }

    function set_october_page()
    {
        $this->set_month_page('10');
    }

    function set_november_page()
    {
        $this->set_month_page('11');
    }

    function set_december_page()
    {
        $this->set_month_page('12');
    }

    function set_month_page($monthNum)
    {
        $monthName = date('F', mktime(0, 0, 0, (int)$monthNum, 10));
        $daysOfMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, date("Y"));
        $week = isset($_GET['week']) ? $_GET['week'] - 1 : 0;
        $weekFirsdtDay = $week == 0 ? 1 : $week * 7 + 1;

        $weekFirsdtDay = $weekFirsdtDay < 10 ? '0' . (string)$weekFirsdtDay : $weekFirsdtDay;
        $weekLastDay = ($weekFirsdtDay + 6) <= $daysOfMonth ? $weekFirsdtDay + 6 : $daysOfMonth;
        $weekLastDay = $weekLastDay < 10 ? '0' . (string)$weekLastDay : $weekLastDay;
        ?>
        <div class="wrap">
            <h1>Booking <?php echo $monthName; ?></h1>
            <form method="post" action="options.php">
                <style>
            .tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-ev0v{background-color:#dae8fc;border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-fymr{border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-x6qq{background-color:#dae8fc;border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-zgc1{background-color:#656565;border-color:inherit;color:#656565;text-align:left;vertical-align:top}
.tg .tg-6wel{background-color:#dae8fc;border-color:inherit;color:#000000;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-gr25{background-color:#656565;border-color:inherit;color:#000000;font-weight:bold;text-align:left;vertical-align:top}
.tg .tg-re1e{background-color:#656565;border-color:inherit;text-align:left;vertical-align:top}
</style>
<table class="tg" style="min-width:100%">
<thead>
  <tr>
    <th class="tg-fymr">Day</th>
    <th class="tg-fymr">Boat</th>
    <th class="tg-fymr">Name</th>
    <th class="tg-fymr">Charter Type</th>
    <th class="tg-fymr">Sale</th>
    <th class="tg-fymr">Deposit</th>
    <th class="tg-fymr">Balance</th>
    <th class="tg-fymr">Skipper</th>
    <th class="tg-fymr">Menu</th>
    <th class="tg-fymr">Persons</th>
    <th class="tg-fymr">Additional Notes</th>
  </tr>
</thead>
<tbody>
<?php
    // print_r($week);
    $orders = wc_get_orders([
        'sc_booking_page' => [$monthNum .'-' . $weekFirsdtDay, $monthNum . '-' . $weekLastDay],
        'limit' => -1,
    ]);
    // $pluck = wp_list_pluck($orders, 'title');
    // print_r($pluck);
    for ($day = (int)$weekFirsdtDay; $day <= (int)$weekLastDay; $day++){
        $row = 0;
        $currentDay = $day . '/' . $monthNum . '/' . date('Y');
        $dayOfWeek = date('D', strtotime($currentDay));

        $dayOrders = [];
        if(count($orders) > 0){
            foreach($orders as $key => $order){
                $order_id = $order->get_id();
                $dateStart = get_post_meta($order_id, 'sc_day_f', true);
                $dateEnd = get_post_meta($order_id, 'sc_endday_f', true);
                $dates_range = sc_get_dates_in_range($dateStart,$dateEnd);
                foreach($dates_range as $date) {
                    if(explode('-', $date)[2] == $day){
                        $dayOrders[] = $order;
                    }
                }
            }
        }
        $dayOrdersCount = count($dayOrders);
        if($dayOrdersCount!=0){
            foreach($dayOrders as $order){
                $is_food = 'False';
                foreach ($order->get_items() as $item_id => $item){
                    $product = $item->get_product();
                    $boatName = $item->get_name();
                    $variation_id = $item->get_variation_id();
                    $serviceSlug = get_post_meta($variation_id, 'attribute_pa_service', true);
                    $serviceName = get_term_by('slug', $serviceSlug, 'pa_service')->name;
                    $not_sure_menu = get_post_meta( $order_id, 'sc_not_sure_menu', true );
                    if ( has_term( 'food', 'product_cat', $item->get_product_id() ) && !$not_sure_menu) {
                        $is_food = 'True';
                    } else {
                        $is_food = 'False';
                    }
                }
                echo '<tr>';
                if($row == 0){
                    echo '<td class="tg-ev0v">' . $dayOfWeek . '</td>';
                } elseif( $row == 1) {
                    echo '<td class="tg-ev0v">' . $currentDay . '</td>';
                } else {
                    echo '<td class="tg-x6qq"></td>';
                }
                $row = $row + 1;
                echo '<td class="tg-0pky"><a href="' . get_site_url() . '/wp-admin/post.php?post=' . $order->get_id() . '&action=edit">' . $boatName . '</a></td>';
                echo '<td class="tg-0pky">' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() .'</td>';
                echo '<td class="tg-0pky">' . $serviceName . '</td>';
                echo '<td class="tg-0pky">' . wc_price(get_post_meta($order->get_id(), 'sc_general_total', true)) . '</td>';
                echo '<td class="tg-0pky">' . wc_price($order->get_total()) .'</td>';
                echo '<td class="tg-0pky">' . wc_price((int)get_post_meta($order->get_id(), 'sc_general_total', true) - (int)$order->get_total()) . '</td>';
                echo '<td class="tg-0pky">' . (null !== get_field('skipper', $order->get_id()) ? get_field('skipper', $order->get_id()) : '') . '</td>';
                echo '<td class="tg-0pky">' . $is_food . '</td>';
                echo '<td class="tg-0pky">' . get_post_meta($order->get_id(), 'sc_people_number', true) . '</td>';
                echo '<td class="tg-0pky"></td>';
                echo '</tr>';
            }
            if($dayOrdersCount == 1){
                echo '<td class="tg-ev0v">' . $currentDay . '</td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '<td class="tg-0pky"></td>';
                echo '</tr>';
            }
            echo '
                <tr>
                    <td class="tg-gr25"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                    <td class="tg-re1e"></td>
                </tr>';
        } else {
            echo '<tr>';
            echo '<td class="tg-ev0v">' . $dayOfWeek . '</td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '</tr>';
            echo '<td class="tg-ev0v">' . $currentDay . '</td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '<td class="tg-0pky"></td>';
            echo '
            </tr>
            <tr>
                <td class="tg-gr25"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
                <td class="tg-re1e"></td>
            </tr>';
        }
    }
?>
</tbody>
</table>
<?php
$args = [
	'base'         => '%_%',
	'format'       => '?week=%#%',
	'total'        => ceil($daysOfMonth/7),
	'current'      => $week + 1,
	'show_all'     => False,
	'end_size'     => 1,
	'mid_size'     => 2,
	'prev_next'    => True,
	'prev_text'    => __('« Previous'),
	'next_text'    => __('Next »'),
	'type'         => 'plain',
	'add_args'     => False,
	'add_fragment' => '',
	'before_page_number' => '',
	'after_page_number'  => ''
];

echo paginate_links( $args );

?>
            </form>
        </div>
        <?php
    }

}

new Booking();