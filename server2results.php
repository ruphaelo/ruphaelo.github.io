<?php
session_start();

$table =$_SESSION['varTable'];

$id = '1';

$db = "m138026";

$c = mysqli_connect("localhost","root","",$db);

$sql = "SELECT * FROM $table WHERE id=$id";

$result = $c->query($sql);

while($row = $result->fetch_assoc()) {
  $data1 = $row['row3'];
  $data2 = $row['row4'];
  $data3 = $row['row5'];
  $data4 = $row['row6'];
}

$data1 = preg_split ("/\,/", $data1); 
$data2 = preg_split ("/\,/", $data2); 
$data3 = preg_split ("/\,/", $data3); 
$data4 = preg_split ("/\,/", $data4); 

$data=[];
$n = 0;
$m = 0;
for ($i = 0; $i < 4; $i++) {
  $data[] = [];
  if($i==0 & sizeof($data1)!=1){
    for ($j = 0; $j < (sizeof($data1)/29); $j++) {
      if($data1[((sizeof($data1)/29)-1-$j)*29+2]=='new'){
        $data[$i][] = [];
        for ($k = 0; $k < 29; $k++) {
          $data[$i][$n][] = $data1[((sizeof($data1)/29)-1-$j)*29+$k];
        }
        $n+=1;
      }
    }
    for ($j = 0; $j < (sizeof($data1)/29); $j++) {
      if($data1[((sizeof($data1)/29)-1-$j)*29+2]=='com'){
        for ($k = 0; $k < 29; $k++) {
          $data[$i][$n][] = $data1[((sizeof($data1)/29)-1-$j)*29+$k];
        }
        $n+=1;
      }
    }
    $m = $n;
    for ($j = 0; $j < (sizeof($data1)/29); $j++) {
      if($data1[((sizeof($data1)/29)-1-$j)*29+2]!='new' & $data1[((sizeof($data1)/29)-1-$j)*29+2]!='com'){
        $data[$i][] = [];
        for ($k = 0; $k < 29; $k++) {
          $data[$i][$n][] = $data1[((sizeof($data1)/29)-1-$j)*29+$k];
        }
        $n+=1;
      }
    }
  }
  if($i==1 & sizeof($data2)!=1){
    for ($j = 0; $j < (sizeof($data2)/38); $j++) {
      $data[$i][] = [];
      for ($k = 0; $k < 38; $k++) {
        $data[$i][$j][] = $data2[((sizeof($data2)/38)-1-$j)*38+$k];
      }
    }
  }
  if($i==2 & sizeof($data3)!=1){
    for ($j = 0; $j < (sizeof($data3)/18); $j++) {
      $data[$i][] = [];
      for ($k = 0; $k < 18; $k++) {
        $data[$i][$j][] = $data3[((sizeof($data3)/18)-1-$j)*18+$k];
      }
    }
  }
  if($i==3 & sizeof($data4)!=1){
    for ($j = 0; $j < (sizeof($data4)/9); $j++) {
      $data[$i][] = [];
      for ($k = 0; $k < 9; $k++) {
        $data[$i][$j][] = $data4[((sizeof($data4)/9)-1-$j)*9+$k];
      }
    }
  } 
}

if(sizeof($data1)==1 | $m == 0){
  $code = "<br>You need at least one data set to get a code.<br>";
}

