<?php include $this->template('gestioneordini/gestione-header.tpl.php'); ?>

<?php 
    switch ($this->tipo) {
        case "totali":
            echo $this->partial('gestioneordini/dettaglio.totali.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;

        case "utenti":
            echo $this->partial('gestioneordini/dettaglio.utenti.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;
        
        case "prodotti":
            echo $this->partial('gestioneordini/dettaglio.prodotti.tpl.php', array('ordCalcObj' => $this->ordCalcObj));
            break;
        
        default:
        break;
    } 
?>    