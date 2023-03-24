<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        include "header.inc.php";
        ?>
        <?php
            session_start();
            // Arrays to store data fetched from database
            $all_time_top10_items_array = [];
            $store_revenue_by_month_array = [];
                    
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            // Check connection to database
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            }
            
            // SQL statement to retrieve the all-time top 10 most selling products
            $all_time_top10_items_stmt = $conn->prepare("SELECT mydb.Products.product_name, SUM(mydb.Cart_Item.quantity) AS quantity_sold 
                                                        FROM mydb.Cart_Item 
                                                        INNER JOIN mydb.Order_History 
                                                        ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                        INNER JOIN mydb.Products
                                                        ON mydb.Cart_Item.Products_product_id=mydb.Products.product_id
                                                        WHERE mydb.Order_History.purchased=?
                                                        GROUP BY mydb.Cart_Item.Products_product_id
                                                        ORDER BY quantity_sold DESC
                                                        LIMIT 10;");
            
            // Prepared statements parameters and execute SQL statement
            $purchased = 1;
            $all_time_top10_items_stmt->bind_param("i", $purchased);
            $all_time_top10_items_stmt->execute();
        
            // Storing data into array
            $all_time_top10_items_result = $all_time_top10_items_stmt->get_result();
            if ($all_time_top10_items_result->num_rows > 0) {
                while ($row = $all_time_top10_items_result->fetch_assoc()) {
                    array_push($all_time_top10_items_array, array($row["product_name"], $row["quantity_sold"]));
                }
            }
            
            // SQL statement to retrieve the monthly store's revenue for the last 12 months
            $store_revenue_by_month_stmt = $conn->prepare("SELECT SUM(mydb.Cart_Item.price * mydb.Cart_Item.quantity) AS monthly_revenue, YEAR(mydb.Order_History.order_at) AS \"YEAR\", MONTH(mydb.Order_History.order_at) AS \"MONTH\"
                                                           FROM mydb.Cart_Item 
                                                           INNER JOIN mydb.Order_History 
                                                           ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                           WHERE mydb.Order_History.purchased=? AND mydb.Order_History.order_at > now() - INTERVAL 12 MONTH
                                                           GROUP BY YEAR(mydb.Order_History.order_at), MONTH(mydb.Order_History.order_at);");
            
            // Prepared statements parameters and execute SQL statement
            $store_revenue_by_month_stmt->bind_param("i", $purchased);
            $store_revenue_by_month_stmt->execute();
            
            // Storing data into array
            $store_revenue_by_month_result = $store_revenue_by_month_stmt->get_result();
            if ($store_revenue_by_month_result->num_rows > 0) {
                while ($row = $store_revenue_by_month_result->fetch_assoc()) {
                    array_push($store_revenue_by_month_array, array($row["monthly_revenue"], $row["YEAR"], $row["MONTH"]));
                }
            }
            
            // Close database connection
            $conn->close(); 
        ?>
        <script>    
        window.onload = function () {
            var columnChartData = [];
            var lineChartData = [];
            
            const columnChartPHPData = <?php echo json_encode($all_time_top10_items_array, JSON_NUMERIC_CHECK); ?>
            
            for (let index = 0; index < columnChartPHPData.length; index++) {
                console.log(columnChartPHPData[index]);
                var productName = columnChartPHPData[index][0].charAt(0).toUpperCase() + columnChartPHPData[index][0].slice(1);
                columnChartData.push({label: productName, y: columnChartPHPData[index][1]});
            }

            var columnChart = new CanvasJS.Chart("columnChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                backgroundColor: "transparent",
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                axisY: {
                    includeZero: true
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    indexLabel: "{y}", //Shows y value on all Data Points
                    indexLabelFontColor: "#5A5757",
                    indexLabelPlacement: "outside",
                    dataPoints: columnChartData
                }]
            });
//-----------------------------------------------------------------------------------------------------------------------
            const lineChartPHPData = <?php echo json_encode($store_revenue_by_month_array, JSON_NUMERIC_CHECK); ?>
            
            for (let index = 0; index < lineChartPHPData.length; index++) {
                console.log(lineChartPHPData[index]);
                var month = lineChartPHPData[index][2];
                if (month < 10) {
                    month = "0" + month;
                }
                var yearMonth = lineChartPHPData[index][1] + "-" + month;
                lineChartData.push({label: yearMonth, y: lineChartPHPData[index][0]});
            }
           
            var lineChart = new CanvasJS.Chart("lineChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                backgroundColor: "transparent",
                theme: "light2",
                axisY: {
                        title: "Revenue in SGD",
                        prefix: "$"
                },
                data: [{
                        type: "spline",
                        markerSize: 5,
                        xValueFormatString: "YYYY",
                        yValueFormatString: "$#,##0.##",
                        xValueType: "dateTime",
                        dataPoints: lineChartData
                }]
            });
            columnChart.render();
            lineChart.render();
        };
    </script>
    </head>
    <body>
        <?php
        include "nav.inc.php";
        ?>
        <main>
            <?php
            $overall_statistics_array = [];
            $yesterday_statistics_array = [];
            $today_statistics_array = [];
            $monthly_statistics_array = [];

            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            }

            // Prepare, Bind & Execute SELECT statement to retrieve all active products categories:
            $overall_statistics_stmt = $conn->prepare("SELECT SUM(mydb.Cart_Item.price) AS all_time_revenue, SUM(mydb.Cart_Item.quantity) AS all_time_units_sold 
                                                       FROM mydb.Cart_Item 
                                                       INNER JOIN mydb.Order_History 
                                                       ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                       WHERE mydb.Order_History.purchased=?;");
            $purchased = 1;
            $overall_statistics_stmt->bind_param("i", $purchased);
            $overall_statistics_stmt->execute();

            $overall_statistics_result = $overall_statistics_stmt->get_result();
            if ($overall_statistics_result->num_rows > 0) {
                while ($row = $overall_statistics_result->fetch_assoc()) {
                    array_push($overall_statistics_array, $row["all_time_revenue"], $row["all_time_units_sold"]);
                }
            }
            
            if ($overall_statistics_array[1] == null) {
                $overall_statistics_array[1] = 0;
            }
            
            $html_output = "";
            $html_output .= "<main class=\"admin_dashboard\">" .
                                "<div class=\"container-fluid\">" .
                                    "<div class=\"panel-heading\">" .
                                        "<h1><em>DASHBOARD</em></h1>" .
                                        "<hr>" .
                                    "</div>" .
                                    "<div class=\"row\">" . 
                                        "<div class=\"col-12\">" .
                                            "<div class=\"panel\">" .
                                                "<div class=\"panel-heading\">" .
                                                    "<h3 class=\"header\">OVERALL STATISTICS</h3>" .
                                                    "<hr>" .
                                                "</div>" .
                                                "<div class=\"row\">" .
                                                    "<div class=\"col-6 panel-body\">" .
                                                        "<h5>REVENUE GAINED: $". number_format($overall_statistics_array[0], 2, ".", "") ."</h5>" .
                                                    "</div>" .
                                                    "<div class=\"col-6 panel-body\">" .
                                                        "<h5>NUMBER OF PRODUCTS SOLD: ". $overall_statistics_array[1] ."</h5>" .
                                                    "</div>" .
                                                "</div>" .
                                            "</div>" .
                                        "</div>" .
                                    "</div>" .
                                    "<br>";

            $yesterday_statistics_stmt = $conn->prepare("SELECT sum(mydb.Cart_Item.price * mydb.Cart_Item.quantity) AS yesterday_revenue, SUM(mydb.Cart_Item.quantity) AS yesterday_units_sold 
                                                         FROM mydb.Cart_Item 
                                                         INNER JOIN mydb.Order_History 
                                                         ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                         WHERE mydb.Order_History.purchased=? AND mydb.Order_History.order_at BETWEEN ? AND ?;");


            //GET YESTERDAY'S DATE
            date_default_timezone_set("Singapore");
            $yesterday_start_datetime = date('Y-m-d 00:00:00', strtotime("-1 days")); 
            $yesterday_end_datetime = date('Y-m-d 23:59:59', strtotime("-1 days"));
            $yesterday_statistics_stmt->bind_param("iss", $purchased, $yesterday_start_datetime, $yesterday_end_datetime);
            $yesterday_statistics_stmt->execute();

            $yesterday_statistics_result = $yesterday_statistics_stmt->get_result();
            if ($yesterday_statistics_result->num_rows > 0) {
                while ($row = $yesterday_statistics_result->fetch_assoc()) {
                    array_push($yesterday_statistics_array, $row["yesterday_revenue"], $row["yesterday_units_sold"]);
                }
            }
            
            if ($yesterday_statistics_array[1] == null) {
                $yesterday_statistics_array[1] = 0;
            }
            
            $html_output .=         "<div class=\"row\">" .
                                        "<div class=\"col-4\">" .
                                            "<div class=\"panel\">" .
                                                "<div class=\"panel-heading\">" .
                                                    "<h5 class=\"header\">YESTERDAY'S STATISTICS</h5>" .
                                                    "<hr>" .
                                                "</div>" .
                                                "<div class=\"row\">" . 
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>REVENUE GAINED: $". number_format($yesterday_statistics_array[0], 2, ".", "") ."</h6>" .
                                                    "</div>" .
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>NUMBER OF PRODUCTS SOLD: ". $yesterday_statistics_array[1] ."</h6>" .
                                                    "</div>" .
                                                "</div>" .
                                            "</div>" .
                                        "</div>" ;

            $today_statistics_stmt = $conn->prepare("SELECT sum(mydb.Cart_Item.price * mydb.Cart_Item.quantity) AS today_revenue, SUM(mydb.Cart_Item.quantity) AS today_units_sold 
                                                     FROM mydb.Cart_Item 
                                                     INNER JOIN mydb.Order_History 
                                                     ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                     WHERE mydb.Order_History.purchased=? AND mydb.Order_History.order_at BETWEEN ? AND ?;");

            //GET TODAY'S DATE
            date_default_timezone_set("Singapore");
            $today_start_datetime = date('Y-m-d 00:00:00', time()); 
            $today_end_datetime = date('Y-m-d 23:59:59', time());
            $today_statistics_stmt->bind_param("iss", $purchased, $today_start_datetime, $today_end_datetime);
            $today_statistics_stmt->execute();

            $today_statistics_result = $today_statistics_stmt->get_result();
            if ($today_statistics_result->num_rows > 0) {
                while ($row = $today_statistics_result->fetch_assoc()) {
                    array_push($today_statistics_array, $row["today_revenue"], $row["today_units_sold"]);
                }
            }
            
            if ($today_statistics_array[1] == null) {
                $today_statistics_array[1] = 0;
            }
            
            $html_output .=             "<div class=\"col-4\">" .
                                            "<div class=\"panel\">" .
                                                "<div class=\"panel-heading\">" .
                                                    "<h5 class=\"header\">TODAY'S STATISTICS</h5>" .
                                                    "<hr>" .
                                                "</div>" .
                                                "<div class=\"row\">" . 
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>REVENUE GAINED: $". number_format($today_statistics_array[0], 2, ".", "") ."</h6>" .
                                                    "</div>" .
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>NUMBER OF PRODUCTS SOLD: ". $today_statistics_array[1] ."</h6>" .
                                                    "</div>" .
                                                "</div>" .
                                            "</div>" .
                                        "</div>" ;

            $monthly_statistics_stmt = $conn->prepare("SELECT SUM(mydb.Cart_Item.price * mydb.Cart_Item.quantity) AS monthly_revenue, SUM(mydb.Cart_Item.quantity) AS monthly_units_sold 
                                                       FROM mydb.Cart_Item 
                                                       INNER JOIN mydb.Order_History 
                                                       ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                       WHERE mydb.Order_History.purchased=? AND MONTH(mydb.Order_History.order_at)=? AND YEAR(mydb.Order_History.order_at)=?;");

            //GET THIS MONTH
            date_default_timezone_set("Singapore");
            $current_month_word = strtoupper(date("F"));
            $current_month = date('m');
            $current_year = date('Y');
            $monthly_statistics_stmt->bind_param("iss", $purchased, $current_month, $current_year);
            $monthly_statistics_stmt->execute();

            $monthly_statistics_result = $monthly_statistics_stmt->get_result();
            if ($monthly_statistics_result->num_rows > 0) {
                while ($row = $monthly_statistics_result->fetch_assoc()) {
                    array_push($monthly_statistics_array, $row["monthly_revenue"], $row["monthly_units_sold"]);
                }
            }
            
            if ($monthly_statistics_array[1] == null) {
                $monthly_statistics_array[1] = 0;
            }
            
            $html_output .=             "<div class=\"col-4\">" .
                                            "<div class=\"panel\">" .
                                                "<div class=\"panel-heading\">" .
                                                    "<h5 class=\"header\">".$current_month_word."'S STATISTICS</h5>" .
                                                    "<hr>" .
                                                "</div>" .
                                                "<div class=\"row\">" . 
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>REVENUE GAINED: $". number_format($monthly_statistics_array[0], 2, ".", "") ."</h6>" .
                                                    "</div>" .
                                                    "<div class=\"col-lg-6 panel-body\">" .
                                                        "<h6>NUMBER OF PRODUCTS SOLD: ". $monthly_statistics_array[1] ."</h6>" .
                                                    "</div>" .
                                                "</div>" .
                                            "</div>" .
                                        "</div>" .
                                    "</div>" .
                                    "<br>" .
                                    "<br>" .
                                    "<div class=\"panel-heading\">" .
                                        "<h3 class=\"header\">ALL-TIME TOP 10 BEST SELLERS</h3>" .
                                        "<hr>" .
                                    "</div>" .
                                    "<div id=\"columnChartContainer\"></div>" .
                                    "<script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>" .
                                    "<br>" .
                                    "<br>" .
                                    "<div class=\"panel-heading\">" .
                                        "<h3 class=\"header\">STORE REVENUE BY MONTH</h3>" .
                                        "<hr>" .
                                    "</div>" .
                                    "<div id=\"lineChartContainer\"></div>" .
                                    "<script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>" .
                                "</div>" .
                            "</main>";    
            $conn->close();
            
            if(isset($_SESSION['username']) && !empty($_SESSION['username']) && $_SESSION['priority'] == 1){
                echo $html_output;
            } else {
                echo '<div class="alert alert-danger" role="alert">'.
                        '<p>You must be logged in as administrator to access this page</p>' . 
                     '</div>';
            }
            ?>
        </main>
    </body>
</html>


    		