<!DOCTYPE html>
<html>
    <head>
        <?php
        include "header.inc.php";
        ?>
        <?php
            $all_time_top10_items_array = [];
                    
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            }
            
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
            
            $purchased = 1;
            $all_time_top10_items_stmt->bind_param("i", $purchased);
            $all_time_top10_items_stmt->execute();
        
            // Storing Top 10 Products Into An Array
            $all_time_top10_items_result = $all_time_top10_items_stmt->get_result();
            if ($all_time_top10_items_result->num_rows > 0) {
                while ($row = $all_time_top10_items_result->fetch_assoc()) {
//                    array_push($all_time_top10_items_array, array("x"=>$row["product_name"], "y"=>$row["quantity_sold"]));
                }
            }
            
            for ($i = 0; $i < count($all_time_top10_items_array); $i+=1) {
                echo implode(",", $all_time_top10_items_array[$i]);
            }
//            
//            for ($i = 0; $i < count($columnDataPoints); $i+=1) {
//                echo $columnDataPoints[$i];
//                "<br>";
//            }
//            $columnDataPoints = 
//            [array("x"=> 10, "y"=> 41),
//            array("x"=> 20, "y"=> 35, "indexLabel"=> "Lowest"),
//            array("x"=> 30, "y"=> 50),
//            array("x"=> 40, "y"=> 45),
//            array("x"=> 50, "y"=> 52),
//            array("x"=> 60, "y"=> 68),
//            array("x"=> 70, "y"=> 38),
//            array("x"=> 80, "y"=> 71, "indexLabel"=> "Highest"),
//            array("x"=> 90, "y"=> 52),
//            array("x"=> 100, "y"=> 60),
//            array("x"=> 110, "y"=> 36),
//            array("x"=> 120, "y"=> 49),
//            array("x"=> 130, "y"=> 41)];
            
            $lineDataPoints = array(
            array("x" => 946665000000, "y" => 3289000),
            array("x" => 978287400000, "y" => 3830000),
            array("x" => 1009823400000, "y" => 2009000),
            array("x" => 1041359400000, "y" => 2840000),
            array("x" => 1072895400000, "y" => 2396000),
            array("x" => 1104517800000, "y" => 1613000),
            array("x" => 1136053800000, "y" => 1821000),
            array("x" => 1167589800000, "y" => 2000000),
            array("x" => 1199125800000, "y" => 1397000),
            array("x" => 1230748200000, "y" => 2506000),
            array("x" => 1262284200000, "y" => 6704000),
            array("x" => 1293820200000, "y" => 5704000),
            array("x" => 1325356200000, "y" => 4009000),
            array("x" => 1356978600000, "y" => 3026000),
            array("x" => 1388514600000, "y" => 2394000),
            array("x" => 1420050600000, "y" => 1872000),
            array("x" => 1451586600000, "y" => 2140000));
             
        ?>
        <script>
        window.onload = function () {

            var columnChart = new CanvasJS.Chart("columnChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                backgroundColor: "transparent",
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                axisY: {
                    includeZero: true
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    //indexLabel: "{y}", //Shows y value on all Data Points
                    indexLabelFontColor: "#5A5757",
                    indexLabelPlacement: "outside",
                    dataPoints: <?php echo json_encode($all_time_top10_items_array, JSON_NUMERIC_CHECK); ?>
                }]
            });
            
            var lineChart = new CanvasJS.Chart("lineChartContainer", {
                animationEnabled: true,
                backgroundColor: "transparent",
                axisY: {
                        title: "Revenue in USD",
                        valueFormatString: "#0,,.",
                        suffix: "mn",
                        prefix: "$"
                },
                data: [{
                        type: "spline",
                        markerSize: 5,
                        xValueFormatString: "YYYY",
                        yValueFormatString: "$#,##0.##",
                        xValueType: "dateTime",
                        dataPoints: <?php echo json_encode($lineDataPoints, JSON_NUMERIC_CHECK); ?>
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

            $html_output = "";
            $html_output .= "<div class=\"container-fluid\">" .
                                          "<div class=\"row\">" . 
                                          "<div class=\"col-12\">" .
                                          "<div class=\"panel\">" .
                                          "<div class=\"panel-heading\">" .
                                          "<h3 class=\"header\">OVERALL STATISTICS</h3>" .
                                          "<hr>" .
                                          "</div>" .
                                          "<div class=\"row\">" .
                                          "<div class=\"col-6 panel-body\">" .
                                          "<h5>REVENUE GAINED $". number_format($overall_statistics_array[0], 2, ".", "") ."</h5>" .
                                          "</div>" .
                                          "<div class=\"col-6 panel-body\">" .
                                          "<h5>NUMBER OF PRODUCTS SOLD ". $overall_statistics_array[1] ."</h5>" .
                                          "</div>" .
                                          "</div>" .
                                          "</div>" .
                                          "</div>" .
                                          "</div>" .
                                          "<br>";

            $yesterday_statistics_stmt = $conn->prepare("SELECT sum(mydb.Cart_Item.price) AS yesterday_revenue, SUM(mydb.Cart_Item.quantity) AS yesterday_units_sold 
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

            $html_output .= "<div class=\"row\">" .
                            "<div class=\"col-4\">" .
                            "<div class=\"panel\">" .
                            "<div class=\"panel-heading\">" .
                            "<h3 class=\"header\">YESTERDAY'S STATISTICS</h3>" .
                            "<hr>" .
                            "</div>" .
                            "<div class=\"row\">" . 
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>REVENUE GAINED $". number_format($yesterday_statistics_array[0], 2, ".", "") ."</h5>" .
                            "</div>" .
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>NUMBER OF PRODUCTS SOLD ". $yesterday_statistics_array[1] ."</h5>" .
                            "</div>" .
                            "</div>" .
                            "</div>" .
                            "</div>" ;

            $today_statistics_stmt = $conn->prepare("SELECT sum(mydb.Cart_Item.price) AS today_revenue, SUM(mydb.Cart_Item.quantity) AS today_units_sold 
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

            $html_output .= "<div class=\"col-4\">" .
                            "<div class=\"panel\">" .
                            "<div class=\"panel-heading\">" .
                            "<h3 class=\"header\">TODAY'S STATISTICS</h3>" .
                            "<hr>" .
                            "</div>" .
                            "<div class=\"row\">" . 
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>REVENUE GAINED $". number_format($today_statistics_array[0], 2, ".", "") ."</h5>" .
                            "</div>" .
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>NUMBER OF PRODUCTS SOLD ". $today_statistics_array[1] ."</h5>" .
                            "</div>" .
                            "</div>" .
                            "</div>" .
                            "</div>" ;

            $monthly_statistics_stmt = $conn->prepare("SELECT SUM(mydb.Cart_Item.price) AS monthly_revenue, SUM(mydb.Cart_Item.quantity) AS monthly_units_sold 
                                                       FROM mydb.Cart_Item 
                                                       INNER JOIN mydb.Order_History 
                                                       ON mydb.Cart_Item.Order_History_order_id=mydb.Order_History.order_id
                                                       WHERE mydb.Order_History.purchased=1 AND MONTH(mydb.Order_History.order_at)=? AND YEAR(mydb.Order_History.order_at)=?;");

            //GET THIS MONTH
            date_default_timezone_set("Singapore");
            $current_month = date('m');
            $current_year = date('Y');
            $monthly_statistics_stmt->bind_param("ss", $current_month, $current_year);
            $monthly_statistics_stmt->execute();

            $monthly_statistics_result = $monthly_statistics_stmt->get_result();
            if ($monthly_statistics_result->num_rows > 0) {
                while ($row = $monthly_statistics_result->fetch_assoc()) {
                    array_push($monthly_statistics_array, $row["monthly_revenue"], $row["monthly_units_sold"]);
                }
            }

            $html_output .= "<div class=\"col-4\">" .
                            "<div class=\"panel\">" .
                            "<div class=\"panel-heading\">" .
                            "<h3 class=\"header\">CURRENT MONTH'S STATISTICS</h3>" .
                            "<hr>" .
                            "</div>" .
                            "<div class=\"row\">" . 
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>REVENUE GAINED $". number_format($monthly_statistics_array[0], 2, ".", "") ."</h5>" .
                            "</div>" .
                            "<div class=\"col-6 panel-body\">" .
                            "<h5>NUMBER OF PRODUCTS SOLD ". $monthly_statistics_array[1] ."</h5>" .
                            "</div>" .
                            "</div>" .
                            "</div>" .
                            "</div>" .
                            "</div>" ;

            echo $html_output;
            $conn->close(); 
            ?>
            <br>
            <br>
            <div class=\panel-heading\">
                <h3 class=\header\">ALL-TIME TOP 10 BEST SELLERS</h3>
                <hr>
            </div>
            <div id="columnChartContainer" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <br>
            <br>
            <div class=\panel-heading\">
                <h3 class=\header\">STORE REVENUE BY MONTH</h3>
                <hr>
            </div>
            <div id="lineChartContainer" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        </main>
    </body>
</html>


    		