<?php 

class Funcoes
{

	public static function CalculaIdade($data_nasc, $data = "hoje"){
	// Separa em dia, mês e ano
		list($dia_idade, $mes_idade, $ano_idade) = explode('/', $data_nasc);
	if($data == "hoje"){
		// Descobre que dia é hoje e retorna a unix timestamp
		$hoje_idade = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	}else{
		//Pega a data
		list($dia_hj, $mes_hj, $ano_hj) = explode('/', $data);
		// Descobre que dia é hoje e retorna a unix timestamp
		$hoje_idade = mktime(0, 0, 0, $mes_hj, $dia_hj, $ano_hj);
	}
		// Descobre a unix timestamp da data de nascimento do fulano
		$nascimento_idade = mktime( 0, 0, 0, $mes_idade, $dia_idade, $ano_idade);
		// Depois apenas fazemos o cálculo já citado :)
		$idade_idade = floor((((($hoje_idade - $nascimento_idade) / 60) / 60) / 24) / 365.25);
		return $idade_idade;
	}


	public static function calc_idade( $data_nasc ){
		$data_nasc = explode("-", $data_nasc);
		$data = date("d-m-Y");
		$data = explode("-", $data);
		$anos = $data[2] - $data_nasc[2];
		if ( $data_nasc[1] >= $data[1] ){
			if ( $data_nasc[0] <= $data[0] ){
				return $anos;
			}else{
				return $anos-1;
			}
		}else{
			return $anos;
		}
	}

	public static function limpa($str) {
		return strip_tags(trim($str));
	}

    public static function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ? '' : basename($filename))
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
	   	curl_setopt($ch, CURLOPT_POSTFIELDS, array('input' => CurlFile($file, 'image/jpg', $file)));
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

