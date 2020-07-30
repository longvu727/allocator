<?php
    include('../Allocator.php');
    include('./TestHelper.php');
    
    function randTest() {
        $inventory = array(
            "A" => 15,
            "B" => 15,
            "C" => 10,
            "D" => 10,
            "E" => 10
        );
        
        $allocator = new Allocator( $inventory );
                
        $i=1;
        while( $allocator->hasStocks() && $i++ ) {
            $input = generateInput( $i, $inventory );
            $response = $allocator->allocate($input);
            
            print_r( array( "input" => $input, "response" => $response ) );
        }
        
        $order_history = $allocator->orderHistory();
        $order_history = json_decode($order_history, JSON_OBJECT_AS_ARRAY);
        
        printOrderHistory( $order_history );
        
    }
    
    randTest();
?>