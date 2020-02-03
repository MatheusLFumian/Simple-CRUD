<?php

class Funcoes
{

	public static function hashAleatorio($length=6) { 
		return substr(str_shuffle("aeiouyAEIOUYbdghjmnpqrstvzBDGHJLMNPQRSTVWXZ0123456789"), 0, $length); 
	}

	public static function compartilhe($id, $nome){
		$link = SITE_URL.'produtos/'.$nome.'#'.$id;
		$linkCurto = encurtaUrl($link);
		
		//Facebook
		$a = '<div class="fb-like" data-href="'.$link.'" data-layout="button_count" data-action="recommend" data-show-faces="false" data-share="false" style="margin-right:30px;"></div>';
		
		//Twitter
		$a .= '<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$link.'" data-text="'.$nome.'. via Câmeras e DVRs '.$linkCurto.'" data-lang="pt" data-hashtags="cameras seguranca dvrs monitoramento">Tweetar</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>';
	}
	
	public static function convToUtf8($str) { 
		if(mb_detect_encoding($str)!="UTF-8" ) 
		return  iconv(mb_detect_encoding($str),"UTF-8",$str); 
		
		else 
		return $str; 
	}


	
	public static function toAscii($str, $replace=array(), $delimiter='-') {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
		$clean = str_replace('ç', 'c', $str);
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
	
	public static function resumo($texto,$qnt){
	    if (strlen($texto)>$qnt){
			$resumo=substr($texto,'0',$qnt);
			$last=strrpos($resumo," ");
			$resumo=substr($resumo,0,$last);
			return $resumo."...";
		} else {
			return $texto;
		}
	}
	
	
	public static function loga($msg) {
		$msg =  $msg . "\r\n";
		file_put_contents ('PD.log', $msg, FILE_APPEND);
	}
	
	public static function pegaFrete($cepOrigem = 27281830, $cepDestino, $peso=300, $tipo) {
		

		
		$tipoFrete = $tipo==41106 ? "PAC" : "SEDEX";
		$peso = $peso<300 ? 300 : $peso ;
		$pesoUsa = intval($peso)/1000;
		
		$nCdServico 		 = $tipo;
		$nCdEmpresa          = "";
		$sDsSenha            = "";
		$nCdFormato          = 1;
		$nVlComprimento      = 16;
		$nVlAltura           = 11;
		$nVlLargura          = 11;
		$nVlDiametro         = 0;
		$sCdMaoPropria       = "N";
		$nVlValorDeclarado   = 0;
		$sCdAvisoRecebimento = "N";
				
		$sCepOrigem          = str_replace("-", "", trim($cepOrigem));
		$sCepDestino		 = str_replace("-", "", trim($cepDestino));
		$nVlPeso             = $pesoUsa; 
				
		$URLcorreios =trim("http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=$nCdEmpresa&sDsSenha=$sDsSenha&sCepOrigem=$sCepOrigem&sCepDestino=$sCepDestino&nVlPeso=$nVlPeso&nCdFormato=$nCdFormato&nVlComprimento=$nVlComprimento&nVlAltura=$nVlAltura&nVlLargura=$nVlLargura&sCdMaoPropria=$sCdMaoPropria&nVlValorDeclarado=$nVlValorDeclarado&sCdAvisoRecebimento=$sCdAvisoRecebimento&nCdServico=$nCdServico&nVlDiametro=$nVlDiametro&StrRetorno=xml");
		//echo $URLcorreios;
		if($file = simplexml_load_file($URLcorreios)){
			foreach ($file->cServico as $item) {	
				$valor = str_replace(",",".", $item->Valor);
				$erro = $item->Erro;	
				$prazo = intval($item->PrazoEntrega);
				$total = $_SESSION['totalCarrinho'];
				if($erro=="0" ){
					return array("ok" => 'true', "tipo"=>$tipoFrete,"valor" => $valor, "prazo" => $prazo );			
				} else {
					$msg = $item->MsgErro;
					return array("ok" => 'false', "tipo"=>"Erro","valor"=>$msg, "prazo"=> 0);
					
				}
			} 
		} else {
			//$msg = $item->MsgErro;
			return array("ok" => 'false', "tipo"=>"Erro","valor"=>"nao carregou o arquivo $URLcorreios", "prazo"=> 0);
		}
		
	}
	
	public static function showCategoria($a=0){
		
		global $DB;
		
		if($a>0) {
			
			$sql = $DB->query("select * from areas where idArea=$a limit 1")->fetch();
			

			if ($cat = $DB->query("select * from categorias where idAreaCategoria=$a order by nomeCategoria asc")) {
				
				echo "<h2>".$sql['nomeArea']."<span class='i$a'></span></h2>";
				echo "<ul>";
				foreach($cat as $c){
					$idc = $c['idCategoria'];
					$nomec = $c['nomeCategoria'];
					$des = $c['destaqueCategoria'];
					echo "<li><a href='produtos.php?a=$a&c=$idc' class=''>$nomec</a></li>";
				}
				echo "</ul>";
			}
		}
		
		$n = $DB->query("select * from areas where idArea<>$a order by idArea");
		
		while($sql = $n->fetch()) {
			
			$a = $sql['idArea'];
			
			if ($cat = $DB->query("select * from categorias where idAreaCategoria=$a order by nomeCategoria asc")) {		
				echo "<h2>".$sql['nomeArea']."<span class='i$a'></span></h2>";
				echo "<ul>";
				$totaldes = 0;
				foreach($cat as $c){
					$idc = $c['idCategoria'];
					$nomec = $c['nomeCategoria'];
					$des = $c['destaqueCategoria'];
					$class= $des==0 ? "esconde" : "" ;
					$totaldes = $des==0 ? $totaldes+1 : $totaldes ;
					echo "<li class='$class'><a href='produtos.php?a=$a&c=$idc' class=''>$nomec</a></li>";
				}
				echo $totaldes>0 ? "<li><a href='' class='mais'>+ mais categorias</a></li>" : "" ;
				echo "</ul>";
			}
			
		}
	}
	
	
	
	
	public static function trocaExtensao($filename, $new_extension) {
	    $info = pathinfo($filename);
    	return $info['filename'] . '.' . $new_extension;
	}

    public static function fdata($data,$tipo){
		$exData = substr($data,0,10);
		$exData = explode($tipo,$exData);
		if($tipo=='/'){
			$date = $exData[2].'-'.$exData[1].'-'.$exData[0];		
		}else if($tipo=='-'){
			$date = $exData[2].'/'.$exData[1].'/'.$exData[0];
		}else if($tipo=='extenso'){
			$meses = array('a', 'Janeiro', 'Fevereiro', 'Março', 'Abril ','Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
			$date = $exData[2].' de '.$meses[$exData[1]].' de '.$exData[0];
		}else{
			return 'erro';
		}		
		return $date;
	}

	public static function fdataExtenso($data,$tipo){
		$exData = substr($data,0,10);
		$exData = explode($tipo,$exData);
		$meses = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril ','Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
		if(($tipo=='/')||($tipo=='-')){
			$date = $exData[2].' de '.$meses[intval($exData[1])].' de '.$exData[0];
		}else{
			return 'erro';
		}		
		return $date;
	}
	
	
	public static function paginar($total, $pag , $id = "paginacao" ) {
	
		$itens = "";
		
		if($total>1){	
			for ($i=1; $i<=$total; $i++) $itens .= "<a ".($i==$pag? "" : "href='?pag=$i'").($i==$pag? " class='ativo'" : "").">$i</a>";
			$itens = "<div id='$id'>" . $itens . "</div>";
		}
		
		return $itens;
	}

	public static function paginar_wget_avancado($total, $pag, $parametrosbusca, $id = "paginacao" ) {
	
		$paginacao = "";
		
		echo "<div class='paginacao'>";
		
		$prox = $pag + 1;
		$ant = $pag - 1;
		$ultima_pag = $total;
		$penultima = $ultima_pag - 1;  
		$adjacentes = 2;
		
		if ($pag>1)
		{
		  $paginacao .= '<a href="?pag='.$ant.$parametrosbusca.'" class="anterior">anterior</a>';
		} else {
			$paginacao .= '<a class="anterior">anterior</a>';
		}
		  
		if ($ultima_pag <= 5)
		{
		  for ($i=1; $i<=$ultima_pag; $i++)
		  {
			if ($i == $pag)
			{
			  $paginacao .= '<a class="atual" href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';        
			} else {
			  $paginacao .= '<a href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';  
			}
		  }
		}
		
		if ($ultima_pag > 5)
		{
		  if ($pag < 1 + (2 * $adjacentes))
		  {
			for ($i=1; $i< 2 + (2 * $adjacentes); $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a href="?pag='.$penultima.$parametrosbusca.'">'.$penultima.'</a>';
			$paginacao .= '<a href="?pag='.$ultima_pag.$parametrosbusca.'">'.$ultima_pag.'</a>';
		  }
		  
		  elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3)
		  {
			$paginacao .= '<a href="?pag=1'.$parametrosbusca.'">1</a>';        
			$paginacao .= '<a href="?pag=2'.$parametrosbusca.'">2</a> ... ';  
			for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a href="?pag='.$penultima.$parametrosbusca.'">'.$penultima.'</a>';
			$paginacao .= '<a href="?pag='.$ultima_pag.$parametrosbusca.'">'.$ultima_pag.'</a>';
		  }
		  else {
			$paginacao .= '<a href="?pag=1'.$parametrosbusca.'">1</a>';        
			$paginacao .= '<a href="?pag=2'.$parametrosbusca.'">2</a> ... ';  
			for ($i = $ultima_pag - (2 * $adjacentes); $i <= $ultima_pag; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
		  }
		}
		
		if ($prox <= $ultima_pag)
		{
		  $paginacao .= '<a href="?pag='.$prox.$parametrosbusca.'">pr&oacute;xima</a>';
		} else {
			$paginacao .= '<a>pr&oacute;xima</a>';
		}
		  
		echo $paginacao."</div>";
	}

	public static function paginar_wget_avancado_amigavel($total, $pag, $parametrosbusca, $id = "paginacao", $url ) {
	
		$paginacao = "";
		
		echo "<div class='paginacao'>";
		
		$prox = $pag + 1;
		$ant = $pag - 1;
		$ultima_pag = $total;
		$penultima = $ultima_pag - 1;  
		$adjacentes = 2;
		
		if ($pag>1)
		{
		  $paginacao .= '<a href="'.$url.'/pag/'.$ant.'/'.$parametrosbusca.'" class="anterior">anterior</a>';
		} else {
			$paginacao .= '<a class="anterior">anterior</a>';
		}
		  
		if ($ultima_pag <= 5)
		{
		  for ($i=1; $i<=$ultima_pag; $i++)
		  {
			if ($i == $pag)
			{
			  $paginacao .= '<a class="atual" href="?pag='.$i.$parametrosbusca.'">'.$i.'</a>';        
			} else {
			  $paginacao .= '<a href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';  
			}
		  }
		}
		
		if ($ultima_pag > 5)
		{
		  if ($pag < 1 + (2 * $adjacentes))
		  {
			for ($i=1; $i< 2 + (2 * $adjacentes); $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a href="'.$url.'/pag/'.$penultima.'/'.$parametrosbusca.'">'.$penultima.'</a>';
			$paginacao .= '<a href="'.$url.'/pag/'.$ultima_pag.'/'.$parametrosbusca.'">'.$ultima_pag.'</a>';
		  }
		  
		  elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3)
		  {
			$paginacao .= '<a href="'.$url.'/pag/1/'.$parametrosbusca.'">1</a>';        
			$paginacao .= '<a href="'.$url.'/pag/2/'.$parametrosbusca.'">2</a> ... ';  
			for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a href="'.$url.'/pag/'.$penultima.'/'.$parametrosbusca.'">'.$penultima.'</a>';
			$paginacao .= '<a href="'.$url.'/pag/'.$ultima_pag.'/'.$parametrosbusca.'">'.$ultima_pag.'</a>';
		  }
		  else {
			$paginacao .= '<a href="?pag=1'.$parametrosbusca.'">1</a>';        
			$paginacao .= '<a href="?pag=2'.$parametrosbusca.'">2</a> ... ';  
			for ($i = $ultima_pag - (2 * $adjacentes); $i <= $ultima_pag; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="atual" href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a href="'.$url.'/pag/'.$i.'/'.$parametrosbusca.'">'.$i.'</a>';  
			  }
			}
		  }
		}
		
		if ($prox <= $ultima_pag)
		{
		  $paginacao .= '<a href="'.$url.'/pag/'.$prox.'/'.$parametrosbusca.'">pr&oacute;xima</a>';
		} else {
			$paginacao .= '<a>pr&oacute;xima</a>';
		}
		  
		echo $paginacao."</div>";
	}
	
	public static function paginar_wget($total, $pag, $parametrosbusca, $id = "paginacao" ) {
	
		$itens = "";
		
		if($total>1){	
			for ($i=1; $i<=$total; $i++) $itens .= "<a ".($i==$pag? "" : "href='?pag=$i$parametrosbusca'" ).($i==$pag? " class='ativo'" : "").">$i</a>";
			$itens = "<div id='$id'>" . $itens . "</div>";
		}
		
		return $itens;
	}
	
	public static function aspectRatio($a, $b , $c = null) {  
		
		$aa = $a;
		$bb = $b;
		
		while ($b != 0) {
			$remainder = $a % $b;  
			$a = $b;  
			$b = $remainder;  
		} 	 
		$gcd = abs ($a);  
		$a = $aa;
		$b = $bb;
		$a = $a/$gcd;  
		$b = $b/$gcd;  
		$ratio = $a . ":" . $b;  
		
		if (isset($c)){
			return array($ratio, $a, $b);
		} else {
			return $ratio;
		}
		
	
	} 
	
	public static function diffDate($d1, $d2, $type='', $sep='-')
	{
	 $d1 = explode($sep, $d1);
	 $d2 = explode($sep, $d2);
	 switch ($type)
	 {
	 case 'A':
	 $X = 31536000;
	 break;
	 case 'M':
	 $X = 2592000;
	 break;
	 case 'D':
	 $X = 86400;
	 break;
	 case 'H':
	 $X = 3600;
	 break;
	 case 'MI':
	 $X = 60;
	 break;
	 default:
	 $X = 1;
	 }
	 $da2 = mktime(0, 0, 0, $d2[1], $d2[2], $d2[0]);
	 $da1 = mktime(0, 0, 0, $d1[1], $d1[2], $d1[0]);
	
	
	 return floor( ( ( $da2-$da1) / $X ) );
	}
	
	public static function showmes($strmes){
	$strmes = intval($strmes)  ;
	$meses = array('a', 'Janeiro', 'Fevereiro', 'Março', 'Abril ','Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	return $meses[$strmes];
	}
	
	public static function mes($strmes){
	$strmes = intval($strmes)  ;
	$meses = array('a', 'JAN', 'FEV', 'MAR', 'ABR ','MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ');
	return $meses[$strmes];
	}
	
	public static function datarel($data){
		$ano = substr($data, 0,4 );
		$mes = showmes(intval(substr($data, 5,2 )));
		$dia = substr($data, 8,2 );
		return $dia ." de ". $mes . " de ". $ano;
	
	}
	
	public static function converter_data($strData) {
	        // Recebemos a data no formato: dd/mm/aaaa
	        // Convertemos a data para o formato: aaaa-mm-dd
	        if ( preg_match("#/#",$strData) == 1 ) {
	                $strDataFinal = "'";
	                $strDataFinal .= implode('-', array_reverse(explode('/',strData)));
	                $strDataFinal .= "'";
	        }
	        return $strDataFinal;
	}
	public static function showfab($id){
	    $sqlshowfab = mysql_query("select nome from fabricantes where id='$id'") or die("erro linha 18: ".mysql_error());
	    if(mysql_num_rows($sqlshowfab)>0){
			$fab = mysql_fetch_array($sqlshowfab);	
			return $fab['nome'];	
		} else{
			return "";
		}
		
	}
	public static function showc($fComb, $fGnv ){
		if ($fComb=="a"){
			$result = "Álcool";
		} else if ($fComb=="g"){
			$result = "Gasolina";
		} else if ($fComb=="f"){
			$result = "Flex";
		} else if ($fComb=="d"){
			$result = "Diesel";
		}	
		if ($fGnv>0) {
			$result .=" + Gnv";
		}
		return $result;
		
	}
	
	public static function showcat($id){
	    $sqlshowcat = mysql_query("select nome from categorias where id='$id'");
	    $cat = mysql_fetch_array($sqlshowcat);
		return $cat['nome'];
	}
	
	public static function showlinha($id){
	    if($id==1){
			return "Anest&eacute;sicos";
		} else if($id==2){
			return "Produtos Veterin&aacute;rios";
		} else if($id==3){
			return "Produtos Hospitalares";
		} else if($id==4){
			return "Higiene e Beleza";
		} else if($id==5){
			return "Ra&ccedil;&otilde;es";
		}
	}
	 /* RALPH */
	
	public static function asi($string)
	{
	    $string = get_magic_quotes_gpc() ? stripslashes($string) : $string;
	    $string = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($string) : mysql_escape_string($string);
	    return $string;
	}
	
	
	public static function validacpf($CampoNumero)
	{
		$RecebeCPF=$CampoNumero;
	   //Retirar todos os caracteres que nao sejam 0-9
	   $s="";
	   for ($x=1; $x<=strlen($RecebeCPF); $x=$x+1)
	   {
	    $ch=substr($RecebeCPF,$x-1,1);
	    if (ord($ch)>=48 && ord($ch)<=57)
	    {
	      $s=$s.$ch;
	    }
	   }
	    
	   $RecebeCPF=$s;
	   if (strlen($RecebeCPF)!=11)
	   {
	    return "false";
	   }
	   else
	     if ($RecebeCPF=="00000000000")
	     {
	       $then;
	       return "false";
	     }
	     else
	     {
	      $Numero[1]=intval(substr($RecebeCPF,1-1,1));
	      $Numero[2]=intval(substr($RecebeCPF,2-1,1));
	      $Numero[3]=intval(substr($RecebeCPF,3-1,1));
	      $Numero[4]=intval(substr($RecebeCPF,4-1,1));
	      $Numero[5]=intval(substr($RecebeCPF,5-1,1));
	      $Numero[6]=intval(substr($RecebeCPF,6-1,1));
	      $Numero[7]=intval(substr($RecebeCPF,7-1,1));
	      $Numero[8]=intval(substr($RecebeCPF,8-1,1));
	      $Numero[9]=intval(substr($RecebeCPF,9-1,1));
	      $Numero[10]=intval(substr($RecebeCPF,10-1,1));
	      $Numero[11]=intval(substr($RecebeCPF,11-1,1));
	
	     $soma=10*$Numero[1]+9*$Numero[2]+8*$Numero[3]+7*$Numero[4]+6*$Numero[5]+5*
	     $Numero[6]+4*$Numero[7]+3*$Numero[8]+2*$Numero[9];
	     $soma=$soma-(11*(intval($soma/11)));
	
	    if ($soma==0 || $soma==1)
	    {
	      $resultado1=0;
	    }
	    else
	    {
	      $resultado1=11-$soma;
	    }
	
	    if ($resultado1==$Numero[10])
	    {
	     $soma=$Numero[1]*11+$Numero[2]*10+$Numero[3]*9+$Numero[4]*8+$Numero[5]*7+$Numero[6]*6+$Numero[7]*5+
	     $Numero[8]*4+$Numero[9]*3+$Numero[10]*2;
	     $soma=$soma-(11*(intval($soma/11)));
	
	     if ($soma==0 || $soma==1)
	     {
	       $resultado2=0;
	     }
	     else
	     {
	      $resultado2=11-$soma;
	     }
	     if ($resultado2==$Numero[11])
	     {
	      return "true";
	     }
	     else
	     {
	     return "false";
	     }
	    }
	    else
	    {
	     return "false";
	    }
	   }
	   
	}
	
	
	public static function encurtaUrl($urlDecodificar){
	
		if($urlDecodificar!='') {
			
			$url = 'https://www.googleapis.com/urlshortener/v1/url';
			$data['longUrl'] = $urlDecodificar;
			$data['key'] = 'AIzaSyAVkPHe4rfFByMRqGfsI4ungUaNzkO284Q';
			$data = json_encode($data);
			
			$curl = curl_init($url);
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$data = curl_exec($curl);
			curl_close($curl);
			$data = json_decode($data);
	
			//print_r($data);
		
			return $data->id;
			
		} else {
			return false;
		}
	
	}
	
	public static function GERA_IMAGEM($IMAGEM,$NOME,$DESTINO,$NOVO_NOME,$V1,$V2,$DIRECAO) {

		if ($NOME !="") { 
			$img=getimagesize($IMAGEM);
			$altura = $img[1];
			$largura = $img[0];
			if ($DIRECAO=="s"){
				if ( $largura> $altura) {
					$LARGURA_NOVA = $V1;
					$ALTURA_NOVA = $V2;
				} else {
					$LARGURA_NOVA = $V2;
					$ALTURA_NOVA = $V1;
				}
			} else {
				$LARGURA_NOVA = $V1;
				$ALTURA_NOVA = $V2;
			}
			$valid=explode(".",$NOME);
			$extencao=strtolower($valid[1]);
			$extencao=trim($extencao);
			if (($extencao=="jpg") || ($extencao=="jpeg")) {
				@mkdir($DESTINO);
				$NOVO_NOME = $NOVO_NOME.".$extencao";
				if (copy($IMAGEM, "$DESTINO/$NOVO_NOME"))	{
					$img_base=imagecreatefromjpeg ("$DESTINO/$NOVO_NOME"); 
					$img_nova = imagecreatetruecolor($LARGURA_NOVA,$ALTURA_NOVA); 
					$ratio = $largura / $altura ;
					$RATIO_NOVO = $LARGURA_NOVA / $ALTURA_NOVA;
					if ($ratio > $RATIO_NOVO) {
						$altura_n = $altura;
						$largura_n = intval($altura * $RATIO_NOVO) ;
						$x_n= intval(($largura - $largura_n)/2);
						$y_n= 0;
						imagecopyresized ($img_nova, $img_base, 0, 0, $x_n,$y_n, $LARGURA_NOVA, $ALTURA_NOVA, $largura_n, $altura_n);
					} else {
						$largura_n = $largura;
						$altura_n = intval($largura_n / $RATIO_NOVO);
						$x_n= intval(($largura - $largura_n)/2);
						$y_n= 0;
						imagecopyresized ($img_nova, $img_base, 0, 0, $x_n,$y_n, $LARGURA_NOVA, $ALTURA_NOVA, $largura_n, $altura_n);
					}
					imagejpeg($img_nova,"$DESTINO/$NOVO_NOME",90);
					return $NOVO_NOME;
				} else {
					echo "<script>alert('O arquivo nao foi enviado')</script>";
					return false;
				}
			} else {
					echo "<script>alert('Tipo incorreto de arquivo')</script>";
					return false;
			}	 
		}
		
		}
		


}

function showmes($strmes){
$strmes = intval($strmes)  ;
$meses = array('a', 'Janeiro', 'Fevereiro', 'Março', 'Abril ','Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
return $meses[$strmes];
}

 // Hello There!


function encurtaUrl($urlDecodificar){

	if($urlDecodificar!='') {
		
		$url = 'https://www.googleapis.com/urlshortener/v1/url';
		$data['longUrl'] = $urlDecodificar;
		$data['key'] = 'AIzaSyAVkPHe4rfFByMRqGfsI4ungUaNzkO284Q';
		$data = json_encode($data);
		
		$curl = curl_init($url);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$data = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($data);

		//print_r($data);
	
		return $data->id;
		
	} else {
		return false;
	}

}

?>
