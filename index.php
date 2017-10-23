<?php
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

?>