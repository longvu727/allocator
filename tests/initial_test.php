<?php
    include('../Allocator.php');
    include('./TestHelper.php');
    
    function initialTest() {
        $inventory = array(
            "A" => 2,
            "B" => 3,
            "C" => 1,
            "D" => 0,
            "E" => 0
        );
        
        $tests = array(
            '{"Header": 1, "Lines":[{"Product": "A", "Quantity": "1"},{"Product": "C", "Quantity": "1"}]}',
            '{"Header": 2, "Lines": [{"Product": "E", "Quantity": "5"}]}',
            '{"Header": 3, "Lines": [{"Product": "D", "Quantity": "4"}]}',
            '{"Header": 4, "Lines": [{"Product": "A", "Quantity": "1"},{"Product": "C", "Quantity": "1"}]}',
            '{"Header": 5, "Lines": [{"Product": "B", "Quantity": "3"}]}',
            '{"Header": 6, "Lines": [{"Product": "D", "Quantity": "4"}]}'
        );
        
        $allocator = new Allocator($inventory);
        
        foreach( $tests as $test ) {
            print( "$test\n" );
            print( $allocator->allocate($test) );
            print("\n");
        }
        
        $order_history = $allocator->orderHistory();
        $order_history = json_decode($order_history, JSON_OBJECT_AS_ARRAY);
        
        printOrderHistory( $order_history );
    }
    
    initialTest();
?>
