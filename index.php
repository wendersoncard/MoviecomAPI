<?php

$db_host  = "107.170.68.250";
$db_name  = "wordpress";
$db_id    = "moviecom";
$db_pass  = "cumbaca@#";

$cn = mysqli_connect($db_host, $db_id, $db_pass, $db_name);
/* functions */
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

function verificaRegistraTokenUsuario($cn, $token){
  $token = anti_injection($token);

  $query = mysqli_query($cn, "SELECT * FROM api_user_token where token = '$token'");

  $usuario = mysqli_fetch_row($query)[1];

  if($usuario == ""){
    return false;
  }
  return true;
}

function registraLogRequisicao($cn, $token, $praca, $data_inicio, $data_fim, $status){
  //usuario
  $query = mysqli_query($cn, "SELECT * FROM api_user_token where token = '$token'");
  $usuario = mysqli_fetch_row($query)[1];

  //date_req
  $data = date("Y-m-d H:i:s");

  //params
  $params = "Praca: ".$praca." DataIni: ".$data_inicio." DataFim: ".$data_fim." Token: ".$token;

  $query = mysqli_query($cn, "INSERT INTO api_user_token_req (user_req, date_req, params_req, status_req, praca_req) VALUES ('$usuario', '$data', '$params', '$status', '$praca')");
}

function retornaJson($programacao){
  print(json_encode($programacao, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

/*fim functions*/

header('Content-Type: application/json; charset=utf-8');

$programacao = array();
$programacao['status'] = "";
$programacao['data'] = array();

//parâmetros válidos
if((!isset($_GET['praca']) || trim($_GET['praca']) == "") || (!isset($_GET['data_fim']) || trim($_GET['data_fim']) == "") || (!isset($_GET['data_ini']) || trim($_GET['data_ini']) == "") ){
  $programacao['status'] = "Error";
  $programacao['data'][] = "É necessário enviar os parâmetros de data início, data fim e código da praça.";

  registraLogRequisicao($cn, "", "", "", "", "Error. Invalid params");

  retornaJson($programacao);
  return false;
}
//token no get
else if(!isset(getallheaders()['user_token']) || trim(getallheaders()['user_token']) == ""){
  $programacao['status'] = "Error";
  $programacao['data'][] = "É necessário enviar o token de autenticação.";

  registraLogRequisicao($cn, "", anti_injection($_GET['praca']), anti_injection($_GET['data_ini']), anti_injection($_GET['data_fim']), "Error. No token");

  retornaJson($programacao);
  return false;
}
//autenticando
else if(!verificaRegistraTokenUsuario($cn, getallheaders()['user_token'])){
  $programacao['status'] = "Error";
  $programacao['data'][] = "Token de autenticação inválido.";

  registraLogRequisicao($cn, anti_injection(getallheaders()['user_token']), anti_injection($_GET['praca']), anti_injection($_GET['data_ini']), anti_injection($_GET['data_fim']), "Error. Invalid token");

  retornaJson($programacao);
  return false;
}
else{
    $praca = anti_injection($_GET['praca']);
    $dataIni = anti_injection($_GET['data_ini']);
    $dataFim = anti_injection($_GET['data_fim']);
    $token = anti_injection(getallheaders()['user_token']);

    $d1 = strtotime($dataIni);
    $d2 = strtotime($dataFim);

    $dias_diferenca = ((($d2 - $d1)/60)/60)/24;
    if($dias_diferenca > 60){
        $programacao['status'] = "Error";
        $programacao['data'][] = "Número máximo de dias disponíveis para consulta é de 60 dias."; 

        registraLogRequisicao($cn, $token, $praca, $dataIni, $dataFim, "Error. 60 days");

        retornaJson($programacao);
        return false;
    }
    try{ //inicio try
    
      mysqli_query($cn, 'SET CHARACTER SET utf8');

      $query_filmes = "SELECT distinct(IDFILMEPAI_DESC) FROM sys_programacao WHERE CODPRACA = '$praca' AND DATASESSAO > '$dataIni' AND DATASESSAO < '$dataFim' ORDER BY IDFILMEPAI_DESC";
      $result_filmes = mysqli_query($cn, $query_filmes);

      if(!$result_filmes){
        throw new Exception("Erro interno.");
      }

      $programacao['data'][]['praca'] = $praca; 

      while($row = mysqli_fetch_assoc($result_filmes)){ //while filmes
        $nome_filme = $row['IDFILMEPAI_DESC'];

        $query = "SELECT * FROM sys_programacao WHERE CODPRACA = '$praca' AND DATASESSAO > '$dataIni 00:00:00' AND DATASESSAO < '$dataFim 00:00:00' AND IDFILMEPAI_DESC = '$nome_filme' ORDER BY IDFILMEPAI_DESC, DATASESSAO";

        $result = mysqli_query($cn, $query);

        if(!$result){
          throw new Exception("Erro interno.");
        }

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
                  'ticket_sessao' => stripslashes('http://moviecom.com.br/vendasessao/?idprog='.$row1['IDPROG'].'&praca='.$praca)
              )
          );
           //codigo csn
           $codigo_csn = $row1['IDFILMEPAI'];
        } //fim while sessoes
        //fim sessoes

        //get post id
        $post_id_query = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_value = $codigo_csn and meta_key = 'codigo_csn'");
        if(!$post_id_query){
          throw new Exception("Erro interno.");
        }
        $post_id = mysqli_fetch_row($post_id_query)[1];

        //get post
        $post = mysqli_query($cn, "SELECT * FROM wp_posts where id = $post_id");
        if(!$post){
          throw new Exception("Erro interno.");
        }
        //get sinopse
        $sinopse = mysqli_fetch_row($post)[4];

        //get cartaz
        $query_cartaz = mysqli_query($cn, "SELECT * FROM wp_posts WHERE post_parent = '$post_id' AND post_type = 'attachment'");
        if(!$query_cartaz){
          throw new Exception("Erro interno.");
        }
        $cartaz = mysqli_fetch_row($query_cartaz)[18];

        //get censura
        $query_censura = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_key = 'censura' and post_id = '$post_id'");
        if(!$query_censura){
          throw new Exception("Erro interno.");
        }
        $censura = mysqli_fetch_row($query_censura)[3];

        //get trailer
        $query_trailer = mysqli_query($cn, "SELECT * FROM wp_postmeta where meta_key = 'trailer' and post_id = '$post_id'");
        if(!$query_trailer){
          throw new Exception("Erro interno.");
        }
        $trailer = mysqli_fetch_row($query_trailer)[3];

        //cria json final do filme
        $programacao['data'][0]['filmes'][] = array (
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
      } //fim while filmes
      $programacao['status'] = "Success";

      registraLogRequisicao($cn, $token, $praca, $dataIni, $dataFim, "Success");

      retornaJson($programacao);
    } //fim try
    catch(Exception $ex){
      $programacao['status'] = "Error";
      $programacao['data'][] = $ex->getMessage(); 

      registraLogRequisicao($cn, $token, $praca, $dataIni, $dataFim, $ex->getMessage());

      retornaJson($programacao);
      return false;
    }
}
?>