<?php
$query = $_SERVER['QUERY_STRING'];

$ope  = array();
$buff = array();

//convert RPN
for($i = 0; $i < strlen($query); $i++) {
    $pattern = '/[\+]|[\-]|[\*]|[\/]|[\(]|[\)]/';
    $data = $query[$i];

    if (is_numeric($data)) {
        if(empty($buff)){
            array_push($buff, $data);
        }else{
            $temp = array_pop($buff);
            if (is_numeric($temp)){
                array_push($buff, $temp.$data);
            }else{
                array_push($buff, $temp);
                array_push($buff, $data);
            }
        }
    }elseif (preg_match($pattern, $data) == 1) {
        $temp = array_pop($buff);
        if (is_numeric($temp)){
            array_push($buff, $temp);
            array_push($buff, ' ');
        }else{
            array_push($buff, $temp);
        }
        
        if ($data == ')'){
            while(($temp = array_pop($ope)) != '(') {
                array_push($buff, $temp);
            }
        }elseif (empty($ope) or $data == '('){
            array_push($ope, $data);
        }elseif ($data == '*' or $data == '/'){
            $temp = array_pop($ope);
            if ($temp == '*' or $temp == '/'){
                array_push($buff, $temp);
                array_push($ope, $data);
            }else{
                array_push($ope, $temp);
                array_push($ope, $data);
            }
        }elseif($data == '+' or $data == '-'){
            $temp = array_pop($ope);
            if ($temp == '*' or $temp == '/' or $temp == '+' or $temp == '-'){
                array_push($buff, $temp);
                array_push($ope, $data);
            }else{
                array_push($ope, $temp);
                array_push($ope, $data);
            }
        }
    }else{
        print("ERROR");
        exit();
    }
}
while(empty($ope) != 1){
    $temp = array_pop($ope);
    array_push($buff, $temp);
}


//calc
$stack = array();
for($i = 0; $i < count($buff); $i++) {
    
  $pattern = '/[\+]|[\-]|[\*]|[\/]|[\(]|[\)]/';
  $data = $buff[$i];
  if (is_numeric($data)) {
        array_push($stack, $data);
  }elseif (preg_match($pattern, $data) == 1) {
    $x = $data;
    $a = array_pop($stack);
    $b = array_pop($stack);
    
    //print("$b$x$a\n\n");
    if ($x == '+')
      array_push($stack, $b + $a);
    elseif ($x == '-')
      array_push($stack, $b - $a);
    elseif ($x == '*')
      array_push($stack, $b * $a);
    elseif ($x == '/')
      array_push($stack, $b / $a);
  }
}
echo $stack[0]; //output

?>
