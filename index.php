<?php

$db_host  = "107.170.68.250";
$db_name  = "wordpress";
$db_id    = "moviecom";
$db_pass  = "cumbaca@#";

$cn = mysqli_connect($db_host, $db_id, $db_pass, $db_name);

function anti_injection($sql){
   // remove palavras que contenham sintaxe sql
   $sql = str_replace("from", "", $sql);
   $sql = str_replace("select", "", $sql);
   $sql = str_replace("insert", "", $sql);
   $sql = str_replace("delete", "", $sql);
   $sql = str_replace("where", "", $sql);
   $sql = str_replace("drop", "", $sql);
   $sql = str_replace("table", "", $sql);
   $sql = str_replace("show", "", $sql);
   $sql = str_replace("update", "", $sql);
   $sql = str_replace("select", "", $sql);
   $sql = str_replace("union", "", $sql);
   $sql = str_replace("all", "", $sql);
   $sql = str_replace("order by", "", $sql);
   $sql = str_replace("or 1=1", "", $sql);
   $sql = str_replace("') or '1'='1", "", $sql);
   $sql = str_replace("') or ('1'='1", "", $sql);
   $sql = trim($sql);//limpa espaços vazio
   $sql = strip_tags($sql);//tira tags html e php
   $sql = addslashes($sql);//Adiciona barras invertidas a uma string

   $retirar = array("[=://", "/]", "[/]", "://","[","]");
   $sql = str_replace($retirar, "", $sql);
   
   return $sql;
}

header('Content-Type: application/json; charset=utf-8');

if
(
   (!isset($_GET['praca']) || trim($_GET['praca']) == "") || 
   (!isset($_GET['data_fim']) || trim($_GET['data_fim']) == "") || 
   (!isset($_GET['data_fim']) || trim($_GET['data_ini']) == "") 

){
   echo 'É necessário enviar os parâmetros de data início, data fim e código da praça';
}
else{
   $praca = anti_injection($_GET['praca']);
   $dataIni = anti_injection($_GET['data_ini']);
   $dataFim = anti_injection($_GET['data_fim']);

   $d1 = strtotime($dataIni);
   $d2 = strtotime($dataFim);

   $dias_diferenca = ((($d2 - $d1)/60)/60)/24;
   if($dias_diferenca > 60){
      echo 'Número máximo de dias disponíveis para consulta é de 60 dias.';
      return false;
   } 

   mysqli_query($cn, 'SET CHARACTER SET utf8');

   $query_filmes = "SELECT distinct(IDFILMEPAI_DESC) FROM sys_programacao WHERE CODPRACA = '$praca' AND DATASESSAO > '$dataIni' AND DATASESSAO < '$dataFim' ORDER BY IDFILMEPAI_DESC";
   $result_filmes = mysqli_query($cn, $query_filmes);

   $programacao = array();
   $programacao['praca'] = $praca; 

   while($row = mysqli_fetch_assoc($result_filmes)){

      $nome_filme = $row['IDFILMEPAI_DESC'];

      $query = "SELECT * FROM sys_programacao WHERE CODPRACA = '$praca' AND DATASESSAO > '$dataIni 00:00:00' AND DATASESSAO < '$dataFim 00:00:00' AND IDFILMEPAI_DESC = '$nome_filme' ORDER BY IDFILMEPAI_DESC, DATASESSAO";

      $result = mysqli_query($cn, $query);

      //sessoes
      $horarios = array();

      $codigo_csn = "";

      while($row1 = mysqli_fetch_assoc($result)){

         //tipo
         $tipo = "";
         $tipoCod = $row1['DUBLADO'].$row1['LEGENDADO'].$row1['TRESD']; //bool dub leg 3d

         if ($tipoCod == "100"){
               $tipo = 'DUB';
         }
         else if ($tipoCod == "010"){
            $tipo = 'LEG';
         }
         else if ($tipoCod == "101"){
            $tipo = 'DUB 3D';
         }
         else if ($tipoCod == "011"){
            $tipo = 'LEG 3D';
         }
         else if ($tipoCod == "000"){
            $tipo = 'NACIONAL';
         }

         //dia / hora
         $data = split(' ', $row1['DATASESSAO']);
         $dia = $data[0];
         $hora = $data[1];


         $horarios[] = array(
             'sessao' => array(
                    'tipo' =>  $tipo,
                    'dia' => $dia,
                    'hora' => $hora,
                    'sala' => substr($row1['CODSALA'], 4),
                    'ticket_sessao' => 'http://moviecom.com.br/vendasessao/?idprog='.$row1['IDPROG'].'&praca='.$praca
                 )
         );

         //codigo csn
         $codigo_csn = $row1['IDFILMEPAI'];
      }
      //fim sessoes

      
      //get post id
      $post_id_query = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_value = $codigo_csn and meta_key = 'codigo_csn'");
      $post_id = mysqli_fetch_row($post_id_query)[1];

      //get post
      $post = mysqli_query($cn, "SELECT * FROM wp_posts where id = $post_id");

      //get sinopse
      $sinopse = mysqli_fetch_row($post)[4];

      //get cartaz
      $query_cartaz = mysqli_query($cn, "SELECT * FROM wp_posts WHERE post_parent = '$post_id' AND post_type = 'attachment'");
      $cartaz = mysqli_fetch_row($query_cartaz)[18];

      //get censura
      $query_censura = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_key = 'censura' and post_id = '$post_id'");
      $censura = mysqli_fetch_row($query_censura)[3];

      //get trailer
      $query_trailer = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_key = 'trailer' and post_id = '$post_id'");
      $trailer = mysqli_fetch_row($query_trailer)[3];

      //cria json final do filme
      $programacao['filmes'][] = array (
           'filme' => array(
           'titulo' => $row['IDFILMEPAI_DESC'],
           'cartaz' => $cartaz,
           'sinopse' => $sinopse,
           'censura' => $censura,
           'trailer' => $trailer,
           'ticket_filme' => 'http://moviecom.com.br/vendafilme/?praca='.$praca.'&idfilme='.$codigo_csn,
           'horarios' => $horarios,
           )
      );
   }


   print(json_encode($programacao, JSON_PRETTY_PRINT));
   print(json_encode($programacao, JSON_UNESCAPED_UNICODE)); //pros caracter especial funcionar
}

?>