if(sizeof($data1)!=1 & $m != 0){


$file = substr($data[0][0][28], 0, strrpos($data[0][0][28], '/')) . "/results.pdf";
$code = "<br>Your results will be saved in '" . $file . "'.<br><br>";
$code .= "Your code:<br><br>";
$code .= "library('lubridate')<br>";
$code .= "library('varhandle')<br>";
$code .= "library('ggplot2')<br>";
$code .= "library('vtable')<br>";
$code .= "library('car')<br>";
$code .= "library('rstatix')<br>";
$code .= "library('ggh4x')<br>";
$code .= "library('cluster')<br>";
$code .= "library('PredictABEL')<br>";
$code .= "library('margins')<br>";
$code .= "library('gridExtra')<br>";
$code .= "library('data.table')<br>";
$code .= "file.create('" . $file . "')<br>";
$code .= "pdf('" . $file . "')<br>";
for ($i = 0; $i < $m; $i++) {
  if(str_ends_with($data[0][$i][28],'.csv')){
    $code .= "data" . $i+1 . " <- fread('" . $data[0][$i][28] . "')<br>";
  }
  if(str_ends_with($data[0][$i][28],'.xsl') | str_ends_with($data[0][$i][28],'.xslx')){
    $code .= "data" . $i+1 . " <- read_excel('" . $data[0][$i][28] . "')<br>";
  }
  if(str_ends_with($data[0][$i][28],'.txt')){
    $code .= "data" . $i+1 . " <- read.table('" . $data[0][$i][28] . "')<br>";
  }
  $code .= "data" . $i+1 . "[data" . $i+1 . " == ''] <- NA<br>";
  $code .= "for(i in (1:ncol(data" . $i+1 . "))){<br>";
  $code .= "data" . $i+1 . "[[i]] <- as.character(data" . $i+1 . "[[i]])<br>";
  $code .= "data" . $i+1 . "[[paste0(colnames(data" . $i+1 . ")[i],'_LABEL')]] <- data" . $i+1 . "[[i]]<br>";
  $code .= "}<br>";
}


if(sizeof($data1)!=1){
  for ($i = 0; $i < (sizeof($data[0])); $i++) {
    if($data[0][$i][2]=='rename'){
      $data[0][$i][4] = preg_split ("/\;/", $data[0][$i][4]);
      for ($j = 0; $j < (sizeof($data[0][$i][4])-1)/2; $j++) {
        $code .= "levels <- levels(data" . $data[0][$i][3] . "$" . $data[0][$i][4][$j*2] . ")<br>";
        $code .= "colnames(data" . $data[0][$i][3] . "[colnames(" . "data" . $data[0][$i][3] . ") == '" . $data[0][$i][4][$j*2] . "']) <- '" . $data[0][$i][4][$j*2+1] . "'<br>";
        $code .= "colnames(data" . $data[0][$i][3] . "[colnames(" . "data" . $data[0][$i][3] . ") == '" . $data[0][$i][4][$j*2] . "_LABEL']) <- '" . $data[0][$i][4][$j*2+1] . "_LABEL'<br>";
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='false'){
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == '" . $data[0][$i][11][$j*2] . "', '" . $data[0][$i][11][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7];
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='true'){
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "_LABEL<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == '" . $data[0][$i][11][$j*2] . "', '" . $data[0][$i][11][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7];
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='false'){
      $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- ifelse(";
        for ($k = 0; $k < (sizeof($data[0][$i][11])-1)/2; $k++) {
          $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " == '" . $data[0][$i][11][$k*2] . "', '" . $data[0][$i][11][$k*2+1] . "', ifelse(";
        }
        $code = substr($code, 0, -7);
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j];
        for ($k = 0; $k < (sizeof($data[0][$i][11])-1)/2; $k++) {
          $code .= ')';
        }
        $code .= '<br>';
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='true'){
      $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ")<br>";
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- ifelse(";
        for ($k = 0; $k < (sizeof($data[0][$i][11])-1)/2; $k++) {
          $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " == '" . $data[0][$i][11][$k*2] . "', '" . $data[0][$i][11][$k*2+1] . "', ifelse(";
        }
        $code = substr($code, 0, -7);
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j];
        for ($k = 0; $k < (sizeof($data[0][$i][11])-1)/2; $k++) {
          $code .= ')';
        }
        $code .= '<br>';
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='false'){
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " == '" . $data[0][$i][11][$j*2] . "', '" . $data[0][$i][11][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
      $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='valtoval' & $data[0][$i][10]=='true'){
      $data[0][$i][11] = preg_split ("/\;/", $data[0][$i][11]);
      $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
      $code .= "data" . $data[0][$i][3] . "[[paste0(colnames(i),'_LABEL')]]" . " <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " == '" . $data[0][$i][11][$j*2] . "', '" . $data[0][$i][11][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]";
      for ($j = 0; $j < (sizeof($data[0][$i][11])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
      $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='valtomis'){
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
      $data[0][$i][12] = preg_split ("/\;/", $data[0][$i][12]);
      for ($j = 0; $j < sizeof($data[0][$i][12])-1; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == '" . $data[0][$i][12][$j] . "'] <- NA<br>";
      }
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='valtomis'){
      $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
      $data[0][$i][12] = preg_split ("/\;/", $data[0][$i][12]);
      for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
        for ($k = 0; $k < sizeof($data[0][$i][12])-1; $k++) {
          $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL[data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " == '" . $data[0][$i][12][$k] . "'] <- NA<br>";
          $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " == '" . $data[0][$i][12][$k] . "'] <- NA<br>";
        }
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='valtomis'){
      $data[0][$i][12] = preg_split ("/\;/", $data[0][$i][12]);
      for ($j = 0; $j < sizeof($data[0][$i][12])-1; $j++) {
        $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
        $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "[data" . $data[0][$i][3] . "[[colnames(i)]]" . " == '" . $data[0][$i][12][$j] . "'] <- NA<br>";
        $code .= "}<br>";   
        $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='mistoval'){
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL[data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == NA] <- '" . $data[0][$i][13] . "'<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == NA] <- '" . $data[0][$i][13] . "'<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='mistoval'){
      $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
      for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL[data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j]. " == NA] <- '" . $data[0][$i][13] . "'<br>";
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j]. " == NA] <- '" . $data[0][$i][13] . "'<br>";
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='mistoval'){
      $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
      $code .= "data" . $data[0][$i][3] . "[[paste0(colnames(i),'_LABEL')]]" . "[data" . $data[0][$i][3] . "[[colnames(i)]]" . " == NA] <- '" . $data[0][$i][13] . "'<br>";
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "[data" . $data[0][$i][3] . "[[colnames(i)]]" . " == NA] <- '" . $data[0][$i][13] . "'<br>";
      $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='dty' & str_contains($data[0][$i][14],'yyyy')){
       $order = str_replace('yyyy','y',$data[0][$i][14]);
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.character(parse_date_time(data" . $data[0][$i][3] . "$" . $data[0][$i][7] . ", order = '" . $order . "'))<br>";
       $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],9,10))/365<br>";
       $code .= "}<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][7] . ")<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='dty' & !str_contains($data[0][$i][14],'yyyy') & $data[0][$i][14]!=''){
       $order = str_replace('yy','y',$data[0][$i][14]);
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.character(parse_date_time2(data" . $data[0][$i][3] . "$" . $data[0][$i][7] . ", order = '" . $order . "'))<br>";
       $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[i],9,10))/365<br>";
       $code .= "}<br>";
       $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]] <- ifelse(as.numeric(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]] - " . $data[0][$i][15] . " >= 100, (as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]]) - " . $data[0][$i][15] . ")%%100 + " . $data[0][$i][15] . ", data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "[[i]])<br>";
       $code .= "}<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][7] . ")<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='dty' & str_contains($data[0][$i][14],'yyyy')){
       $order = str_replace('yyyy','y',$data[0][$i][14]);
       $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
       for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(parse_date_time(data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ", order = '" . $order . "'))<br>";
         $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],9,10))/365<br>";
         $code .= "}<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ")<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
       }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='dty' & !str_contains($data[0][$i][14],'yyyy') & $data[0][$i][14]!=''){
       $order = str_replace('yy','y',$data[0][$i][14]);
       $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
       for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(parse_date_time2(data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ", order = '" . $order . "'))<br>";
         $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[i],9,10))/365<br>";
         $code .= "}<br>";
         $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]] <- ifelse(as.numeric(" . "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]] - " . $data[0][$i][15] . " >= 100, as.character((as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]]) - " . $data[0][$i][15] . ")%%100 + " . $data[0][$i][15] . "), data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "[[i]])<br>";
         $code .= "}<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ")<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
       }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='dty' & str_contains($data[0][$i][14],'yyyy')){
       $order = str_replace('yyyy','y',$data[0][$i][14]);
       $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . " <- as.character(parse_date_time(data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . ", order = '" . $order . "'))<br>";
       $code .= "for(j in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[j]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[j],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[j],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[j],9,10))/365<br>";
       $code .= "}<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " <- as.character(data"  . $data[0][$i][3] . "[[colnames(i)]]" . ")<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
       $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='dty' & !str_contains($data[0][$i][14],'yyyy') & $data[0][$i][14]!=''){
       $order = str_replace('yy','y',$data[0][$i][14]);
       $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . " <- as.character(parse_date_time2(data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . ", order = '" . $order . "'))<br>";
       $code .= "for(i in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[i]] <- as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[i],1,4)) + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[i],6,7))/12 + as.numeric(substr(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[i],9,10))/365<br>";
       $code .= "}<br>";
       $code .= "for(j in (1:nrow(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[j]] <- ifelse(as.numeric(" . "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[j]] - " . $data[0][$i][15] . " >= 100, as.character((as.numeric(data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[j]]) - " . $data[0][$i][15] . ")%%100 + " . $data[0][$i][15] . "), data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . "[[j]])<br>";
       $code .= "}<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " <- as.character(data"  . $data[0][$i][3] . "[[colnames(i)]]" . ")<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
       $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='numeric'){
       if($data[0][$i][16] == 'plus'){
          $data[0][$i][16] = '+';
       }
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][7] . ")" . $data[0][$i][16] . $data[0][$i][17] . "<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][7] . ")<br>";
       $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='numeric'){
       if($data[0][$i][16] == 'plus'){
          $data[0][$i][16] = '+';
       }
       $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
       for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ")" . $data[0][$i][16] . $data[0][$i][17] . "<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " <- as.character(data"  . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . ")<br>";
         $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "<br>";
       }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='numeric'){
       if($data[0][$i][16] == 'plus'){
          $data[0][$i][16] = '+';
       }
       $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
       $code .= "data" . $data[0][$i][3] . "$" . "[[colnames(i)]]" . " <- as.numeric(data" . $data[0][$i][3] . "$" . "[[colnames(i)]])" . $data[0][$i][16] . $data[0][$i][17] . "<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " <- as.character(data"  . $data[0][$i][3] . "[[colnames(i)]]" . ")<br>";
       $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- data" . $data[0][$i][3] . "[[colnames(i)]]" . "<br>";
       $code .= "}<br>";
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='create' & $data[0][$i][9]=='relabel'){
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][6] . "_LABEL<br>";
      $data[0][$i][19] = preg_split ("/\;/", $data[0][$i][19]);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . "_LABEL <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][19])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7] . " == '" . $data[0][$i][19][$j*2] . "', '" . $data[0][$i][19][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][7];
      for ($j = 0; $j < (sizeof($data[0][$i][19])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
    }
     if($data[0][$i][2]=='val' & $data[0][$i][5]=='spec' & $data[0][$i][9]=='relabel'){
      $data[0][$i][8] = preg_split ("/\;/", $data[0][$i][8]);
      $data[0][$i][19] = preg_split ("/\;/", $data[0][$i][19]);
      for ($j = 0; $j < sizeof($data[0][$i][8])-1; $j++) {
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . "_LABEL <- ifelse(";
        for ($k = 0; $k < (sizeof($data[0][$i][19])-1)/2; $k++) {
          $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j] . " == '" . $data[0][$i][19][$k*2] . "', '" . $data[0][$i][19][$k*2+1] . "', ifelse(";
        }
        $code = substr($code, 0, -7);
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][8][$j];
        for ($k = 0; $k < (sizeof($data[0][$i][19])-1)/2; $k++) {
          $code .= ')';
        }
        $code .= '<br>';
      }
    }
    if($data[0][$i][2]=='val' & $data[0][$i][5]=='all' & $data[0][$i][9]=='relabel'){
      $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
      $data[0][$i][19] = preg_split ("/\;/", $data[0][$i][19]);
      $code .= "for(i in (1:ncol(data" . $data[0][$i][3] . "))){<br>";
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . "_LABEL <- ifelse(";
      for ($j = 0; $j < (sizeof($data[0][$i][19])-1)/2; $j++) {
        $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]" . " == '" . $data[0][$i][11][$j*2] . "', '" . $data[0][$i][19][$j*2+1] . "', ifelse(";
      }
      $code = substr($code, 0, -7);
      $code .= "data" . $data[0][$i][3] . "[[colnames(i)]]";
      for ($j = 0; $j < (sizeof($data[0][$i][19])-1)/2; $j++) {
        $code .= ')';
      }
      $code .= '<br>';
      $code .= "}<br>";
      $code .= "}<br>";
    }
    if($data[0][$i][2]=='join'){
      $data[0][$i][18] = preg_split ("/\;/", $data[0][$i][18]);
      for ($j = 1; $j < (sizeof($data[0][$i][18])-1)/2; $j++) {
        if($data[0][$i][18][$j*2] == 'plus'){
          $data[0][$i][18][$j*2] = '+';
        }
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][18][0] . " <- as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][18][0] . ") " . $data[0][$i][18][$j*2] . "as.numeric(data" . $data[0][$i][3] . "$" . $data[0][$i][18][($j*2)-1] . ")<br>";
        $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][18][0] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][18][0] . "<br>";
      }
    }
    if($data[0][$i][2]=='spread'){
      $code .= "data" . $i+1 . " <- spread(data" . $i+1 . ", " . $data[0][$i][20] . ", " . $data[0][$i][21] . ")<br>";
      
    }
    if($data[0][$i][2]=='gather'){
      $data[0][$i][24] = preg_split ("/\;/", $data[0][$i][24]);
      $code .= "data" . $data[0][$i][3] . " <- gather(data" . $data[0][$i][3] . ", " . $data[0][$i][22] . ", " . $data[0][$i][23] . ", c(";
      for ($j = 0; $j < sizeof($data[0][$i][24])-1; $j++) { 
        $code .= $data[0][$i][24][$j] . ", ";
      }
      $code = substr($code, 0, -2);
      $code .= "))<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][22] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][22] . "<br>";
      $code .= "data" . $data[0][$i][3] . "$" . $data[0][$i][23] . "_LABEL <- data" . $data[0][$i][3] . "$" . $data[0][$i][23] . "<br>";
    }
    if($data[0][$i][2]=='ex'){
      $data[0][$i][25] = preg_split ("/\;/", $data[0][$i][25]);
      for ($j = 0; $j < (sizeof($data[0][$i][25])-1)/3; $j++) {
        if($data[0][$i][25][$j*3+1] == '!='){
          $code .= "data" . $data[0][$i][3] . " <- subset(data" . $data[0][$i][3] . ", data" . $data[0][$i][3] . "$" . $data[0][$i][25][$j*3] . " != '" . $data[0][$i][25][$j*3+2] . "' | is.na(data" . $data[0][$i][3] . "$" . $data[0][$i][25][$j*3] . "))<br>";
        }else{
          $code .= "data" . $data[0][$i][3] . " <- subset(data" . $data[0][$i][3] . ", data" . $data[0][$i][3] . "$" . $data[0][$i][25][$j*3] . " " . $data[0][$i][25][$j*3+1] . " '" . $data[0][$i][25][$j*3+2] . "')<br>";
        }
      }
    }
    if($data[0][$i][2]=='com'){
      $data[0][$i][27] = preg_split ("/\;/", $data[0][$i][27]);
      $code .= "data" . $i+1 . " <- data" . $data[0][$i][27][0] . "<br>";
      for ($j = 1; $j < sizeof($data[0][$i][27])-1; $j++) {
        $code .= "data" . $i+1 . " <- merge(x = data" . $i+1 . ", y = data" . $data[0][$i][27][$j] . ", by = '" . $data[0][$i][26] . "', all.x=TRUE)<br>";
      }
    }
    if($data[0][$i][2]=='new'){
      if(str_ends_with($data[0][$i][28],'.csv')){
        $code .= "data" . $i+1 . " <- fread('" . $data[0][$i][28] . "')<br>";
      }
      if(str_ends_with($data[0][$i][28],'.xsl') | str_ends_with($data[0][$i][28],'.xslx')){
        $code .= "data" . $i+1 . " <- read_excel('" . $data[0][$i][28] . "')<br>";
      }
      if(str_ends_with($data[0][$i][28],'.txt')){
        $code .= "data" . $i+1 . " <- read.table('" . $data[0][$i][28] . "')<br>";
      }
      $code .= "data" . $i+1 . "[data" . $i+1 . " == ''] <- NA<br>";
      $code .= "for(i in (1:ncol(data" . $i+1 . "))){<br>";
      $code .= "data" . $i+1 . "[[i]] <- as.character(data" . $i+1 . "[[i]])<br>";
      $code .= "data" . $i+1 . "[[paste0(colnames(data" . $i+1 . ")[i],'_LABEL')]] <- data" . $i+1 . "[[i]]<br>";
      $code .= "}<br>";
    }
  }
}


