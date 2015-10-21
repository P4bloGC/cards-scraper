<?php
require ('simple_html_dom.php');
require ('conexion.php');

//Usamos el url de tradecardsonline para obtener el nombre de las cartas.
$url	=	'http://www.tradecardsonline.com/im/selectCard/series_id/2956/goal/';
$html	=	file_get_html($url);
$tabla	=	array_slice($html->find('table[class=padded_0 cell_padded_3]'), 0, 1);
$cartas =   array();
$id_count	=	0;
$name_count = 	0;

foreach($tabla as $var){
    //En este ciclo obtenemos el ID de las cartas.
    foreach($var->find('td ') as $td){
        if(stristr($td->plaintext, 'PR-')){
            $cartas[$id_count]['cardID'] = str_replace('-', '/', $td->plaintext);
            $id_count++;	
        }
    }
    //En ese ciclo creamos la url hacia la wikia.
    foreach($var->find('td a') as $td){
        if($td->plaintext != 'search'){
            $cartas[$name_count]['name'] = 'http://cardfight.wikia.com/wiki/'.str_replace(' ', '_', $td->plaintext);
            $name_count++;
        }
    }
}
//A partir de aquí recorremos cada una de la urls generadas y extraemos los datos.
$k  =   0;
$datos = array();
foreach($cartas as $link){
    if($html	=	file_get_html($link['name'])){
        $post   =   $html->find('div[class=info-main] table');
        $datos['cardID']    =   $link['cardID'];
        foreach($post as $post){
            $i  =   0;
            foreach($post->find('td') as $td){
                $atributo[$i]    =   $td->plaintext;   
                if(($i%2) != 0){
                    $datos[$atributo[$i-1]]  =   $td->plaintext;   
                }
                $i++;
            }
        }
        $sets   =   $html->find('table[class=sets]');
        foreach($sets as $sets){
            foreach($sets->find('tr') as $td){
                $atributo[$i]    =   $td->plaintext;   
                if(($i%2) != 0){
                    $datos[$atributo[$i-1]]  =   $td->plaintext;   
                }
                $i++;
            }
        }
        $flavor   =   $html->find('table[class=flavor]');
        foreach($flavor as $flavor){
            foreach($flavor->find('tr') as $td){   
                if(($i%2) != 0){
                    $datos['text']  =   $td->plaintext;   
                }
                $i++;
            }
        }
        $effect   =   $html->find('table[class=effect]');
        foreach($effect as $effect){
            foreach($effect->find('tr') as $td){   
                if(($i%2) != 0){
                    $datos['effect']  =   $td->plaintext;   
                }
                $i++;
            }
        }
        if($datos[' Trigger '] != ' None '){
            $datos[' Trigger '] = $datos[' Trigger '].', '.$trigger[$k];
        }
        if(empty($datos[' Shield '])){
            $datos[' Shield '] = '-';
        }
        echo 'Agregado... '.$datos['cardID'].' '.$datos[' Name '].' ('.$link['name'].') '.$datos[' Trigger '].'<br>';
       //Copia la imagen y la guarda en el disco
        $img    =   $html->find('a[class=image image-thumbnail]');
        $img    =   $img[0]->attr['href'];
        $img    =   file_get_contents($img);
        $name = trim($datos[' Name ']);
        file_put_contents('cards/'.$name.'.png', $img); 
        // Preparar y ejecutar sentencias sql
        if($stm = $dbconn->prepare("insert into cards (cardID,name,uclass,triger,power,critical,shield,clan,race,effect,text,illustrator,nation,grade_skill) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
            if (!$stm->bind_param("ssssssssssssss",$cardID,$name,$uclass,$triggers,$power,$critical,$shield,$clan,$race,$effect,$text,$illustrator,$nation,$gradeskill)) {
                print("Error en bind_param: " . $stm->error . "n");
                exit;
            }
            $cardID =   trim($datos['cardID']);
            $name   =   trim($datos[' Name ']);
            $uclass =   trim($datos[' Unit Type ']);
            $triggers    =   trim($datos[' Trigger ']);
            $power  =   trim($datos[' Power ']);
            $critical   =   trim($datos[' Critical ']);
            $shield =   trim($datos[' Shield ']);
            $clan   =   trim($datos[' Clan ']);
            $race   =   trim($datos[' Race ']);
            $effect =   trim($datos['effect']);
            $text   =   trim($datos['text']);
            $illustrator    =   trim($datos[' Illust ']);
            $nation =   trim($datos[' Nation ']);
            $gradeskill =   trim($datos[' Grade / Skill ']);
            $stm->execute();
        }else{
            var_dump($dbconn->error);
        } 
        $k++;
    }
}
//Desconectarse de la base de datos
$dbconn->close();
?>