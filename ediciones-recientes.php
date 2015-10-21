<?php
require ('simple_html_dom.php');
require ('conexion.php');
$url	=	'http://cardfight.wikia.com/wiki/G_Trial_Deck_5:_Fateful_Star_Messiah';  /// <- Colocamos el link de la edición
$html	=	file_get_html($url);

//Obtiene links de las cartas
$tabla	=	array_slice($html->find('table[class=sortable]'), 0, 1);
$links  =   array();
foreach($tabla as $table){
    $i	=	0;
    $k  =   0;
    foreach($table->find('td a') as $td){
        if(($i%2) == 0){
            $cadena_de_texto = $td->attr['href'];
            $cadena_buscada   = 'http';
            $posicion_coincidencia = strpos($cadena_de_texto, $cadena_buscada);
            if(strpos($cadena_de_texto, 'edit')){
                $links[$k]   =   'http://www.google.cl';

            }else{
                if($posicion_coincidencia === false){
                    $links[$k]   =   'http://cardfight.wikia.com'.$td->attr['href'];
                }else{
                    $links[$k]   =   $td->attr['href'];
                }    
            }
            $k++;
        }
        $i++;
    }
}

//Obtiene ID de las cartas
foreach($tabla as $table1){
    $i	=	0;
    $k  =   0;
    foreach($table1->find('tr td') as $td){
            if((($i == 0) || ($i == 6)) && (strpos($td->plaintext, 'Card') === false)){
            $id[$k]   =   $td->plaintext;
                //echo $id[$k].'<br>';
                $k++;
            }
            if($i == 6){
                $i = 0;
            }
        $i++;
        }   
}


//Obtiene trigger de las cartas
foreach($tabla as $table2){
    $i	=	0;
    $k  =   0;
    foreach($table2->find('tr td') as $td){
        if($i == 4){
            $trigger[$k]   =   $td->plaintext;
        }
        if($i == 5){
            $i = -1;
            $k++;
        }
        $i++;
    }
}
//Obtiene datos de cada carta
$k  =   0;
foreach($links as $link){
    if(!$html   =   file_get_html($link)){
        exit;
    };
    $post   =   $html->find('div[class=info-main] table');
    $datos['cardID']    =   $id[$k];
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
    echo 'Agregando... '.$datos['cardID'].' '.$datos[' Name '].' ('.$link.') '.$datos[' Trigger '].'<br>';

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

// Desconectarse de la base de datos
$dbconn->close();

