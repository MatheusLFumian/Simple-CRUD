<?
if(!isset($_SESSION)) session_start();
header('Content-Type: text/html; charset=utf-8');
if (!class_exists('Conexao')) {

    if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='servidor' ) {

        DEFINE('DB_HOST','localhost');
        DEFINE('DB_USER','root');
        DEFINE('DB_PASS','');
        DEFINE('DB_TABLE','etapa1');
        DEFINE('SITE_URL','http://localhost/h4money-etapa1/');
        // DEFINE('GERENCIE_LIB', 'http://localhost/gerencielib/');
    } else {
        define('DB_HOST','definir host');
        define('DB_USER','admin');
        define('DB_PASS','admin');
        define('DB_TABLE','etapa1');
        define('SITE_URL','definir domínio do sistema');
        // DEFINE('GERENCIE_LIB', 'http://www.agenciasingular.com.br/gerencielib/');
    }

//print DB_HOST." ".DB_USER." ".DB_PASS." ".DB_TABLE." ".SITE_URL;

    //DEFINIR NOME DA SESSÃO
    // DEFINE('NOME_SESSAO', 'login_gerenciemeta');

    DEFINE('NOME_SITE', utf8_encode(' - H4 Money'));

    // DEFINE('EMAIL_GERENCIE', 'gerencie@agenciasingular.com.br'); // email da singular
    // DEFINE('USUARIO_EMAIL_AUTENTICADO', 'noreply@agtturismo.com.br'); // DEVE CRIAR UM EMAIL PARA ENVIO
    // DEFINE('SENHA_EMAIL_AUTENTICADO', 'agtSingTur2015Success'); // DEVE CRIAR UM EMAIL PARA ENVIO


    class Conexao extends PDO {

        private static $instancia;

        public function __construct($dsn, $username = "", $password = "") {
            // O construtro abaixo é o do PDO
            parent::__construct($dsn, $username, $password);
        }

        public static function getInstance() {
            if(!isset( self::$instancia )){
                try {
                    self::$instancia = new Conexao("mysql:host=".DB_HOST.";dbname=".DB_TABLE, DB_USER , DB_PASS, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true ));

                } catch ( Exception $e ) {
                    echo 'Erro ao conectar';
                    exit ();
                }
            }
            return self::$instancia;
        }
    }

    $DB = Conexao::getInstance();
    $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
    $a->closeCursor();

    date_default_timezone_set('America/Sao_paulo');
    setlocale(LC_ALL, "pt_BR");

    /*
    function __autoload($classe) {
        require_once "/gerencie/includes/$classe/". $classe . '.php';
    }
    */

    /* ============= CRUD ==================*/
    include('includes/Funcoes/CRUD.php');
    include('includes/Funcoes/Funcoes.php');
}
?>