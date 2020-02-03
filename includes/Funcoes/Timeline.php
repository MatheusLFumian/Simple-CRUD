<? 
class TIMELINE {
    public static function add($idU, $nomeU, $area, $acaoTL){
        CRUD::INSERT('gerencie_timeline', array('idUsuario'=>$idU, 'nomeUsuario'=>$nomeU, 'area'=>$area, 'acao'=>$acaoTL, 'data'=>date("Y-m-d H:i:s")));        
    }
}
$_SESSION['time']=time(); 
?>