for ($i = 0; $i < $m; $i++) {
$code .= "data_table <- data" . $i+1 . "<br>";
$code .= "for(i in (1:ncol(data_table))){<br>";
$code .= "if(all(check.numeric(data_table[[i]]) == TRUE)){<br>";
$code .= "data_table[[i]] <- as.numeric(data_table[[i]])<br>";
$code .= "}<br>";
$code .= "}<br>";
$code .= "sumtable(data_table)<br>";
}

if(sizeof($data2)!=1){
  for ($i = 0; $i < (sizeof($data[1])); $i++) {
    $code .= "data_graphic <- data" . $data[1][$i][2] . "<br>";
    if($data[1][$i][37] != ''){
      $data[1][$i][37] = preg_split("/\;/", $data[1][$i][37]);
      for ($j = 0; $j < (sizeof($data[1][$i][37])-1)/3; $j++) {
        $code .= "data_graphic <- subset(data_graphic, data_graphic$" . $data[1][$i][37][$j] . " " . $data[1][$i][37][$j+1] . " '" . $data[1][$i][37][$j+2] . "')<br>";
      }
    }
    if($data[1][$i][4] == 'pie'){
      $j = 8;
    }
    if($data[1][$i][4] == 'barplot' & ($data[1][$i][9] == 'count' | $data[1][$i][9] == 'percentage')){
      $j = 13;
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2'){
      $j = 14;
    }
    if($data[1][$i][4] == 'histogram'){
      $j = 29;
    }
    if(($data[1][$i][4] == 'pie' & $data[1][$i][5] == 'false') | ($data[1][$i][4] == 'barplot' & ($data[1][$i][9] == 'count' | $data[1][$i][9] == 'percentage') & $data[1][$i][10] == 'false') | $data[1][$i][4] == 'histogram'){
      $code .= "data_graphic <- subset(data_graphic, !is.na(data_graphic$" . $data[1][$i][$j] . "))<br>";
    }
    if($data[1][$i][4] == 'pie' | $data[1][$i][4] == 'barplot'){
    $code .= "if(all(check.numeric(data_graphic$" . $data[1][$i][$j] . ") == TRUE)){<br>";
    $code .= "data_graphic$" . $data[1][$i][$j] . " <- as.numeric(data_graphic$" . $data[1][$i][$j] . ")<br>";
    $code .= $data[1][$i][$j] . " <- unique(data_graphic[order(as.numeric(data_graphic$" . $data[1][$i][$j] . ")),]$" . $data[1][$i][$j] . "_LABEL)<br>";
    $code .= "}else{<br>";
    $code .= $data[1][$i][$j] . " <- unique(data_graphic[order(data_graphic$" . $data[1][$i][$j] . "),]$" . $data[1][$i][$j] . "_LABEL)<br>";
    $code .= "}<br>";
    }
    if(($data[1][$i][4] == 'pie' & $data[1][$i][6] == 'false' & $data[1][$i][7] == 'false') | ($data[1][$i][4] == 'barplot' & $data[1][$i][11] == 'false' & $data[1][$i][12] == 'false')){
      $cop = "''";
    }
    if(($data[1][$i][4] == 'pie' & $data[1][$i][6] == 'true' & $data[1][$i][7] == 'false') | ($data[1][$i][4] == 'barplot' & $data[1][$i][11] == 'true' & $data[1][$i][12] == 'false')){
      $cop = "Freq";
    }
    if(($data[1][$i][4] == 'pie' & $data[1][$i][6] == 'false' & $data[1][$i][7] == 'true') | ($data[1][$i][4] == 'barplot' & $data[1][$i][11] == 'false' & $data[1][$i][12] == 'true')){
      $cop = "paste0(round(Freq/sum(Freq)*100,2),'%')";
    }
    if(($data[1][$i][4] == 'pie' & $data[1][$i][6] == 'true' & $data[1][$i][7] == 'true') | ($data[1][$i][4] == 'barplot' & $data[1][$i][11] == 'true' & $data[1][$i][12] == 'true')){
      $cop = "paste0(Freq,'\n(',round(Freq/sum(Freq)*100,2),'%)')";
    }
    if($data[1][$i][4] == 'histogram' & ($data[1][$i][23] == 'his' | $data[1][$i][23] == 'both') & $data[1][$i][25] == 'false' & $data[1][$i][26] == 'false'){
      $cop = "";
    }
    if($data[1][$i][4] == 'histogram' & ($data[1][$i][23] == 'his' | $data[1][$i][23] == 'both') & $data[1][$i][25] == 'true' & $data[1][$i][26] == 'false'){
      $cop = "stat_bin(binwidth=1, geom='text', color='black', aes(label=..count..))";
    }
    if($data[1][$i][4] == 'histogram' & ($data[1][$i][23] == 'his' | $data[1][$i][23] == 'both') & $data[1][$i][25] == 'false' & $data[1][$i][26] == 'true'){
      $cop = "stat_bin(binwidth=1, geom='text', color='black', aes(label=paste0(round(..count../sum(..count..)*100,2),'%')))";
    }
    if($data[1][$i][4] == 'histogram' & ($data[1][$i][23] == 'his' | $data[1][$i][23] == 'both') & $data[1][$i][25] == 'true' & $data[1][$i][26] == 'true'){
      $cop = "stat_bin(binwidth=1, geom='text', color='black', aes(label=paste0(..count..,'\n(',round(..count../sum(..count..)*100,2),'%)')))";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his' & ($data[1][$i][25] == 'true' | $data[1][$i][26] == 'true') &  $data[1][$i][24] == 'count'){
      $cop .= " +<br>";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his' & ($data[1][$i][25] == 'true' | $data[1][$i][26] == 'true') & $data[1][$i][24] == 'percentage'){
      $cop .= ",<br>position=position_stack(vjust = 0.5/sum(table(data_graphic$" . $data[1][$i][29] . "))*100)) +<br>";
      $cop .= "ylim(c(0,max(table(data_graphic$EPDS_i01_T0))/sum(table(data_graphic$" . $data[1][$i][29] . "))*100)) +<br>";
    }
    if($data[1][$i][4] == 'histogram' & (($data[1][$i][23] == 'his' & $data[1][$i][24] == 'density')| $data[1][$i][23] == 'both') & ($data[1][$i][25] == 'true' | $data[1][$i][26] == 'true')){
      $cop .= ",<br>position=position_stack(vjust = 0.5/sum(table(data_graphic$" . $data[1][$i][29] . ")))) +<br>";
      $cop .= "ylim(c(0,max(density(data_graphic$" . $data[1][$i][29] . ")$" . "y))) +<br>";
    }
    if($data[1][$i][4] == 'pie'){
      $code .= "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][8] . ", exclude = NULL)), aes(x = '', y = Freq, fill = " . $data[1][$i][$j] . ")) +<br>";
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "geom_col(color = 'black') +<br>";
      $code .= "geom_text(aes(label = " . $cop . "),<br>";
      $code .= "position = position_stack(vjust = 0.5)) +<br>";
      $code .= "coord_polar(theta = 'y') +<br>";
      $code .= "theme_void() +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5))<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'count' & $data[1][$i][10] == 'false' & $data[1][$i][35] == 'no'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ")), aes(x = Var1, y = Freq)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'percentage' & $data[1][$i][10] == 'false' &  $data[1][$i][35] == 'no'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ")), aes(x = Var1, y = Freq/sum(Freq)*100)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2' & $data[1][$i][10] == 'false' &  $data[1][$i][35] == 'no'){
      $code .= "data_agr <- aggregate(as.numeric(". $data[1][$i][15] . ") ~ " . $data[1][$i][14] . ", data=data_graphic, mean, na.rm=TRUE)<br>";
      $code .= "names(data_agr) <- c('" . $data[1][$i][14] . "', '" . $data[1][$i][15] . "')<br>";
      $ggplot = "ggplot(data_agr, aes(x = factor(" . $data[1][$i][14] . "), y = " . $data[1][$i][15] . ")) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'count' & $data[1][$i][10] == 'true' & $data[1][$i][35] == 'no'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", exclude = NULL)), aes(x = Var1, y = Freq)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'percentage' & $data[1][$i][10] == 'true' &  $data[1][$i][35] == 'no'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", exclude = NULL)), aes(x = Var1, y = Freq/sum(Freq)*100)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2' & $data[1][$i][10] == 'true' & $data[1][$i][35] == 'no'){
      $code .= "data_agr <- aggregate(as.numeric(". $data[1][$i][15] . ") ~ " . $data[1][$i][14] . ", data=data_graphic, mean, na.rm=TRUE)<br>";
      $code .= "names(data_agr) <- c('" . $data[1][$i][14] . "', '" . $data[1][$i][15] . "')<br>";
      $ggplot = "ggplot(data_agr, aes(x = factor(" . $data[1][$i][14] . "), y = " . $data[1][$i][15] . ")) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'count' & $data[1][$i][10] == 'false' &  $data[1][$i][35] == 'yes'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", data_graphic$". $data[1][$i][36] . ")), aes(x = Var1, y = Freq, fill = Var2)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'percentage' & $data[1][$i][10] == 'false' &  $data[1][$i][35] == 'yes'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", data_graphic$". $data[1][$i][36] . ")), aes(x = Var1, y = Freq/sum(Freq)*100, fill = Var2)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2' & $data[1][$i][10] == 'false' & $data[1][$i][35] == 'yes'){
      $code .= "data_agr <- aggregate(as.numeric(". $data[1][$i][15] . ") ~ " . $data[1][$i][14] . " + " . $data[1][$i][36] . "_LABEL, data=data_graphic, mean, na.rm=TRUE)<br>";
      $code .= "names(data_agr) <- c('" . $data[1][$i][14] . "', '" . $data[1][$i][41] . "', '" . $data[1][$i][15] . "')<br>";
      $ggplot = "ggplot(data_agr, aes(x = factor(" . $data[1][$i][14] . "), y = " . $data[1][$i][15] . ", fill = " . $data[1][$i][36] . ")) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'count' & $data[1][$i][10] == 'true' &  $data[1][$i][35] == 'yes'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", data_graphic$". $data[1][$i][36] . ", exclude = NULL)), aes(x = Var1, y = Freq, fill = Var2)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'percentage' & $data[1][$i][10] == 'true' &  $data[1][$i][35] == 'yes'){
      $ggplot = "ggplot(as.data.frame(table(data_graphic$" . $data[1][$i][13] . ", data_graphic$". $data[1][$i][36] . ", exclude = NULL)), aes(x = Var1, y = Freq/sum(Freq)*100, fill = Var2)) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2' & $data[1][$i][10] == 'true' &  $data[1][$i][35] == 'yes'){
      $code .= "data_agr <- aggregate(as.numeric(". $data[1][$i][15] . ") ~ " . $data[1][$i][14] . " + " . $data[1][$i][36] . "_LABEL, data=data_graphic, mean, na.rm=TRUE)<br>";
      $code .= "names(data_agr) <- c('" . $data[1][$i][14] . "', '" . $data[1][$i][41] . "', '" . $data[1][$i][15] . "')<br>";
      $ggplot = "ggplot(data_agr, aes(x = factor(" . $data[1][$i][14] . "), y = " . $data[1][$i][15] . ", fill = " . $data[1][$i][36] . ")) +<br>";
    }
    if($data[1][$i][4] == 'barplot' & ($data[1][$i][9] == 'count' | $data[1][$i][9] == 'percentage')){
      $code .= "if(all(check.numeric(data_graphic$" . $data[1][$i][$j] . "_LABEL) == TRUE)){<br>";
      $code .= $ggplot;
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = " . $data[1][$i][$j] . ") +<br>";
      $code .= "xlab('" . $data[1][$i][$j] . "') +<br>";
      $code .= "ylab('" . $data[1][$i][9] . "') +<br>";
      $code .= "geom_text(aes(label = " . $cop . "),<br>";
      $code .= "position = position_stack(vjust = 0.5))<br>";
      $code .= "}else{<br>";
      $code .= $ggplot;
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = " . $data[1][$i][$j] . ") +<br>";
      $code .= "xlab('" . $data[1][$i][$j] . "') +<br>";
      $code .= "ylab('" . $data[1][$i][9] . "') +<br>";
      $code .= "geom_text(aes(label = " . $cop . "),<br>";
      $code .= "position = position_stack(vjust = 0.5)) +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle = 90, vjust = 0.5, hjust=1))<br>";
      $code .= "}<br>";
    }
    if($data[1][$i][4] == 'barplot' & $data[1][$i][9] == 'variable2'){
      $code .= "if(all(check.numeric(data_graphic$" . $data[1][$i][$j] . "_LABEL) == TRUE)){<br>";
      $code .= $ggplot;
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = " . $data[1][$i][$j] . ") +<br>";
      $code .= "xlab('" . $data[1][$i][$j] . "') +<br>";
      $code .= "ylab('" . $data[1][$i][15] . "') +<br>";
      $code .= "geom_text(aes(label = round(" . $data[1][$i][15] . ", 2)),<br>";
      $code .= "position = position_stack(vjust = 0.5))<br>";
      $code .= "}else{<br>";
      $code .= $ggplot;
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = " . $data[1][$i][$j] . ") +<br>";
      $code .= "xlab('" . $data[1][$i][$j] . "') +<br>";
      $code .= "ylab('" . $data[1][$i][15] . "') +<br>";
      $code .= "geom_text(aes(label = round(" . $data[1][$i][15] . ", 2)),<br>";
      $code .= "position = position_stack(vjust = 0.5)) +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle = 90, vjust = 0.5, hjust=1))<br>";
      $code .= "}<br>";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his' & $data[1][$i][24] == 'count'){
      $cpd = "..count..";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his' & $data[1][$i][24] == 'percentage'){
      $cpd = "..count../sum(..count..)*100";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his' & $data[1][$i][24] == 'density'){
      $cpd = "..density..";
    }
    if($data[1][$i][4] == 'histogram'){
      $code .= "data_graphic$" . $data[1][$i][29] . " <- as.numeric(data_graphic$" . $data[1][$i][29] . ")<br>";
      $code .= "ggplot(data_graphic, aes(x = " . $data[1][$i][29] . ")) +<br>";
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5)) +<br>";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'his'){
      $code .= "geom_histogram(binwidth=1, aes(y=" . $cpd . "), colour='black', fill='white') +<br>";
      $code .= "ylab('". $data[1][$i][24] ."') +<br>";
      $code .= $cop;
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'den'){
      $code .= "geom_density(alpha=.2, aes(y=..density..), fill='gray') +<br>";
      $code .= $cop;
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][23] == 'both'){
      $code .= "geom_histogram(binwidth=1, aes(y=..density..), colour='black', fill='white') +<br>";
      $code .= "geom_density(alpha=.2, fill='gray') +<br>";
      $code .= $cop;
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][27] == 'true'){
      $code .= "geom_vline(xintercept = mean(data_graphic$" . $data[1][$i][29] . "),col = 'red',lwd = 1) +<br>";
      $code .= "annotate('text',x = mean(data_graphic$" . $data[1][$i][29] . "),y = 0,label = paste('Mean =', round(mean(data_graphic$" . $data[1][$i][29] . "),2)),col = 'red',size = 4) +<br>";
    }
    if($data[1][$i][4] == 'histogram' & $data[1][$i][28] == 'true'){
      $code .= "geom_vline(xintercept = quantile(data_graphic$" . $data[1][$i][29] . ", probs = .025),col = 'black',lwd = 1) +<br>";
      $code .= "geom_vline(xintercept = quantile(data_graphic$" . $data[1][$i][29] . ", probs = .975),col = 'black',lwd = 1) +<br>";
    }
    if($data[1][$i][4] == 'histogram'){
      $code = substr($code, 0, -6);
      $code .= "<br>";
    }
    if($data[1][$i][4] == 'boxplot'){
      $code .= "data_graphic$" . $data[1][$i][22] . " <- as.numeric(data_graphic$" . $data[1][$i][22] . ")<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][18] == 'false'){
      $code .= "data_graphic <- subset(data_graphic, !is.na(data_graphic$" . $data[1][$i][21] . "))<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][18] == 'false' & $data[1][$i][35] == 'yes'){
      $code .= "data_graphic <- subset(data_graphic, !is.na(data_graphic$" . $data[1][$i][36] . "))<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][35] == 'no'){
      $code .= "ggplot(data_graphic, aes(x = " . $data[1][$i][21] . ", y = " . $data[1][$i][22] . ")) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][35] == 'yes'){
      $code .= "ggplot(data_graphic, aes(x = " . $data[1][$i][21] . ", y = " . $data[1][$i][22] . ", fill = " . $data[1][$i][36] . "_LABEL)) +<br>";
      $code .= "guides(fill=guide_legend(title='" . $data[1][$i][36] . "')) +<br>";
    }
    if($data[1][$i][4] == 'boxplot'){
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "scale_x_discrete(labels = na.omit(unique(data_graphic[order(" . $data[1][$i][21] . "),]$" . $data[1][$i][21] . "_LABEL))) +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5)) +<br>";
      $code .= "xlab('" . $data[1][$i][21] . "') +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'false' & $data[1][$i][19] == 'false' & $data[1][$i][20] == 'false'){
      $code .= "geom_boxplot(outlier.shape = NA, coef = 0, fatten = NULL) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'false' & $data[1][$i][19] == 'false' & $data[1][$i][20] == 'true'){
      $code .= "geom_boxplot(coef = 0, fatten = NULL) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'false' & $data[1][$i][19] == 'true' & $data[1][$i][20] == 'false'){
      $code .= "geom_boxplot(outlier.shape = NA, fatten = NULL) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'false' & $data[1][$i][19] == 'true' & $data[1][$i][20] == 'true'){
      $code .= "geom_boxplot(fatten = NULL) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'true' & $data[1][$i][19] == 'false' & $data[1][$i][20] == 'false'){
      $code .= "geom_boxplot(outlier.shape = NA, coef = 0) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'true' & $data[1][$i][19] == 'false' & $data[1][$i][20] == 'true'){
      $code .= "geom_boxplot(coef = 0) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'true' & $data[1][$i][19] == 'true' & $data[1][$i][20] == 'false'){
      $code .= "geom_boxplot(outlier.shape = NA) +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][17] == 'true' & $data[1][$i][19] == 'true' & $data[1][$i][20] == 'true'){
      $code .= "geom_boxplot() +<br>";
    }
    if($data[1][$i][4] == 'boxplot' & $data[1][$i][16] == 'true'){
      $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
    }
    if($data[1][$i][4] == 'boxplot'){
      $code = substr($code, 0, -6);
      $code .= "<br>";
    }
    if($data[1][$i][4] == 'scatter'){
      $code .= "data_graphic <- subset(data_graphic, !is.na(data_graphic$" . $data[1][$i][33] . "))<br>";
      $code .= "data_graphic <- subset(data_graphic, !is.na(data_graphic$" . $data[1][$i][34] . "))<br>";
      $code .= "data_graphic$" . $data[1][$i][33] . " <- as.numeric(data_graphic$" . $data[1][$i][33] . ")<br>";
      $code .= "data_graphic$" . $data[1][$i][34] . " <- as.numeric(data_graphic$" . $data[1][$i][34] . ")<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][35] == 'no'){
      $code .= "ggplot(data_graphic, aes(x = " . $data[1][$i][33] . ", y = " . $data[1][$i][34] . ")) +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][35] == 'yes'){
      $code .= "ggplot(data_graphic, aes(x = " . $data[1][$i][33] . ", y = " . $data[1][$i][34] . ", color = " . $data[1][$i][36] . "_LABEL)) +<br>";
    }
    if($data[1][$i][4] == 'scatter'){
      $code .= "ggtitle('" . $data[1][$i][3] . "') +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5)) +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][30] == 'true' & $data[1][$i][35] == 'no'){
      $code .= "geom_count() +<br>";
      $code .= "scale_size_area() +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][30] == 'true' & $data[1][$i][35] == 'yes'){
      $code .= "geom_count(aes(colour=factor(" . $data[1][$i][36] . "_LABEL))) +<br>";
      $code .= "scale_size_area() +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][31] == 'true'){
      $code .= "stat_summary(fun=mean, geom='line', size=1) +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][32] == 'true'){
      $code .= "geom_smooth(method=lm, se=TRUE) +<br>";
    }
    if($data[1][$i][4] == 'scatter' & $data[1][$i][35] == 'yes'){
      $code .= "guides(color=guide_legend(title='" . $data[1][$i][36] . "')) +<br>";
    }
    if($data[1][$i][4] == 'scatter'){
      $code = substr($code, 0, -6);
      $code .= "<br>";
    }
  }
}


