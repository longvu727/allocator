<?php
    class Allocator {
        private $inventory;
        private $orders;
        
        function __construct( $inventory=array() ) {
            $this->inventory = $inventory;
            
            if( !$this->inventory ) {
                $this->inventory = array(
                    "A" => 150,
                    "B" => 150,
                    "C" => 100,
                    "D" => 100,
                    "E" => 100
                );
            }
            
            $this->orders=array();
        }
        
        private function createCart($products) {
            $cart = array();
            
            foreach( $products as $product ) {
                $product_name = $product["Product"];
                $quantity = $product["Quantity"];
                
                $type = ($this->inventory[$product_name] < $quantity) ? 
                            "backordered" : 
                                "allocated";
                if( $type == "allocated" ) {
                    $this->inventory[$product_name] -= $quantity;
                }
                
                array_push( $cart, array(
                        "product"   => $product_name,
                        "type"      => $type,
                        "quantity"  => $quantity
                    )
                );
            }
            
            return $cart;
        }
        
        private function validateAllocation( $request ) {
            $response = array(
                "error" => "",
                "success" => False,
                "data" => Null,
            );
            
            if( !$request ) {
                $response["error"] = "Unable to parse request";
                return $response;
            }
            
            $products = $request["Lines"];
            
            if( !$products ) {
                $response["error"] = "Invalid order, no product";
                return $response;
            }
            
            if( !$this->hasStocks() ) {
                $response["error"] = "Sorry, Out of Stock";
                return $response;
            }
            
            $product_error_message = "";
            
            foreach( $products as $product ) {
                $product_name = $product["Product"];
                $quantity = $product["Quantity"];
                
                if( !isset($this->inventory[$product_name]) ) {
                    $product_error_message .= "Invalid order, Product $product_name is not available\n";
                }
                if( !(0 <= $quantity and $quantity <= 5) ) {
                    $product_error_message .= "Invalid order, Product $product_name, quantity is not in range of 0 to 5\n";
                }
            }
            
            
            if( $product_error_message != "" ) {
                $response["error"] = $product_error_message;
                return $response;
            }
            
            $response["success"] = True;
            return $response;
        }
        
        public function hasStocks() {
            $stocks = array_filter(
                $this->inventory, 
                function($quantity, $name) {return $quantity > 0;}, ARRAY_FILTER_USE_BOTH
            );
            
            return count($stocks) > 0;
        }
        
        public function allocate( $json_request ) {
            $response = array(
                "error" => "",
                "success" => false,
                "data" => Null,
            );
            
            $request = json_decode($json_request, JSON_OBJECT_AS_ARRAY);
            $validate_result = $this->validateAllocation( $request );
            
            if( ! $validate_result["success"] ) {
                $response["error"] = $validate_result["error"];
                return json_encode($response);
            }
            
            $order = array(
                "header"    => $request["Header"],
                "cart"      => $this->createCart( $request["Lines"] ),
            );
            
            array_push( $this->orders, $order );
            
            $response["success"] = true;
            $response["data"] = array( "order" => $order );
            
            return json_encode($response);
        }
        
        public function orderHistory() {
            $inventory_keys = array_keys( $this->inventory );
            $rows = array();
            
            foreach( $this->orders as $order ) {
                $row = [
                    "header"        => $order["header"],
                    "order"         => array_fill_keys($inventory_keys, 0),
                    "allocated"     => array_fill_keys($inventory_keys, 0),
                    "backordered"   => array_fill_keys($inventory_keys, 0)
                ];
                
                foreach( $order["cart"] as $item ) {
                    $product_name = $item["product"];
                    $quantity = $item["quantity"];
                    $type = $item["type"];
                    
                    $row["order"][$product_name] = $quantity;
                    
                    if( $type == "backordered" ) {
                        $row["backordered"][$product_name] = $quantity;
                    }
                    else {
                        $row["allocated"][$product_name] = $quantity;
                    }
                }
                
                array_push( $rows, $row );
            }
            
            return json_encode( $rows );
        }
    }
    
    
?>