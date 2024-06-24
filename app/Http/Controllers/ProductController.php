<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    
/**
 * This is POST method and will perform the solution for get one and buy one free sales campaign
 * @param String prices
 * @param String rule
 * @return view form.blade
 * 
 */
    public function submit_prices(Request $request)
    { 
        if($request->prices && $request->rule) {
           $results = null;
            $prices = explode(",",$request->prices);
            rsort($prices);//make descending order the items
            switch($request->rule) {
                case 'rule1':
                    $results = $this->validate_prices_with_rule1($prices); //return array with discount & payable item prices
                    break;
                case 'rule2':
                    $results = $this->validate_prices_with_rule2($prices); //return array with discount & payable item prices
                    break;
                case 'rule3':
                    $results = $this->validate_prices_with_rule3($prices); //return array with discount & payable item prices
                break;
            }
            //$results is an array with keys payable & discount - comma separated item values 
            return view('form',['results' => $results,'input_prices' => $request->prices,'input_rule'=>$request->rule]);
        }   
    }

/**
 * Process the Item prices with the given Rule1
 * --Customers can buy one product and get another product for free as long as the price of the free product is equal to or less than the price of the first product.
 * @param ARRAY $prices
 * @return ARRAY
 */
    public function validate_prices_with_rule1($prices)
    {
       $total = count($prices);
       $discountedItems = [];
       $payableItems = [];
       for($i = 0; $i< $total; $i+=2) {
            $payableItems[] = $prices[$i];
            if(isset($prices[$i+1])) {
                $discountedItems[] = $prices[$i+1];
            }
       }
       return ['discount' => implode(", ",$discountedItems), 'payable' => implode(", ",$payableItems)];
    }
/**
 * Process the Item prices with the given Rule2
 * Customers can buy one product and get another product for free as long as the price of the free product is less than the price of the first product.
 * @param ARRAY $prices
 * @return ARRAY
 */
    public function validate_prices_with_rule2($prices)
    {
       $total = count($prices);
       $discountedItems = [];
       $payableItems = [];
       $stock = $prices;
       for($i = 0; $i< $total; $i++) {
            if($stock && key_exists($i,$stock)) {
                $payableItems[] = $prices[$i]; //payable item
                unset($stock[$i]); //removing payable item from stock
                $discountItem = $this->findNearestSmallerElement($stock,$prices[$i]); //will give the nearest small item price
                if($discountItem) {
                    $discountedItems[] = $discountItem; // discount item
                    unset($stock[array_search($discountItem,$stock)]);  //remove disount item from stock
                }
            }
        }
        return ['discount' => implode(", ",$discountedItems), 'payable' => implode(", ",$payableItems)];
    }

/**
 * Process the Item prices with the given Rule3
 * Customers can buy two products and get two products for free as long as the price of the free product is less than the price of the first product.
 * @param ARRAY $prices
 * @return ARRAY
 */
    public function validate_prices_with_rule3($prices)
    {
       $total = count($prices);
       $discountedItems = [];
       $payableItems = [];
       $stock = $prices;
       for($i = 0; $i< $total; $i++) {
        $price1 = $price2 = null;
            if($stock && key_exists($i,$stock)) {
                $payableItems[] = $prices[$i];
                $price1 = $prices[$i];  
                unset($stock[$i]); //remove payable Item from stock
            }
            if($stock && key_exists($i+1,$stock)) {
                $payableItems[] = $prices[$i+1];
                $price2 = $prices[$i+1];
                unset($stock[$i+1]); //remove payable Item from stock
            }
            if($price1 !== null) {
                $discountItem1 = $this->findNearestSmallerElement($stock,$prices[$i]);
                if($discountItem1) {
                    unset($stock[array_search($discountItem1,$stock)]); //remove discountable item from stock
                    $discountedItems[] = $discountItem1;
                }
            }
            if($price2 !== null) {
                $discountItem2 = $this->findNearestSmallerElement($stock,$price2);
                if($discountItem2) {
                    unset($stock[array_search($discountItem2,$stock)]); //remove discountable item from stock
                    $discountedItems[] = $discountItem2;
                }
            }

        }   
        return ['discount' => implode(", ",$discountedItems), 'payable' => implode(", ",$payableItems)];
    }

/**
 * This function will take the array and element value and return the nearest small number on the given array.
 * @param ARRAY $array
 * @param NUMBER $num
 * @return NULL|NUMBER
 */
    public function findNearestSmallerElement($array, $num) {
        // Filter the array to keep only elements smaller than N
        $filteredArray = array_filter($array, function($element) use ($num) {
            return $element < $num;
        });
        // If the filtered array is not empty, return the maximum value
        if (!empty($filteredArray)) {
            return max($filteredArray);
        }
        // Return null if no smaller element is found
        return null;
    }
}