// inicio paginaçao bootstrap
public static function paginar_wget_bootstrap($total, $pag, $parametrosbusca, $id = "paginacao", $url ) { 
	
		$paginacao = "";
		
		echo "<nav><ul class='paginacao pagination'>";
		
		$prox = $pag + 1;
		$ant = $pag - 1;
		$ultima_pag = $total;
		$penultima = $ultima_pag - 1;  
		$adjacentes = 2;
		
		if ($pag>1)
		{
		  $paginacao .= '
		  <li>
		      <a href="'.$url.'/'.$parametrosbusca.'pag/'.$ant.'/" class="anterior" aria-label="Previous">
		        <span aria-hidden="true">&laquo;</span>
		      </a>
	      </li>
	      ';
		} else {
			$paginacao .= '
			  <li class="disabled">
			      <a class="anterior desativado " aria-label="Previous">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
		      </li>
			';
		}
		  
		if ($ultima_pag <= 5)
		{
		  for ($i=1; $i<=$ultima_pag; $i++)
		  {
			if ($i == $pag)
			{
			  $paginacao .= '<li class="active"><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag atual">'.$i.'<span class="sr-only">(current)</span></a></li>';        
			} else {
			  $paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag">'.$i.'</a></li>';
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
				$paginacao .= '<li class="active"><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag atual">'.$i.'<span class="sr-only">(current)</span></a></li>';        
			  } else {
				$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag">'.$i.'</a></li>';  
			  }
			}
			$paginacao .= '<li>...</li>';
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$penultima.'/" class="btn_pag">'.$penultima.'</a></li>';
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$ultima_pag.'/" class="btn_pag">'.$ultima_pag.'</a></li>';
		  }
		  
		  elseif($pag > (2 * $adjacentes) && $pag < $ultima_pag - 3)
		  {
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/1/" class="btn_pag">1</a></li>';        
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/2/" class="btn_pag">2</a></li><li>...</li>';  
			for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<li class="active"><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag atual">'.$i.'<span class="sr-only">(current)</span></a></li>';        
			  } else {
				$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag">'.$i.'</a></li>';  
			  }
			}
			$paginacao .= '<li>...</li>';
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$penultima.'/" class="btn_pag">'.$penultima.'</a></li>';
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$ultima_pag.'/" class="btn_pag">'.$ultima_pag.'</a></li>';
		  }
		  else {
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/1/" class="btn_pag">1</a></li>';        
			$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/2/" class="btn_pag">2</a></li><li>...</li>';  
			for ($i = $ultima_pag - (2 * $adjacentes); $i <= $ultima_pag; $i++)
			{
			  if ($i == $pag)
			  {
				$paginacao .= '<li class="active"><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag atual">'.$i.'<span class="sr-only">(current)</span></a></li>';        
			  } else {
				$paginacao .= '<li><a href="'.$url.'/'.$parametrosbusca.'pag/'.$i.'/" class="btn_pag">'.$i.'</a></li>';  
			  }
			}
		  }
		}
		
		if ($prox <= $ultima_pag)
		{
		  $paginacao .= '
						    <li>
						      <a class="proxima" href="'.$url.'/'.$parametrosbusca.'pag/'.$prox.'/" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						      </a>
						    </li>';
		} else {
			$paginacao .= '
			  <li class="disabled">
			      <a class="proxima desativado " aria-label="Next">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
		      </li>
		      ';
		}
		  
		echo $paginacao."</ul>";
	} // fim paginaçao bootstrap

	public static function realBR($moeda) {
		$realBR = number_format($moeda,2,",",".");
		return $realBR;

	}


	public static function extensoReais($valor=0, $maiusculas=false) {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
                // retira o ponto de milhar, se tiver
                $valor = str_replace(".", "", $valor);

                // troca a virgula decimal por ponto decimal
                $valor = str_replace(",", ".", $valor);
        }
        $singular = array("centavo", "real", "mil", "milh&atilde;o", "bilh&atilde;o", "trilh&atilde;o", "quatrilh&atilde;o");
        $plural = array("centavos", "reais", "mil", "milh&otilde;es", "bilh&otilde;es", "trilh&otilde;es",
                "quatrilh&otilde;es");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
                "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
                "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
                "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "tr&ecirc;s", "quatro", "cinco", "seis",
                "sete", "oito", "nove");

        $z = 0;



        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
                for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
                $valor = $inteiro[$i];
                $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
                $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
                $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

                $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                        $ru) ? " e " : "") . $ru;
                $t = $cont - 1 - $i;
                $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000"

                )$z++; elseif ($z > 0)
                $z--;
                if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
                return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
                return (strtoupper($rt) ? strtoupper($rt) : "Zero");
        } else {
                return (ucwords($rt) ? ucwords($rt) : "Zero");
        }
        }

	public static function extenso($valor=0, $maiusculas=false) {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
                // retira o ponto de milhar, se tiver
                $valor = str_replace(".", "", $valor);

                // troca a virgula decimal por ponto decimal
                $valor = str_replace(",", ".", $valor);
        }
        $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões",
                "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
                "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
                "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
                "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
                "sete", "oito", "nove");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        for ($i = 0; $i < $cont; $i++)
                for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) {
                $valor = $inteiro[$i];
                $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
                $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
                $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

                $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                        $ru) ? " e " : "") . $ru;
                $t = $cont - 1 - $i;
                $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000"

                )$z++; elseif ($z > 0)
                $z--;
                if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
                if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
                return($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
                return (strtoupper($rt) ? strtoupper($rt) : "Zero");
        } else {
                return (ucwords($rt) ? ucwords($rt) : "Zero");
        }
        }




		//Esta função é utilizada pela função ordinal.
		//Retorna um número com n zeros à esquerda (string).
		public static function strzero($valor, $tamanho)
		   {
		   settype($valor, "string");
		   $valor = str_pad("0", $tamanho-strlen($valor)).$valor;
		   
		   return $valor;
		   }

		//Os parâmetros da função ordinal:
		//numero - É o número que você quer transformar em ordinal
		//genero - um string contendo "a" ou "o" para definir o gênero.
		//maiusculas - true para primeiras letras em caixa alta.
		//
		//A construção dos ordinais eu fiz segundo explicação do Vestibuol.

		public static function ordinal($numero, $genero, $maiusculas = false)
		  {
		  $numero = Funcoes::strzero($numero, 20);
		  
		  $elementos[1] = Array("", "primeir", "segund", "terceir", "quart", "quint", "sext", "sétim", "oitav", "non");
		  $elementos[2] = Array("", "décim", "vigésim", "trigésim", "quadragésim", "quinquagésim", "sexagésim", "septuagésim", "octogésim", "nonagésim");
		  $elementos[3] = Array("", "centésim", "ducentésim", "trecentésim", "quadringentésim", "quingentésim", "seiscentésim", "septingentésim", "octingentésim", "nongentésim");
		  $elementos[4] = "milésim";
		  $elementos[7] = "milhonésim";
		  $elementos[10] = "bilhonésim";
		  $elementos[13] = "trilhonésim";
		  
		  $controle = 3;
		  $ordinal = "";
		  $soma = 0;
		  
		  for ($c = 5; $c <= 19; $c++)
		     {
		     $num = substr($numero, $c, 1);
		     settype($num, "integer");
		     
		     if ($num <> 0 && ($num > 1 || $c > 16))
		        {
		        $temp_ord = $elementos[$controle][$num];
		              
		        if ($maiusculas)
		           $temp_ord = strtoupper(substr($temp_ord,0,1)).substr($temp_ord,1,strlen($temp_ord)-1);
		        
		        $ordinal = $ordinal." ".$temp_ord.$genero;
		        
		        $soma+= $num*10^$controle;
		        }
		     else if ($num <> 0)
		        {
		        $soma+= $num*10^$controle;           
		        }
		     
		   
		     $controle--;
		     
		     if ($controle == 0 && $c < 19)
		        {
		        if ($soma > 1 && isset($elementos[20-$c]))
		           { 
		           $temp_ord = $elementos[20-$c];
		              
		           if ($maiusculas)
		              $temp_ord = strtoupper(substr($temp_ord,0,1)).substr($temp_ord,1,strlen($temp_ord)-1);
		        
		           $ordinal = $ordinal." ".$temp_ord.$genero;
		           }
		           
		        $controle = 3;
		        $soma = 0;
		        }
		     }   
		  return $ordinal;
		  }


		  public static function NotificaEmail($emailSend,$typeMail,$transacao,$place){
		  	// EMAIL = email de envio
		  	// TIPOS = 1 - cadastro | 2 - recupera senha | 3 - nova compra | 4 - alteraçao na compra
		  	// TRANSACAO = id da transacao alterada
		  	// PLACE = caminho do include do phpmailer

		  	$transacao = intval($transacao);
		  	$typeMail = intval($typeMail);

                        // define quem envia e quem recebe
                        $destinatarios = $emailSend;
                        $nomeRemetente = NOME_SITE;
                        $usuario = USUARIO_EMAIL_AUTENTICADO; // email que envia
                        $senha = SENHA_EMAIL_AUTENTICADO; // senha do email que envia

				        $user = CRUD::SELECT('','clientes',"emailc=:emailc", array( 'emailc'=>md5($emailSend) ) ,'');
				        $totalUser = sizeof($user);
				        if ($totalUser==0) { //  NAO ENCONTROU NENHUM USUARIO COM ESTE EMAIL
				        	return false;
				        } else { // USUÁRIO ENCONTRADO $user
                        // define o tipo do email
								if ($typeMail==1) { // cadastro
									$subject = "AGT Turismo - Seja bem vindo à AGT";
						        	$mensagem = '<html>
													<body style="background:#fff; text-align:center; " >
															<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#25201c">
															<table width="600" align="center" style="background:#fff; text-align:center; ">
																<thead>
																	<tr>
																		<td colspan="2"><img src="'.SITE_URL.'img/mail-top.jpg" width="600" height="194" alt="" ></td>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td colspan="2">Olá, <b>'.$user['0']['nome'].'</b>. Seja bem vindo à AGT Turismo.</td>
																	</tr>
																	<tr>
																		<td colspan="2">Em nosso sistema você pode comprar passagens rodoviárias para diversos destinos, com a segurança e qualidade AGT Turismo que você já deve conhecer.</td>
																	</tr>
																	<tr>
																		<td colspan="2">Segue abaixo os sesus dados de acesso:</td>
																	</tr>
																	
																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>

																	<tr>
																		<td style="text-align:right"><b>E-mail:</b></td>
																		<td style="text-align:left">
																			'.$user['0']['email'].'<br><br>
																		</td>
																	</tr>
																	<tr>
																		<td style="text-align:right"><b>Senha:</b></td>
																		<td style="text-align:left">
																			'.$user['0']['senha'].'<br><br>
																		</td>
																	</tr>
																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="2">
																			Obrigado por se cadastrar!<br>
																			Aproveite nossas ofertas.<br>
																			Att. Equipe AGT Turismo.
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2">
																			<a href="www.agtturismo.com.br" target="_blank" style="color:#3b366b;">www.agtturismo.com.br</a>
																		</td>
																	</tr>
																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>
																</tfoot>
															</table>
															</font>
													</body>
												</html>';
								} // FIM DO TIPO 1 - cadastro

								if ($typeMail==2) { // 2 - recupera senha
									$subject = "AGT Turismo - Recuperar senha";
						        	$mensagem = '<html>
													<body style="background:#fff; text-align:center; " >
															<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#25201c">
															<table width="600" align="center" style="background:#fff; text-align:center; ">
																<thead>
																	<tr>
																		<td colspan="2"><img src="'.SITE_URL.'img/mail-top.jpg" width="600" height="194" alt="" ></td>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td colspan="2">Olá, <b>'.$user['0']['nome'].'</b>.</td>
																	</tr>
																	<tr>
																		<td colspan="2">Segue abaixo os sesus dados de acesso:</td>
																	</tr>

																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>

																	<tr>
																		<td style="text-align:right"><b>E-mail:</b></td>
																		<td style="text-align:left">
																			'.$user['0']['email'].'<br><br>
																		</td>
																	</tr>
																	<tr>
																		<td style="text-align:right"><b>Senha:</b></td>
																		<td style="text-align:left">
																			'.$user['0']['senha'].'<br><br>
																		</td>
																	</tr>
																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>
																</tbody>
																<tfoot>
																	<tr>
																		<td colspan="2">
																			Obrigado por comprar conosco!<br>
																			Aproveite nossas ofertas.<br>
																			Att. Equipe AGT Turismo.
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2">
																			<a href="www.agtturismo.com.br" target="_blank" style="color:#3b366b;">www.agtturismo.com.br</a>
																		</td>
																	</tr>
																	<tr>
																		<td height="25" colspan="2"></td>
																	</tr>
																</tfoot>
															</table>
															</font>
													</body>
												</html>';
								} // FIM DO TIPO 2 - recupera senha

								if ($transacao!=0) { // SÓ EXECUTA COM TRANSACAO VALIDA
										$ps = CRUD::SELECT('*, viagens_lista.id as id, viagens.id as idV, viagens_lista.valor as ValorFinal', "viagens_lista LEFT JOIN viagens ON viagens_lista.viagem=viagens.id LEFT JOIN pacotes ON viagens.destino=pacotes.id", 'viagens_lista.id=:idV', array('idV'=>$transacao), 'order by viagens_lista.datadecompra desc'); 
										$totalPed = sizeof($ps);

										// verifica se tem o pedido no sistema
										if ($totalPed!=0) {

                                            $onibus = $ps["0"]["onibus"]!=0 ? $ps["0"]["onibus"] : "-";
                                            if ($ps["0"]["onibus"]!=0) {
                                                $Dbus = CRUD::SELECT_ID('','frota',$ps["0"]["onibus"]);
                                                $onibus = $Dbus['numordem'];
                                            }

		                                    $saida = strftime("%d", strtotime($ps["0"]['datasaida']))."/".strftime("%m", strtotime($ps["0"]['datasaida']))."/".strftime("%Y", strtotime($ps["0"]['datasaida']))." | ".substr($ps["0"]['datasaida'], -9,-3);
		                                    $retorno = strftime("%d", strtotime($ps["0"]['datasaida']))."/".strftime("%m", strtotime($ps["0"]['datasaida']))."/".strftime("%Y", strtotime($ps["0"]['datasaida']))." | ".substr($ps["0"]['datasaida'], -9,-3);
		                                    $dataCompra = strftime("%d", strtotime($ps["0"]['datadecompra']))."/".strftime("%m", strtotime($ps["0"]['datadecompra']))."/".strftime("%Y", strtotime($ps["0"]['datadecompra']))." | ".substr($ps["0"]['datadecompra'], -9,-3);

												if ($typeMail==3) { // 3 - nova compra
													$subject = "AGT Turismo - Pagamento iniciado do pedido ".$transacao;
										        	$mensagem = '<html>
																	<body style="background:#fff; text-align:center; " >
																			<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#25201c">
																			<table width="600" align="center" style="background:#fff; text-align:center; ">
																				<thead>
																					<tr>
																						<td colspan="2"><img src="'.SITE_URL.'img/mail-top.jpg" width="600" height="194" alt="" ></td>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td colspan="2">Olá, <b>'.$user['0']['nome'].'</b>. Seu pedido de número '.$ps['0']['id'].' foi iniciado e estamos aguardando o pagamento.</td>
																					</tr>
																					<tr>
																						<td colspan="2">Segue abaixo as informações do seu pedido:</td>
																					</tr>

																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>

																						<tr>
																							<td><b>Viagem:</b></td>
																							<td>
																								'.$ps['0']['idV'].' - '.$ps['0']['titulo'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Ônibus:</b></td>
																							<td>
																								'.$onibus.'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Poltrona de ida:</b></td>
																							<td>
																								'.$ps['0']['poltronaIda'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Poltrona de volta:</b></td>
																							<td>
																								'.$ps['0']['poltronaVolta'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Valor:</b></td>
																							<td>
																								R$ '.number_format(trim($ps['0']['ValorFinal']),2,',','.').'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Saída:</b></td>
																							<td>
																								'.$saida.'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Retorno:</b></td>
																							<td>
																								'.$retorno.'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Forma de pagamento:</b></td>
																							<td>
																								'.$ps['0']['pagamento'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Status de pagamento:</b></td>
																							<td>
																								'.$ps['0']['statustxt_pagamento'].'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Data de compra:</b></td>
																							<td>
																								'.$dataCompra.'<br><br>
																							</td>
																						</tr>

																					<tr>
																						<td colspan="2">Se você ainda não pagou pode acessar à área do cliente no site, escolher o pedido, clicar em "Ver detalhes" e depois em "Pagar viagme".</td>
																					</tr>

																					<tr>
																						<td colspan="2">Caso já tenha efetuado o pagamento aguarde até que o sistema confirme a transação e logo você receberá um e-mail com a confirmação do pagamento.</td>
																					</tr>

																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>
																				</tbody>
																				<tfoot>
																					<tr>
																						<td colspan="2">
																							Obrigado por comprar conosco!<br>
																							Aproveite nossas ofertas.<br>
																							Att. Equipe AGT Turismo.
																						</td>
																					</tr>
																					<tr>
																						<td colspan="2">
																							<a href="www.agtturismo.com.br" target="_blank" style="color:#3b366b;">www.agtturismo.com.br</a>
																						</td>
																					</tr>
																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>
																				</tfoot>
																			</table>
																			</font>
																	</body>
																</html>';
												} // FIM DO TIPO 3 - nova compra

												if ($typeMail==4) { // 4 - alteraçao na compra
													$subject = "AGT Turismo - O Status do pedido ".$transacao." foi alterado - ".$ps['0']['statustxt_pagamento'];
										        	$mensagem = '<html>
																	<body style="background:#fff; text-align:center; " >
																			<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#25201c">
																			<table width="600" align="center" style="background:#fff; text-align:center; ">
																				<thead>
																					<tr>
																						<td colspan="2"><img src="'.SITE_URL.'img/mail-top.jpg" width="600" height="194" alt="" ></td>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td colspan="2">Olá, <b>'.$user['0']['nome'].'</b>. O status do pedido '.$ps['0']['id'].' foi alterado para '.$ps['0']['statustxt_pagamento'].'.</td>
																					</tr>
																					<tr>
																						<td colspan="2">Segue abaixo as informações do seu pedido:</td>
																					</tr>

																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>

																						<tr>
																							<td><b>Viagem:</b></td>
																							<td>
																								'.$ps['0']['idV'].' - '.$ps['0']['titulo'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Ônibus:</b></td>
																							<td>
																								'.$onibus.'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Poltrona de ida:</b></td>
																							<td>
																								'.$ps['0']['poltronaIda'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Poltrona de volta:</b></td>
																							<td>
																								'.$ps['0']['poltronaVolta'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Valor:</b></td>
																							<td>
																								R$ '.number_format(trim($ps['0']['ValorFinal']),2,',','.').'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Saída:</b></td>
																							<td>
																								'.$saida.'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Retorno:</b></td>
																							<td>
																								'.$retorno.'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Forma de pagamento:</b></td>
																							<td>
																								'.$ps['0']['pagamento'].'<br><br>
																							</td>
																						</tr>
																						<tr>
																							<td><b>Status de pagamento:</b></td>
																							<td>
																								'.$ps['0']['statustxt_pagamento'].'<br><br>
																							</td>
																						</tr>

																						<tr>
																							<td><b>Data de compra:</b></td>
																							<td>
																								'.$dataCompra.'<br><br>
																							</td>
																						</tr>

																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>
																				</tbody>
																				<tfoot>
																					<tr>
																						<td colspan="2">
																							Obrigado por comprar conosco!<br>
																							Aproveite nossas ofertas.<br>
																							Att. Equipe AGT Turismo.
																						</td>
																					</tr>
																					<tr>
																						<td colspan="2">
																							<a href="www.agtturismo.com.br" target="_blank" style="color:#3b366b;">www.agtturismo.com.br</a>
																						</td>
																					</tr>
																					<tr>
																						<td height="25" colspan="2"></td>
																					</tr>
																				</tfoot>
																			</table>
																			</font>
																	</body>
																</html>';
												} // FIM DO TIPO 4 - alteraçao na compra
										}
								}

	                        /*********************************** A PARTIR DAQUI NAO ALTERAR ************************************/

	                        include ($place."class.phpmailer.php");

	                        $To = $destinatarios;
	                        $Subject = sprintf('=?%s?%s?%s?=', 'UTF-8', 'B', base64_encode($subject));

	                        $Message = $mensagem;
	                        $Host = 'mail.'.substr(strstr($usuario, '@'), 1);
	                        $Username = $usuario;
	                        $Password = $senha;
	                        $Port = "587";

	                        $mail = new PHPMailer();
	                        $body = $Message;
	                        $mail->IsSMTP(); // telling the class to use SMTP
	                        $mail->Host = $Host; // SMTP server
	                        $mail->SMTPDebug = 1; // enables SMTP debug information (for testing)
	                        // 1 = errors and messages
	                        // 2 = messages only
	                        $mail->SMTPAuth = true; // enable SMTP authentication
	                        $mail->Port = $Port; // set the SMTP port for the service server
	                        $mail->Username = $Username; // account username
	                        $mail->Password = $Password; // account password

	                        $mail->SetFrom($usuario, $nomeRemetente);
	                        $mail->Subject = $Subject;
	                        $mail->MsgHTML($body);
	                        $mail->AddAddress($To, "");

	                        if($mail->Send()){
	                            return "ok";
	                        }  else {
	                            return $mail->ErrorInfo;
	                        } 
				        }
		  }
}

?>
