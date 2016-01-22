<?php

/**
 * Fansoro Charts Plugin
 *
 * (c) Romanenko Sergey / Awilum <awilum@msn.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Add Action
Action::add('theme_header', function () {
    echo('<script type="text/javascript" src="https://www.google.com/jsapi"></script>');
});

// Add Shortcode {chart}
Shortcode::add('chart', function ($attributes) {

    // Extract
    extract($attributes);

    // UID
    $uid = md5(uniqid(mt_rand()));

    // Data
    if (isset($data)) {
        $data_string = '';
        $_data = explode('|', $data);
        foreach ($_data as $d) {
            $part = explode(',', $d);
            $data_string .= '["'.$part[0].'", '.$part[1].'],';
        }

        $data = $data_string;
    } else {
        $data = '["test", 1],';
    }

    // Title
    if (isset($title)) {
        $title = $title;
    } else {
        $title = 'Charts';
    }

    // Type
    if (isset($type) && ($type == 'pie' || $type == 'bar')) {
        $type = $type;
    } else {
        $type = 'pie';
    }

    // Width
    if (isset($width)) {
        $width = $width;
    } else {
        $width = 400;
    }

    // Height
    if (isset($height)) {
        $height = $height;
    } else {
        $height = 300;
    }

    // Chart
    return ('<script type="text/javascript">

              // Load the Visualization API and the piechart package.
              google.load("visualization", "1.0", {"packages":["corechart"]});

              // Set a callback to run when the Google Visualization API is loaded.
              google.setOnLoadCallback(drawChart_'.$uid.');

              // Callback that creates and populates a data table,
              // instantiates the pie chart, passes in the data and
              // draws it.
              function drawChart_'.$uid.'() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn("string", "Topping");
                data.addColumn("number", "Slices");
                data.addRows([
                  '.$data.'
                ]);

                // Set chart options
                var options = {"title":"'.$title.'",
                               "width":'.(int)$width.',
                               "height":'.(int)$height.'};

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.'.ucfirst($type).'Chart(document.getElementById("chart_div_'.$uid.'"));
                chart.draw(data, options);
              }
            </script>'."\n".'<div id="chart_div_'.$uid.'"></div>');

});
