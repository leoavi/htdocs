<?php
include_once('../../model/armazenagem/minhasCargasDescargasModel.php');

class minhasCargasDescargasController extends minhasCargasDescargasModel
{

    private $conexao;

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    private function loadByQuery($query)
    {
        $this->setHandle($query['HANDLE']);
        $this->setStatus($query['STATUS']);
        $this->setStatusNome($query['STATUSNOME']);
        $this->setstatusImagem($query['STATUS'],$query['STATUSNOME']);
        $this->setNumero($query['NUMEROCARREGAMENTO']);
        $this->setFilial($query['FILIAL']);
        $this->setTipo($query['TIPO']);
        $this->setProcesso($query['PROCESSO']);
        $this->setData(date('d/m/Y', strtotime($query['DATA'])));
        $this->setCliente($query['CLIENTE']);
        $this->setTransportadora($query['TRANSPORTADORA']);
        $this->setNrControle($query['NUMEROCONTROLE']);
        $this->setNrOrdem($query['NUMEROORDEM']);
        $this->setNrPedido($query['NUMEROPEDIDO']);
        $this->setQuantidade(number_format($query['QUANTIDADE']));
        $this->setVolume($query['VOLUME']);
        $this->setPesoBruto(number_format($query['PESOBRUTO'], 4, ',', '.'));
        $this->setPesoLiquido(number_format($query['PESOLIQUIDO'], 4, ',', '.'));
        
    }

    private function getTableRow()
    {
        return "<tr>
                    <td hidden='true'><input type='radio' name='check[]' class='check' hidden='true' id='check'  value='{$this->getHandle()}'>
                    <td width='1%'>{$this->getStatusImagem()}</td> 
                    <td>{$this->getNumero()}</td>
                    <td>{$this->getFilial()}</td>
                    <td>{$this->getNrPedido()}</td>
                    <td>{$this->getNrControle()}</td>    
                    <td>{$this->getNrOdem()}</td>  
                    <td>{$this->getTipo()}</td>
                    <td>{$this->getProcesso()}</td>                    
                    <td>{$this->getData()}</td>    
                    <td>{$this->getCliente()}</td>    
                    <td>{$this->getTransportadora()}</td>    
                    <td class=\"text-right\">{$this->getQuantidade()}</td>                    
                    <td>{$this->getVolume()}</td>
                    <td class=\"text-right\">{$this->getPesoBruto()}</td>
                    <td class=\"text-right\">{$this->getPesoLiquido()}</td>
                </tr>";
    }

    public function getRegistro($filtro = '')
    {
        $result = Array('ERRO' => false,
            'DADOS' => null);
        try {
            $query = $this->conexao->prepare($this->getQuery($filtro));
            $query->execute();

            $result['DADOS'] = $this->processaRegistro($query);
        } catch (Exception $e) {
            $result['ERRO'] = true;
            $result['DADOS'] = Array("<div class=\"alert alert-warning\">{$e->getMessage()}</div>");
        }

        return $result;
    }

    private function processaRegistro($query)
    {
        $result = Array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $this->loadByQuery($row);

            $result[] = $this->getTableRow();
        }
        return $result;
    }

    public function montaFiltro()
    {
        $this->montaFiltroTipo();
        $this->montaFiltroCliente();
        $this->montaFiltroProcesso();
        $this->montaFiltroTransportadora();
    }

    private function montaFiltroTipo()
    {
        $this->filtro['TIPO'] = Array();
        $query = $this->conexao->prepare($this->getQueryFiltroTipo());
        $query->execute();
        if ($query) {
            $this->filtro['TIPO'] = $query->fetchAll();
        }
    }

    private function montaFiltroCliente()
    {
        $this->filtro['CLIENTE'] = Array();
        $query = $this->conexao->prepare($this->getQueryFiltroCliente());
        $query->execute();
        if ($query) {
            $this->filtro['CLIENTE'] = $query->fetchAll();;
        }
    }

    private function montaFiltroTransportadora()
    {
        $this->filtro['TRANSPORTADORA'] = Array();
        $query = $this->conexao->prepare($this->getQueryFiltroTransportadora());
        $query->execute();
        if($query){
            $this->filtro['TRANSPORTADORA'] = $query->fetchAll();
        }        
    }

    private function montaFiltroProcesso()
    {
        $this->filtro['PROCESSO'] = Array();
        $query = $this->conexao->prepare($this->getQueryFiltroProcesso());
        $query->execute();
        if ($query) {
            $this->filtro['PROCESSO'] = $query->fetchAll();
        }
    }

}