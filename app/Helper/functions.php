<?php

function get_price($market_price,$group_price,$discount = 1,$cate_id=0){
    if($cate_id == 3 || $cate_id == 8){
        $discount = 1;
    }
    $market_price_new = $market_price*$discount;
    if($group_price < $market_price_new  && $group_price !=0){
        $price = $group_price;
    }else{
        $price = $market_price_new;
    }
    return $price;
}