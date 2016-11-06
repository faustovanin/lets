<?php
    /**
     * File: Medida.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Medidas do cliente
     * Release: Dezembro/2009
    **/
    
    class Medida {
	/**
	 * @property String busto
	 * @property String comprimentoBusto
	 * @property String ombro
	 * @property String cintura
	 * @property String quadril
	 * @property String comprimento
	 * @property String alturaGancho
	 * @property String boca
	 * @property String manga
	 * @property String bocaManga
	**/
	private $busto;
	private $comprimentoBusto;
	private $ombro;
	private $cintura;
	private $quadril;
	private $comprimento;
	private $alturaGancho;
	private $boca;
	private $manga;
	private $bocaManga;
	
	/**
	 * @method <<constructor>> __construct Construtor da classe
	**/
	public function __construct(){
	    
	}
	
	/**
	 * @method void setBusto
	 * @param String busto Busto
	**/
	public function setBusto($busto) {
	    $this->busto = $busto;
	}
	
	/**
	 * @method void setComprimentoBusto
	 * @param String comprimentoBusto
	**/
	public function setComprimentoBusto($comprimentoBusto) {
	    $this->comprimentoBusto = $comprimentoBusto;
	}
	
	/**
	 * @method void setOmbro
	 * @param String ombro Ombro
	**/
	public function setOmbro($ombro) {
	    $this->ombro = $ombro;
	}
	
	/**
	 * @method void setCintura
	 * @param String cintura Cintura
	**/
	public function setCintura($cintura) {
	    $this->cintura = $cintura;
	}
	
	/**
	 * @method void setQuadril
	 * @param String quadril Quadril
	**/
	public function setQuadril($quadril) {
	    $this->quadril = $quadril;
	}
	
	/**
	 * @method void setComprimento
	 * @param String comprimento Comprimento
	**/
	public function setComprimento($comprimento) {
	    $this->comprimento = $comprimento;
	}
	
	/**
	 * @method void setAlturaGancho
	 * @param String alturaGancho Altura do gancho
	**/
	public function setAlturaGancho($alturaGancho) {
	    $this->alturaGancho = $alturaGancho;
	}
	
	/**
	 * @method void setBoca
	 * @param String boca Boca
	**/
	public function setBoca($boca) {
	    $this->boca = $boca;
	}
	
	/**
	 * @method void setManga
	 * @param String manga Manga
	**/
	public function setManga($manga) {
	    $this->manga = $manga;
	}
	
	/**
	 * @method void setBocaManga
	 * @param String bocaManga Boca da manga
	**/
	public function setBocaManga($bocaManga) {
	    $this->bocaManga = $bocaManga;
	}
	
	/**
	 * @method String getBusto
	 * @return Busto
	**/
	public function getBusto() {
	    return $this->busto;
	}
	
	/**
	 * @method String getComprimentoBusto
	 * @return Comprimento do busto
	**/
	public function getComprimentoBusto() {
	    return $this->comprimentoBusto;
	}
	
	/**
	 * @method String getOmbro
	 * @return Ombro
	**/
	public function getOmbro() {
	    return $this->ombro;
	}
	
	/**
	 * @method String getCintura
	 * @return Cintura
	 **/
	public function getCintura() {
	    return $this->cintura;
	}
	
	/**
	 * @method String getQuadril
	 * @return Quadril
	**/
	public function getQuadril() {
	    return $this->quadril;
	}
	
	/**
	 * @method String getComprimento
	 * @return Comprimento
	**/
	public function getComprimento() {
	    return $this->comprimento;
	}
	
	/**
	 * @method String getAlturaGancho
	 * @return Altura do gancho
	**/
	public function getAlturaGancho() {
	    return $this->alturaGancho;
	}
	
	/**
	 * @method String getBoca
	 * @return Boca
	**/
	public function getBoca() {
	    return $this->boca;
	}
	
	/**
	 * @method String getManga
	 * @return Manga
	**/
	public function getManga() {
	    return $this->manga;
	}
	
	/**
	 * @method String getBocaManga
	 * @return Boca da manga
	**/
	public function getBocaManga() {
	    return $this->bocaManga;
	}
    }
?>