if(sizeof($data3)!=1){
  for ($i = 0; $i < (sizeof($data[2])); $i++) {
    $code .= "data_hyp <- data" . $data[2][$i][3] . "<br>";
    if($data[2][$i][17]!=''){
      $data[2][$i][17] = preg_split ("/\;/", $data[2][$i][17]);
      for ($j = 0; $j < (sizeof($data[2][$i][17])-1)/3; $j++) {
        $code .= "data_hyp <- subset(data_hyp, data_hyp" . "$" . $data[2][$i][17][$j*3] . " " .$data[2][$i][17][$j*3+1] . " '" . $data[2][$i][17][$j*3+2] . "')<br>";
      }
      $data[2][$i][17] = implode(", ",$data[2][$i][17]);
      $data[2][$i][17] = str_replace(", !=, ","!=",$data[2][$i][17]);
      $data[2][$i][17] = str_replace(", ==, ","==",$data[2][$i][17]);
      $data[2][$i][17] = str_replace(", <, ","<",$data[2][$i][17]);
      $data[2][$i][17] = str_replace(", >, ",">",$data[2][$i][17]);
      $data[2][$i][17] = substr($data[2][$i][17], 0, -2);
    }
    if($data[2][$i][4] == 'dif' & $data[2][$i][5] == 'inner'){
      $data[2][$i][6] = preg_split ("/\;/", $data[2][$i][6]);
      $code .= "data_hyp <- gather(data_hyp, measurement, condition, c(";
      for ($j = 0; $j < sizeof($data[2][$i][6])-1; $j++) { 
        $code .= $data[2][$i][6][$j] . ", ";
      }
      $code = substr($code, 0, -2);
      $code .= "))<br>";
    }
    if($data[2][$i][4] == 'dif' & $data[2][$i][7] == 'nom'){
      $code .= "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][8] . "))<br>";
      $code .= "model <- chisq.test(table(data_hyp$" . $data[2][$i][8] . ", data_hyp$" . $data[2][$i][6] . "))<br>";
      $code .= "n <- length(na.omit(data_hyp$" . $data[2][$i][6] . "))<br>";
      $code .= "p_value <- model$" . "p.value<br>";
      $effect = "r";
      $code .= "effect <- sqrt(model$" . "statistic[[1]]/(n*model$" . "parameter[[1]]))<br>";
      $code .= "if(all(check.numeric(data_hyp$" . $data[2][$i][8] . ") == TRUE)){<br>";
      $code .= "data_hyp$" . $data[2][$i][8] . " <- as.numeric(data_hyp$" . $data[2][$i][8] . ")<br>";
      $code .= "groups <- unique(data_hyp[order(as.numeric(data_hyp$" . $data[2][$i][8] . ")),]$" . $data[2][$i][8] . "_LABEL)<br>";
      $code .= "}else{<br>";
      $code .= "groups <- unique(data_hyp[order(data_hyp$" . $data[2][$i][8] . "),]$" . $data[2][$i][8] . "_LABEL)<br>";
      $code .= "}<br>";
      $code .= "if(all(check.numeric(data_hyp$" . $data[2][$i][8] . "_LABEL) == TRUE)){<br>";
      $code .= "ggplot(as.data.frame(table(data_hyp$" . $data[2][$i][8] . ", data_hyp$" . $data[2][$i][6] . ")), aes(x = Var1, y = Freq, fill = Var2)) +<br>";
      $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
      $code .= "labs(subtitle = paste0(model$" . "method, ', subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(p_value,3), ', p-adjusted = ', round(p.adjust(p_value,'bonferroni'," . sizeof($data[2]) . "),3), ', ', names(model$" . "statistic), ' = ', round(model$" . "statistic[[1]],3), ', df = ', model$" . "parameter[[1]],  ', " . $effect . " = ', round(effect,3))) +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = groups) +<br>";
      $code .= "xlab('" . $data[2][$i][8] . "') +<br>";
      $code .= "ylab('count') +<br>";
      $code .= "geom_text(aes(label = Freq),position = position_stack(vjust = 0.5))<br>";
      $code .= "}else{<br>";
      $code .= "ggplot(as.data.frame(table(data_hyp$" . $data[2][$i][8] . ", data_hyp$" . $data[2][$i][6] . ")), aes(x = Var1, y = Freq, fill = Var2)) +<br>";
      $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
      $code .= "labs(subtitle = paste0(model$" . "method, ', subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(p_value,3), ', p-adjusted = ', round(p.adjust(p_value,'bonferroni'," . sizeof($data[2]) . "),3),  ', ', names(model$" . "statistic), ' = ', round(model$" . "statistic[[1]],3), ', df = ', model$" . "parameter[[1]], ', " . $effect . " = ', round(effect,3))) +<br>";
      $code .= "geom_bar(position='stack', stat='identity') +<br>";
      $code .= "scale_x_discrete(labels = groups) +<br>";
      $code .= "xlab('" . $data[2][$i][8] . "') +<br>";
      $code .= "ylab('count') +<br>";
      $code .= "geom_text(aes(label = Freq),position = position_stack(vjust = 0.5)) +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle = 90, vjust = 0.5, hjust=1))<br>";
      $code .= "}<br>";
    }
    if($data[2][$i][4] == 'dif' & $data[2][$i][7] == 'ord'){
      if($data[2][$i][9] == 'between'){
        $paired = "";
        $idvar ="";
        $test = "kruskal";
        $effect = "effect <- 'eta2'<br>";
        $effect .= "effect_size <- model$" . "statistic[[1]]/n<br>";
      }
      if($data[2][$i][9] == 'inner'){
        $paired = ", paired=TRUE";
        $idvar =", data_hyp$" . $data[2][$i][5];
        $test = "friedman";
        $effect = "effect <- 'r'<br>";
        $effect .= "effect_size <- abs(qnorm(p_value)/sqrt(n))<br>";
      }
      $code .= "data_hyp$" . $data[2][$i][6] . " <- as.numeric(data_hyp$" . $data[2][$i][6] . ")<br>";
      $code .= "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][8] . "))<br>";
      $code .= "data_hyp <- subset(data_hyp, !data_hyp$" . $data[2][$i][5] . "%in%subset(data_hyp, is.na(data_hyp$" . $data[2][$i][6] . "))$" . $data[2][$i][5] . ")<br>";
      $code .= "if(length(na.omit(unique(data_hyp$" . $data[2][$i][8] . ")))==2){<br>";
      $code .= "model <- wilcox.test(" . $data[2][$i][6] . " ~ " . $data[2][$i][8] . ", data=data_hyp" . $paired . ")<br>";
      $code .= "n <- length(na.omit(data_hyp$" . $data[2][$i][8] . "))<br>";
      $code .= "p_value <- model$" . "p.value<br>";
      $code .= "effect <- 'r'<br>";
      $code .= "effect_size <- abs(qnorm(p_value)/sqrt(n))<br>";
      $code .= "}<br>";
      $code .= "if(length(na.omit(unique(data_hyp$" . $data[2][$i][8] . ")))>2){<br>";
      $code .= "model <- " . $test . ".test(data_hyp$" . $data[2][$i][6] . ", data_hyp$" . $data[2][$i][8] . $idvar . ")<br>";
      $code .= "n <- length(na.omit(data_hyp$" . $data[2][$i][8] . "))<br>";
      $code .= "p_value <- model$" . "p.value<br>";
      $code .= $effect;
      $code .= "}<br>";
      $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][8] . "_LABEL, y = " . $data[2][$i][6] . ")) +<br>";
      $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
      $code .= "labs(subtitle = paste0(model$" . "method, ', subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(p_value,3), ', p-adjusted = ', round(p.adjust(p_value,'bonferroni'," . sizeof($data[2]) . "),3), ', ', names(model$" . "statistic), ' = ', round(model$" . "statistic[[1]],3), ', df = ', model$" . "parameter[[1]], ', ', effect, ' = ', round(effect_size,3))) +<br>";
      $code .= "xlab('" . $data[2][$i][8] . "') +<br>";
      $code .= "geom_boxplot() +<br>";
      $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3)<br>";
    }
    if($data[2][$i][4] == 'dif' & $data[2][$i][7] == 'int'){
      $code .= "data_hyp$" . $data[2][$i][6] . " <- as.numeric(data_hyp$" . $data[2][$i][6] . ")<br>";
      $data[2][$i][10] = preg_split ("/\;/", $data[2][$i][10]);
      if(sizeof($data[2][$i][10])==3){
        if($data[2][$i][10][1] == 'between'){
          $paired = "";
          $idvar ="";
          $effect = "effect <- 'cohen's d'<br>";
          $effect .= "effect_size <- abs(cohens_d(" . $data[2][$i][6] . " ~ " . $data[2][$i][10][0] . ", data=data_hyp)$" . "effsize)<br>";
          $p_value = "p_value <- model[[1]][[5]][1]<br>";
          $F_value = "F_t_value <- model[[1]][[4]][1]<br>";
          $df = "df <- model[[1]][[1]][1]<br>";
          $method = "anova";
          $subset = "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][10][0] . ")<br>";
        }
        if($data[2][$i][10][1] == 'inner'){
          $paired = ", paired=TRUE";
          $idvar =" + Error(" . $data[2][$i][5] . "/" . $data[2][$i][10][0] . ")";
          $effect = "effect <- 'r'<br>";
          $effect .= "effect_size <- abs(qnorm(p_value)/sqrt(n))<br>";
          $p_value = "p_value <- model[[3]][[1]][[5]][1]<br>";
          $F_value = "F_t_value <- model[[3]][[1]][[4]][1]<br>";
          $df = "df <- model[[3]][[1]][[1]][1]<br>";
          $method = "anova with repeated measurement";
          $code .= "data_hyp$" . $data[2][$i][5] . " <- as.numeric(data_hyp$" . $data[2][$i][5] . ")<br>";
        }
        $code .= "data_hyp <- subset(data_hyp, !data_hyp$" . $data[2][$i][5] . "%in%subset(data_hyp, is.na(data_hyp$" . $data[2][$i][6] . "))$" . $data[2][$i][5] . ")<br>";
        $code .= "if(length(na.omit(unique(data_hyp$" . $data[2][$i][10][0] . ")))==2){<br>";
        $code .= "model <- t.test(" . $data[2][$i][6] . " ~ " . $data[2][$i][10][0] . ", data=data_hyp" . $paired . ")<br>";
        $code .= "n <- length(na.omit(data_hyp$" . $data[2][$i][10][0] . "))<br>";
        $code .= "p_value <- model$" . "p.value<br>";
        $code .= "F_t_value <- model$" . "statistic<br>";
        $code .= "F_t <- 't-value'<br>";
        $code .= "df <- model$" . "parameter<br>";
        $code .= $effect;
        $code .= "}<br>";
        $code .= "if(length(na.omit(unique(data_hyp$" . $data[2][$i][10][0] . ")))>2){<br>";
        $code .= "model <- summary(aov(" . $data[2][$i][6] . " ~ " . $data[2][$i][10][0] . $idvar . ", data=data_hyp))<br>";
        $code .= "model$" . "method <- '" . $method . "'<br>";
        $code .= "n <- length(na.omit(data_hyp$" . $data[2][$i][10][0] . "))<br>";
        $code .= $p_value;
        $code .= $F_value;
        $code .= "F_t <- 'F-value'<br>";
        $code .= $df;
        $code .= "effect <- 'r'<br>";
        $code .= "effect_size <- abs(qnorm(p_value)/sqrt(n))<br>";
        $code .= "}<br>";
        $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][10][0] . "_LABEL, y = " . $data[2][$i][6] . ")) +<br>";
        $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
        $code .= "labs(subtitle = paste0(model$" . "method, ', subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(p_value,3), ', p-adjusted = ', round(p.adjust(p_value,'bonferroni'," . sizeof($data[2]) . "), ', ', F_t,' = ', round(F_t_value,3), ', df = ', df, ', ', effect, ' = ', round(effect_size,3))) +<br>";
        $code .= "xlab('" . $data[2][$i][10][0] . "') +<br>";
        $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
        $code .= "stat_summary(fun.data = mean_se, geom = 'errorbar')<br>";
      }
      if(sizeof($data[2][$i][10])>3){
        $code .= "data_hyp$" . $data[2][$i][5] . " <- as.numeric(data_hyp$" . $data[2][$i][5] . ")<br>";
        $model = "model <- summary(aov(" . $data[2][$i][6] . " ~ ";
        $error = "Error(" . $data[2][$i][5] . "/";
        for ($j = 0; $j < (sizeof($data[2][$i][10])-1)/2; $j++) {
          $code .= "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][10][$j*2] . "))<br>";
          $model .= $data[2][$i][10][$j*2] . "*";
          if($data[2][$i][10][$j*2+1] == 'inner'){
            $error .= $data[2][$i][10][$j*2] . "*";
          }
        }
        $model = substr($model, 0, -1);
        $error = substr($error, 0, -1);
        $error .= ")";
        $model .= " + " . $error . ", data=data_hyp))<br>";
        $code .= $model;
        $code .= "n <- nrow(data_hyp)<br>";

        $x = (sizeof($data[2][$i][10])-1)/2+1;
        $c = "";
        $wert1 = 0;
        function generate_for_loop($j,$n,$x,$v) {
          for (${'v' . $j} = ${'v'}+1; ${'v' . $j} < $x-$n+$j; ${'v' . $j}++) {
            yield ${'v' . $j};
          }
        }
        for ($j = 1; $j < $x; $j++) {
          for ($k = 1; $k < $j+1; $k++) {
            $c .= "foreach (generate_for_loop(" . $k . "," . $j . ",$" . "x,$" . "{'wert' . " . $k . "}) as $" . "{'wert' . " . $k . "+1}) {";
          }
          $c .= "for ($" . "l = 1; $" . "l < " . $j . "+1; $" . "l++) {" . "$" . "code .=  $" . "{'wert' . $" . "l+1};}" . "$" . "code .= '<br>';";
          for ($k = 1; $k < $j+1; $k++) {
            $c .= "}";
          }
        }
        $count = 0;

          $df = "df <- model[[3]][[1]][[1]][1]<br>";
        foreach (generate_for_loop(1,1,$x,${'wert' . 1}) as ${'wert' . 1+1}) {
          $count += 1;
          $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][10][($wert2-1)*2] . "_LABEL, y = " . $data[2][$i][6] . ")) +<br>";
          $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
          $code .= "labs(subtitle = paste0('mixed anova, subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(model[[3]][[1]][[5]][[$count]],3), ', p-adjusted = ', round(p.adjust(model[[3]][[1]][[5]][[$count]],'bonferroni'," . sizeof($data[2]) . "),3), ', F-value = ', round(model[[3]][[1]][[4]][$count]), ', df = ', round(model[[3]][[1]][[1]][$count]), ', r = ', round(abs(qnorm(model[[3]][[1]][[5]][[$count]])/sqrt(n)),3))) +<br>";
          $code .= "xlab('" . $data[2][$i][10][($wert2-1)*2] . "') +<br>";
          $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
          $code .= "stat_summary(fun.data = mean_se, geom = 'errorbar')<br>";
        }
        if($x>2){
        foreach (generate_for_loop(1,2,$x,${'wert' . 1}) as ${'wert' . 1+1}) {
          foreach (generate_for_loop(2,2,$x,${'wert' . 2}) as ${'wert' . 2+1}) {
            $count += 1;
            $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][10][($wert2-1)*2] . "_LABEL, y = " . $data[2][$i][6] . ", color = " . $data[2][$i][10][($wert3-1)*2] . "_LABEL)) +<br>";
            $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
            $code .= "guides(color=guide_legend(title='" . $data[2][$i][10][($wert3-1)*2] . "')) +<br>";
            $code .= "labs(subtitle = paste0('mixed anova, subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(model[[3]][[1]][[5]][[$count]],3), ', p-adjusted = ', round(p.adjust(model[[3]][[1]][[5]][[$count]],'bonferroni'," . sizeof($data[2]) . "),3), ', F-value = ', round(model[[3]][[1]][[4]][$count]), ', df = ', round(model[[3]][[1]][[1]][$count]), ', r = ', round(abs(qnorm(model[[3]][[1]][[5]][[$count]])/sqrt(n)),3))) +<br>";
            $code .= "xlab('" . $data[2][$i][10][($wert2-1)*2] . "') +<br>";
            $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
            $code .= "stat_summary(fun.data = mean_se, geom = 'errorbar')<br>";
          }
        }
        }
        if($x>3){
        foreach (generate_for_loop(1,3,$x,${'wert' . 1}) as ${'wert' . 1+1}) {
          foreach (generate_for_loop(2,3,$x,${'wert' . 2}) as ${'wert' . 2+1}) {
            foreach (generate_for_loop(3,3,$x,${'wert' . 3}) as ${'wert' . 3+1}) {
              $count += 1;
              $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][10][($wert2-1)*2] . "_LABEL, y = " . $data[2][$i][6] . ", color = " . $data[2][$i][10][($wert3-1)*2] . "_LABEL)) +<br>";
              $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
              $code .= "guides(color=guide_legend(title='" . $data[2][$i][10][($wert3-1)*2] . "')) +<br>";
              $code .= "facet_nested(cols = vars('" . $data[2][$i][10][($wert4-1)*2] . "', " . $data[2][$i][10][($wert4-1)*2] . "_LABEL)) +<br>";
              $code .= "labs(subtitle = paste0('mixed anova, subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(model[[3]][[1]][[5]][[$count]],3), ', p-adjusted = ', round(p.adjust(model[[3]][[1]][[5]][[$count]],'bonferroni'," . sizeof($data[2]) . "),3), ', F-value = ', round(model[[3]][[1]][[4]][$count]), ', df = ', round(model[[3]][[1]][[1]][$count]), ', r = ', round(abs(qnorm(model[[3]][[1]][[5]][[$count]])/sqrt(n)),3))) +<br>";
              $code .= "xlab('" . $data[2][$i][10][($wert2-1)*2] . "') +<br>";
              $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
              $code .= "stat_summary(fun.data = mean_se, geom = 'errorbar')<br>";
            }
          }
        }
        }
        if($x>4){
        foreach (generate_for_loop(1,4,$x,${'wert' . 1}) as ${'wert' . 1+1}) {
          foreach (generate_for_loop(2,4,$x,${'wert' . 2}) as ${'wert' . 2+1}) {
            foreach (generate_for_loop(3,4,$x,${'wert' . 3}) as ${'wert' . 3+1}) {
              foreach (generate_for_loop(4,4,$x,${'wert' . 4}) as ${'wert' . 4+1}) {
                $count += 1;
                $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][10][($wert2-1)*2] . "_LABEL, y = " . $data[2][$i][6] . ", color = " . $data[2][$i][10][($wert3-1)*2] . "_LABEL)) +<br>";
                $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
                $code .= "guides(color=guide_legend(title='" . $data[2][$i][10][($wert3-1)*2] . "')) +<br>";
                $code .= "facet_nested(cols = vars('" . $data[2][$i][10][($wert4-1)*2] . "', " . $data[2][$i][10][($wert4-1)*2] . "_LABEL), rows = vars('" . $data[2][$i][10][($wert5-1)*2] . "', " . $data[2][$i][10][($wert5-1)*2] . "_LABEL)) +<br>";
                $code .= "labs(subtitle = paste0('mixed anova, subset: "  . $data[2][$i][17] . "'), caption = paste0('n = ', n, ', p-value = ', round(model[[3]][[1]][[5]][[$count]],3), ', p-adjusted = ', round(p.adjust(model[[3]][[1]][[5]][[$count]],'bonferroni'," . sizeof($data[2]) . "),3), ', F-value = ', round(model[[3]][[1]][[4]][$count]), ', df = ', round(model[[3]][[1]][[1]][$count]), ', r = ', round(abs(qnorm(model[[3]][[1]][[5]][[$count]])/sqrt(n)),3))) +<br>";
                $code .= "xlab('" . $data[2][$i][10][($wert2-1)*2] . "') +<br>";
                $code .= "stat_summary(fun = mean, geom = 'point', shape = 18, size = 3) +<br>";
                $code .= "stat_summary(fun.data = mean_se, geom = 'errorbar')<br>";
              }
            }
          }
        }
       } 
      }
    }
    if($data[2][$i][4] == 'cor'){
      $code .= "data_hyp$" . $data[2][$i][12] . " <- as.numeric(data_hyp$" . $data[2][$i][12] . ")<br>";
      $code .= "data_hyp$" . $data[2][$i][14] . " <- as.numeric(data_hyp$" . $data[2][$i][14] . ")<br>";
      if($data[2][$i][13] == 'ord' | $data[2][$i][15] == 'ord'){
        $code .= "cor <- cor.test(data_hyp$" . $data[2][$i][12] . ", data_hyp$" . $data[2][$i][14] . ", method = 'spearman', exact = FALSE)<br>";
      }
      if($data[2][$i][13] == 'int' & $data[2][$i][15] == 'int'){
        $code .= "if(shapiro.test(data_hyp$" . $data[2][$i][12] . " < 0.05) | shapiro.test(data_hyp$" . $data[2][$i][14] . " < 0.05)){<br>";
        $code .= "cor <- cor.test(data_hyp$" . $data[2][$i][12] . ", data_hyp$" . $data[2][$i][14] . ", method = 'spearman', exact = FALSE)<br>";
        $code .= "}else{<br>";
        $code .= "cor <- cor.test(data_hyp$" . $data[2][$i][12] . ", data_hyp$" . $data[2][$i][14] . ", method = 'pearson')<br>";
        $code .= "}<br>";
      }
      $code .= "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][12] . "))<br>";
      $code .= "data_hyp <- subset(data_hyp, !is.na(data_hyp$" . $data[2][$i][14] . "))<br>";
      $code .= "ggplot(data_hyp, aes(x = " . $data[2][$i][12] . ", y = " . $data[2][$i][14] . ")) +<br>";
      $code .= "ggtitle('" . $data[2][$i][2] . "') +<br>";
      $code .= "labs(subtitle = paste0(model$" . "method, ', subset: "  . $data[2][$i][14] . "'), caption = paste0('n = ', length(data_hyp$" . $data[2][$i][12] . "), ', p-value = ', round(cor$" . "p.value,3), ', p-adjusted = ', round(p.adjust(cor$" . "p.value,'bonferroni'," . sizeof($data[2]) . "),3), ', ', names(model$" . "estimate), ' = ', round(model$" . "estimate[[1]],3))) +<br>";
      $code .= "theme(plot.title = element_text(hjust = 0.5)) +<br>";
      $code .= "stat_summary(fun=mean, geom='line', size=1) +<br>";
      $code .= "geom_smooth(method=lm, se=TRUE)<br>";
    }
  }
}

