<? 
class CRUD {

    /*
     ---------------------------------------------------- Exemplo de uso:------------------------------------------------------
    //---SELECT
	$meuResultado = CRUD::SELECT('nome, email, telefone', 'gerencie_login', 'dataCadastro=:dataCadastro AND nome like=:nome', array('dataCadastro'=>'2014-01-28', 'nome'=>'%nome%'), 'order by nome desc');
	foreach ($meuResultado as $key => $value) { echo $value['nome'];}

	//---SELECT_ID
	$meuResultado = CRUD::SELECT_ID('nome, email, telefone', 'gerencie_login', 21);
	echo $meuResultado['nome'];

	//---INSERT
	if (CRUD::INSERT('gerencie_login', array('nome'=>'Marcela', 'login'=>'mbomfim', 'redefinirSenha'=>1))) echo 'Inserido com sucesso';

	//---UPDATE
	if(CRUD::UPDATE('gerencie_login', array('nome'=>'Marcelaaa', 'login'=>'mbomfimmm', 'redefinirSenha'=>0),21)) echo 'Editado com sucesso';

	//---DELETE
	if(CRUD::DELETE('gerencie_login',21)) echo 'Excluido com sucesso';

    */
    
    public static function SELECT($campos, $tabela, $where, $parametros, $extras){
        
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();

        $query = "SELECT ".($campos!=''?$campos:"*")." FROM  $tabela ".($where!=''?"WHERE 1=1 AND $where":"")." $extras";
        $sql = $DB->prepare($query);

        if($parametros!=''){
            foreach ($parametros as $key => &$val) {
                if (is_int($val)) $sql->bindValue(':'.$key, $val, PDO::PARAM_INT);
                else $sql->bindValue(':'.$key, $val, PDO::PARAM_STR);
            }
        }

        if ($sql->execute()) {
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
            //Retira barras
            foreach ($res as $key=>$value) {
                foreach ($value as $Vkey=>$Vvalue) {
                    $value[$Vkey] = stripslashes($Vvalue);
                }
                $res[$key] = $value;
            }
            return $res;
        } else {
            return $sql->errorInfo();
        }
    }

    public static function SELECT_ID($campos, $tabela, $id){
        
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();

        $query = "SELECT ".($campos!=''?$campos:"*")." FROM $tabela WHERE id=:id";
        $sql = $DB->prepare($query);

        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            $res = $sql->fetchAll(PDO::FETCH_ASSOC);
            //Retira barras
            foreach ($res as $key=>$value) {
                foreach ($value as $Vkey=>$Vvalue) {
                    $value[$Vkey] = stripslashes($Vvalue);
                }
                $res = $value;
            }
            return $res;
        } else {
            return $sql->errorInfo();
        }
    }

    /* 
    ================= OS VALORES RECEIDOS DEVEM SER DO TIPO: ======================
    $parametros = array('campo1'=>'valorcampo1', 'campo2'=>'valorcampo2', 'campoInteiro'=>valorInteiro); 
    Ex.:    $parametros = array('nome'=>'Marcela', 'id'=>115);
    */
    public static function INSERT($tabela, $parametros){
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();
            
        $colunas = ''; $values = '';
        foreach ($parametros as $key => &$val) {
            $colunas .= ($colunas==''?'':', ').$key;
            $values .= ($values==''?':':', :').$key;
        }
        
        $query = "INSERT INTO $tabela ($colunas) VALUES($values)";
        $sql = $DB->prepare($query);

        foreach ($parametros as $key => &$val) {
            if (is_int($val)) $sql->bindValue(':'.$key, $val, PDO::PARAM_INT);
                else $sql->bindValue(':'.$key, addslashes($val), PDO::PARAM_STR); //Adciona barras
        }

        if ($sql->execute()) return true;
        else return $sql->errorInfo();
    }

    public static function INSERT_ID($tabela, $parametros){
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();
            
        $colunas = ''; $values = '';
        foreach ($parametros as $key => &$val) {
            $colunas .= ($colunas==''?'':', ').$key;
            $values .= ($values==''?':':', :').$key;
        }
        
        $query = "INSERT INTO $tabela ($colunas) VALUES($values)";
        $sql = $DB->prepare($query);

        foreach ($parametros as $key => &$val) {
            if (is_int($val)) $sql->bindValue(':'.$key, $val, PDO::PARAM_INT);
                else $sql->bindValue(':'.$key, addslashes($val), PDO::PARAM_STR); //Adciona barras
        }

        if ($sql->execute()) return $DB->lastInsertId();
        else return $sql->errorInfo();
    }

    public static function UPDATE($tabela, $parametros, $id){
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();

        $params = ''; 
        foreach ($parametros as $key => &$val) {
            $params .= ($params==''?'':', ')."$key=:$key";
        }
        
        $query = "UPDATE $tabela SET $params WHERE id=:id";
        $sql = $DB->prepare($query);

        foreach ($parametros as $key => &$val) {
            if (is_int($val)) $sql->bindValue(':'.$key, $val, PDO::PARAM_INT);
                else $sql->bindValue(':'.$key, addslashes($val), PDO::PARAM_STR); //Adciona barras
        }

        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        if ($sql->execute()) return true;
        else return $sql->errorInfo();
    }

    public static function DELETE($tabela, $id, $nome_chave_primaria='id'){
        $DB = Conexao::getInstance();
        $a = $DB->query("SET NAMES utf8;SET character_set_connection=utf8;SET character_set_client=utf8;SET character_set_results=utf8;SET time_zone='-3:00';");
        $a->closeCursor();
            
        $query = "DELETE from $tabela WHERE $nome_chave_primaria=:id";
        $sql = $DB->prepare($query);

        $sql->bindValue(':id', $id, PDO::PARAM_INT);

        if ($sql->execute()) return true;
        else return $sql->errorInfo();
    }
}
?>