<?php
    function generateInput($header, $inventory) {
        $lines = array();
        
        while( rand(0, 1) ) {
            $line = sprintf( '{"Product": "%s", "Quantity": %d}', 
                        array_rand($inventory),
                        rand(0, 6),
                    );
            array_push( $lines, $line );
        }
        
        $lines = implode(",", $lines);
        $input = sprintf('{"Header": %d, "Lines":[%s]}', $header, $lines);
        
        return $input;
    }
    function printOrderHistory($order_history) {
        foreach( $order_history as $row ) {
            $header = $row["header"];
            $order = implode( ",", array_values($row["order"]) );
            $allocated = $order = implode( ",", array_values($row["allocated"]) );
            $backordered = $order = implode( ",", array_values($row["backordered"]) );
            
            print("$header:$order:$allocated:$backordered\n");
        }
    }
?>