if(sizeof($data4)!=1){
  for ($i = 0; $i < (sizeof($data[3])); $i++) {
    $code .= "data_basis <- data" . $data[3][$i][2] . "<br>";
    $data[3][$i][3] = preg_split ("/\;/", $data[3][$i][3]);
    $code .= "data_basis <- data" . $data[3][$i][2] . "[,c(";
    $classlist = "c(";
    $nom = 0;
    $ord = 0;
    $int = 0;
    for ($j = 0; $j < (sizeof($data[3][$i][3])-1)/2; $j++) {
      $code .= "'" . $data[3][$i][3][$j*2] . "', ";
      $classlist .= "'" . $data[3][$i][3][$j*2+1] . "', ";
      if($data[3][$i][3][$j*2+1]=='nom'){
        $nom = 1;
      }
      if($data[3][$i][3][$j*2+1]=='ord'){
        $ord = 1;
      }
      if($data[3][$i][3][$j*2+1]=='int'){
        $int = 1;
      }
    }
    $code = substr($code, 0, -2);
    $code .= ")]<br>";
    $classlist = substr($classlist, 0, -2);
    $classlist .= ")<br>";
    $code .= "classlist <- " . $classlist;
    $code .= "for(i in (1:length(classlist))){<br>";
    if($nom == 1){
      $code .= "if(classlist[i] == 'nom'){<br>";
      $code .= "data_basis[[i]] <- as.factor(data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    if($ord == 1){
      $code .= "if(classlist[i] == 'ord'){<br>";
      $code .= "data_basis[[i]] <- as.numeric(data_basis[[i]])<br>";
      $code .= "data_basis[[i]] <- as.ordered(data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    if($int == 1){
      $code .= "if(classlist[i] == 'int'){<br>";
      $code .= "data_basis[[i]] <- as.numeric(data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    $code .= "}<br>";
    if(($nom == 1 | $ord == 1) & $int == 1){
      $code .= "data_scaled <- data.frame(cbind(data_basis[which(classlist!='int')],scale(data_basis[which(classlist=='int')])))<br>";
      $code .= "dist_data <- daisy(data_scaled,metric='gower')<br>";
    }
    if(($nom == 1 | $ord == 1) & $int == 0){
      $code .= "data_scaled <- data_basis<br>";
      $code .= "dist_data <- daisy(data_scaled,metric='gower')<br>";
    }
    if($nom == 0 & $ord == 0){
      $code .= "for(i in (1:length(classlist))){<br>";
      $code .= "data_basis[[i]] <- ifelse(is.na(data_basis[[i]]),mean(data_basis[[i]], na.rm=TRUE),data_basis[[i]])<br>";
      $code .= "}<br>";
      $code .= "data_scaled <- data.frame(cbind(scale(data_basis)))<br>";
      $code .= "dist_data <- daisy(data_scaled,metric='euclidean')<br>";
    }
    if($data[3][$i][4]=='cluster'){
      $code .= "fit <- hclust(dist_data,method = 'ward.D2')<br>";
    }
    if($data[3][$i][4]=='cluster' & $data[3][$i][5]=='nonumberclus'){
      $code .= "height <- sort(fit$" . "height)<br>";
      $code .= "pitch <- c()<br>";
      $code .= "for(i in 1:length(height)-1){<br>";
      $code .= "pitch[i] <- height[i+1]-height[i]<br>";
      $code .= "}<br>";
      $code .= "pitch_difference <- c()<br>";
      $code .= "for(i in 1:length(pitch)-1){<br>";
      $code .= "pitch_difference[i] <- pitch[i+1]-pitch[i]<br>";
      $code .= "}<br>";
      $code .= "k <- length(height)-which(pitch_difference==max(pitch_difference))<br>";
      $code .= "height <- height[(length(height)-(k+5)):length(height)]<br>";
      $code .= "pitch <- pitch[(length(pitch)-(k+4)):length(pitch)]<br>";
      $code .= "pitch_difference <- pitch_difference[(length(pitch_difference)-(k+3)):length(pitch_difference)]<br>";
      $code .= "ggplot(data.frame((k+7):2,height),aes(x=(k+7):2,y=height)) + geom_line() + scale_x_continuous(breaks = (k+7):2)<br>";
      $code .= "ggplot(data.frame((k+6):2,pitch),aes(x=(k+6):2,y=pitch)) + geom_line() + scale_x_continuous(breaks = (k+6):2)<br>";
      $code .= "ggplot(data.frame((k+5):2,pitch_difference),aes(x=(k+5):2,y=pitch_difference)) + geom_line() + scale_x_continuous(breaks = (k+5):2)<br>";
      $code .= "plot(fit,which.plots=2)<br>";
      $code .= "rect.hclust(as.hclust(fit), k=k, border='red')<br>";
      $code .= "data_basis$" . "Cluster <- cutree(fit, k=k)<br>";
    }
    if($data[3][$i][4]=='cluster' & $data[3][$i][5]=='numberclus'){
      $code .= "plot(fit,which.plots=2)<br>";
      $code .= "rect.hclust(as.hclust(fit), k=" . $data[3][$i][6] . ", border='red')<br>";
      $code .= "data_basis$" . "Cluster <- cutree(fit, k=" . $data[3][$i][6] . ")<br>";
    }
    if($nom == 1){
      $code .= "data_basis <- unclass(data_basis)<br>";
    }
    $code .= "for(i in (1:length(classlist))){<br>";
    if($nom == 1){
      $code .= "if(classlist[i] == 'nom'){<br>";
      $code .= "data_basis[[i]] <- ifelse(is.na(data_basis[[i]]),0,data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    if($ord == 1){
      $code .= "if(classlist[i] == 'ord'){<br>";
      $code .= "data_basis[[i]] <- ifelse(is.na(data_basis[[i]]),as.factor(median(as.numeric(data_basis[[i]]), na.rm=TRUE)),data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    if($int == 1 & ($nom == 1 | $ord == 1)){
      $code .= "if(classlist[i] == 'int'){<br>";
      $code .= "data_basis[[i]] <- ifelse(is.na(data_basis[[i]]),mean(data_basis[[i]], na.rm=TRUE),data_basis[[i]])<br>";
      $code .= "}<br>";
    }
    $code .= "}<br>";
    $code .= "data_basis <- as.data.frame(data_basis)<br>";
    $code .= "set.seed(1337)<br>";
    $code .= "train_ind <- sample(seq_len(nrow(data_basis)),size = floor(0.75*nrow(data_basis)))<br>";
    $code .= "train=data_basis[train_ind,]<br>";
    $code .= "test=data_basis[-train_ind,]<br>";
    if($data[3][$i][4]=='cluster'){
      $code .= "data_basis.glm <- glm(as.factor(Cluster) ~ .,data=data_basis, family='binomial')<br>";
      $code .= "data_basis.glm.train <- glm(as.factor(Cluster) ~ .,data=train, family='binomial')<br>";
    }
    if($data[3][$i][4]=='groups'){
      $code .= "data_basis.glm <- glm(as.factor(" . $data[3][$i][7] . ") ~ .,data=data_basis, family='binomial')<br>";
      $code .= "data_basis.glm.train <- glm(as.factor(" . $data[3][$i][7] . ") ~ .,data=train, family='binomial')<br>";
    }
    $code .= "predicted <- predict(data_basis.glm.train,test)<br>";
    $code .= "code <- 'predicted <- ifelse('<br>";
    $code .= "for(i in 1:(k-1)){<br>";
    $code .= "code <- paste0(code,'predicted<',i+0.5,',',i,',ifelse(')<br>";
    $code .= "}<br>";
    $code .= "code <- substr(code,1,nchar(code)-7)<br>";
    $code .= "code <- paste0(code,k)<br>";
    $code .= "for(i in 1:(k-1)){<br>";
    $code .= "code <- paste0(code,')')<br>";
    $code .= "}<br>";
    $code .= "eval(parse(text=code))<br>";
    if($data[3][$i][4]=='cluster'){
      $code .= "sum <- table(test$" . "Cluster)<br>";
      $code .= "percent <- round((sum/sum(sum))*100,2)<br>";
      $code .= "correct <- table(data_basis$" . "Cluster)*0<br>";
      $code .= "correct2 <- table(subset(test,Cluster==predicted)$" . "Cluster)<br>";
    }
    if($data[3][$i][4]=='groups'){
      $code .= "sum <- table(test$" . "" . $data[3][$i][7] . ")<br>";
      $code .= "percent <- round((sum/sum(sum))*100,2)<br>";
      $code .= "correct <- table(data_basis$" . "" . $data[3][$i][7] . ")*0<br>";
      $code .= "correct2 <- table(subset(test," . $data[3][$i][7] . "==predicted)$" . "" . $data[3][$i][7] . ")<br>";
    }  
    $code .= "for(i in 1:length(correct)){<br>";
    $code .= "if(names(correct)[i]%in%names(correct2)){<br>";
    $code .= "correct[i] <- correct2[names(correct[i])]<br>";
    $code .= "}<br>";
    $code .= "}<br>";
    $code .= "correct <- round((correct/sum)*100,2)<br>";
    $code .= "prediction_table <- cbind(sum,percent,correct)<br>";
    if($data[3][$i][4]=='cluster'){
      $code .= "plot(NA, xlim=c(0,5), ylim=c(0,5), bty='n',xaxt='n', yaxt='n', xlab='', ylab='', main=paste0('correct = ',round(mean(predicted==test$" . "Cluster)*100,2),'%'))<br>";
    }
    if($data[3][$i][4]=='groups'){
      $code .= "plot(NA, xlim=c(0,5), ylim=c(0,5), bty='n',xaxt='n', yaxt='n', xlab='', ylab='', main=paste0('correct = ',round(mean(predicted==test$" . "" . $data[3][$i][7] . ")*100,2),'%'))<br>";
    }
    $code .= "grid.table(prediction_table)<br>";
    $code .= "plot(NA, xlim=c(0,5), ylim=c(0,5), bty='n',xaxt='n', yaxt='n', xlab='', ylab='', main='logistic model')<br>";
    $code .= "grid.table(summary(data_basis.glm)$" . "coefficients)<br>";
    $code .= "plot(margins(data_basis.glm))<br>";
  }
}
  $code .= "dev.off()<br>";
}




echo $code;

exit();
?>

