<?php
/**
 * Description of Status
 * Get Status based on date (inizio and fine)
 * 
 * @author gullo
 */
class Model_Ordini_Status {
    
    const STATUS_PIANIFICATO = "Pianificato";
    const STATUS_APERTO = "Aperto";
    const STATUS_CHIUSO = "Chiuso";
    const STATUS_ARCHIVIATO = "Archiviato";
    
    private $_inizio;
    private $_fine;
    private $_archiviato;
    
    function __construct($o) {
        $this->_inizio = $o->data_inizio;
        $this->_fine = $o->data_fine;
        $this->_archiviato = $o->archiviato;
    }
    
    function getStatus() {
        
        if($this->_archiviato != "S") {
            $startObj = new Zend_Date($this->_inizio, "y-MM-dd HH:mm:ss");
            $endObj = new Zend_Date($this->_fine, "y-MM-dd HH:mm:ss");
            
            $timestampNow = Zend_Date::now()->toString("U");
            
            if( $timestampNow < $startObj->toString("U") ) {
                return self::STATUS_PIANIFICATO;
                
            } else if(
                $timestampNow >= $startObj->toString("U") &&
                $timestampNow <= $endObj->toString("U")
                    ) {
                return self::STATUS_APERTO;
                
            } else if( $timestampNow > $endObj->toString("U") ) {
                return self::STATUS_CHIUSO;
            }
            
        } else {
            return self::STATUS_ARCHIVIATO;
        }
    }

    
    /*
    VERIFICHE STATI
 */   
    function is_Pianificato() {
        return ($this->getStatus() == self::STATUS_PIANIFICATO);
    }
    function is_Aperto() {
        return ($this->getStatus() == self::STATUS_APERTO);
    }
    function is_Chiuso() {
        return ($this->getStatus() == self::STATUS_CHIUSO);
    }
    function is_Archiviato() {
        return ($this->getStatus() == self::STATUS_ARCHIVIATO);
    }
    

    /*
    PERMESSI STATI
 */   

    function can_ModificaProdotti() {
        return ( !$this->is_Archiviato() ) ? true : false;
    }
    

/* ***************************
 *  MISCELLANEOUS
 *************************** */
    
    // get simple array with all status
    static function getArrayStatus() {
        return array(
            self::STATUS_PIANIFICATO,
            self::STATUS_APERTO,
            self::STATUS_CHIUSO,
            self::STATUS_ARCHIVIATO
        );
    }
        

    
    
    // TODO: DA SISTEMARE!
    // Lo so, è orrendo. Piazzato qui è un morto!
    // Ma la logica per identificare lo Stato lasciamola per tutti qui dentro!
    static function getSqlFilterByStato($stato) {
        switch ($stato)
        {
            case self::STATUS_PIANIFICATO:
                $sql = " AND NOW() < o.data_inizio AND o.archiviato='N'";
                break;

            case self::STATUS_APERTO:
                $sql = " AND NOW() >= o.data_inizio AND NOW() <= o.data_fine AND o.archiviato='N'";
                break;
            
            case self::STATUS_CHIUSO:
                $sql = " AND NOW() > o.data_fine AND o.archiviato='N'";
                break;

            case self::STATUS_ARCHIVIATO:
                $sql = " AND o.archiviato='S' ";
                break;

        }
        return $sql; 
    }
    
}