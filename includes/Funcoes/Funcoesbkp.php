<?php 

class Funcoes
{

	public static function limpa($str) {
		return strip_tags(trim($str));
	}

    public static function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }


	public static function otimizar_jpg($file, &$error = ''){

		/* EXEMPLO 
		$result = otimizar_jpg('nome_imagem.jpg', $error);
		if (false !== $result) { file_put_contents('nome_nova_imagem.jpg', $result); } else { echo "{$error}\n"; }
		*/

	   	$ch = curl_init('http://jpgoptimiser.com/optimise');
	   	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	   	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	   	curl_setopt($ch, CURLOPT_POST, 1);
   		curl_setopt($ch, CURLOPT_POSTFIELDS, [ 'input' => new CurlFile($file, 'image/jpg', $file) ]);
	   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   
	   	$img = curl_exec($ch);
	   	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	   	if ($status !== 200) {
	      	//$error = "jpgoptimiser.com request failed: HTTP code {$status}";
	      	return false;
	   	}
	   	$curl_error = curl_error($ch);
	   	if (!empty($curl_error)) {
	      	//$error = "jpgoptimiser.com request failed: CURL error ${$curl_error}";
	      	return false;
	   	}
	   	curl_close($ch);
	   	return $img;
	}

	public static function otimizar_png($PNGfile, &$error = ''){

		/* EXEMPLO 
		$result = otimizar_png('nome_imagem.png', $error);
		if (false !== $result) { file_put_contents('nome_nova_imagem.png', $result); } else { echo "{$error}\n"; }
		*/

	   	$ch = curl_init('http://pngcrush.com/crush');
	   	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	   	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	   	curl_setopt($ch, CURLOPT_POST, 1);
	   	curl_setopt($ch, CURLOPT_POSTFIELDS, array('input' => curl_file_create($PNGfile, 'image/png', $PNGfile)));
	   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   
	   	$png = curl_exec($ch);
	   	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	   	if ($status !== 200) {
	      	$error = "pngcrush.com request failed: HTTP code {$status}";
	      	return false;
	   	}
	   	$curl_error = curl_error($ch);
	   	if (!empty($curl_error)) {
	      	$error = "pngcrush.com request failed: CURL error ${$curl_error}";
	      	return false;
	   	}
	   	curl_close($ch);
	   	return $png;
	}

	public static function time_ago($time)
	{
	   $periods = array("segundo", "minuto", "hora", "dia", "semana", "mese", "ano", "década");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();

	       $difference     = $now - $time;
	       $tense         = "h&aacute;";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }

	   $difference = round($difference);

	   if($difference != 1) {
	       $periods[$j].= "s";
	   }

	   return "$tense $difference $periods[$j]";
	}

	public static function hashAleatorio($length=6) { 
		return substr(str_shuffle("aeiouyAEIOUYbdghjmnpqrstvzBDGHJLMNPQRSTVWXZ0123456789"), 0, $length); 
	}
	
	public static function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';

		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
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
		}else{
			return 'erro';
		}		
		return $date;
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
		
		if ($prox <= $ultima_pag && $ultima_pag > 2)
		{
		  $paginacao .= '<a href="?pag='.$prox.$parametrosbusca.'">pr&oacute;xima</a>';
		}
		  
		echo $paginacao."</div>";
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
		  $paginacao .= '<a href="'.$url.'/'.$parametrosbusca.'pag/'.$ant.'/" class="anterior"><<</a>';
		} else {
			$paginacao .= '<a class="anterior desativado"><<</a>';
		}
		  
		if ($ultima_pag <= 5)
		{
		  for ($i=1; $i<=$ultima_pag; $i++)
		  {
			if ($i == $pag)
			{
			  $paginacao .= '<a class="btn_pag atual" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';        
			} else {
			  $paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';  
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
				$paginacao .= '<a class="btn_pag atual" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$penultima.'/">'.$penultima.'</a>';
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$ultima_pag.'/">'.$ultima_pag.'</a>';
		  }
		  
		  elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3)
		  {
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/1/">1</a>';        
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/2/">2</a> ... ';  
			for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="btn_pag atual" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';  
			  }
			}
			$paginacao .= ' ... ';
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$penultima.'/">'.$penultima.'</a>';
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$ultima_pag.'/">'.$ultima_pag.'</a>';
		  }
		  else {
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/1/">1</a>';        
			$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/2/">2</a> ... ';  
			for ($i = $ultima_pag - (2 * $adjacentes); $i <= $ultima_pag; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<a class="btn_pag atual" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';        
			  } else {
				$paginacao .= '<a class="btn_pag" href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/">'.$i.'</a>';  
			  }
			}
		  }
		}
		
		if ($prox <= $ultima_pag)
		{
		  $paginacao .= '<a href="'.$url.'/'.$parametrosbusca.'pag/'.$prox.'/" class="proxima">>></a>';
		} else {
			$paginacao .= '<a  class="proxima desativado">>></a>';
		}
		  
		echo $paginacao."</div>";
	}

	public static function geraTimestamp($data) {
	$partes = explode('/', $data);
	return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
	}


}

?>
