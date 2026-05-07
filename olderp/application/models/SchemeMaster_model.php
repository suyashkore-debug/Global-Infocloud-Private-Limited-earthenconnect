<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SchemeMaster_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function GateList()
     { 
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $this->db->select(db_prefix() . 'schememaster.SchemeID,'.db_prefix() . 'schememaster.narration,'.db_prefix() . 'schememaster.file_name,'.db_prefix() . 'schememaster.TransDate,'.db_prefix() . 'schememaster.StartDate,'.db_prefix() . 'schememaster.EndDate,'.db_prefix() . 'customers_groups.name,'.db_prefix() . 'xx_statelist.state_name');
        $this->db->join(db_prefix() . 'customers_groups', '' . db_prefix() . 'customers_groups.id = ' . db_prefix() . 'schememaster.DistributorType AND ' . db_prefix() . 'customers_groups.PlantID = ' . db_prefix() . 'schememaster.PlantID ');
        $this->db->join(db_prefix() . 'xx_statelist', '' . db_prefix() . 'xx_statelist.short_name = ' . db_prefix() . 'schememaster.StateID');
        $this->db->from(db_prefix() . 'schememaster');
        $this->db->where(db_prefix() . 'schememaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'schememaster.FY', $FY);
        $this->db->order_by(db_prefix() . 'schememaster.SchemeID', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function GetItemList()
     { 
        $selected_company = $this->session->userdata('root_company');
        $this->db->select('*');
        $this->db->from(db_prefix() . 'items');
        $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
        $result = $this->db->get()->result_array();
        return $result;
     }
    public function GetItemDetailByItemID($ItemID,$State,$DistType,$FromDate,$ToDate)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'schemedetails.ItemID');
        $this->db->where(db_prefix() . 'schemedetails.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'schemedetails.FY', $FY);
        $this->db->where(db_prefix() . 'schemedetails.ItemID', $ItemID);
        $this->db->where(db_prefix() . 'schemedetails.StateID', $State);
        $this->db->where(db_prefix() . 'schemedetails.DistributorType', $DistType);
        $this->db->where(db_prefix() . 'schemedetails.EndDate >',$FromDate);
        $this->db->where(db_prefix() . 'schemedetails.ActYN',"Y");
        $this->db->from(db_prefix() . 'schemedetails');
        $data = $this->db->get()->result_array();
        if($data){
            return false;
        }else{
                $this->db->select(db_prefix() . 'items.item_code,'.db_prefix() . 'items.description,'.db_prefix() . 'items.case_qty,'.db_prefix() . 'rate_master.SaleRate,'.db_prefix() . 'taxes.taxrate,'.db_prefix() . 'rate_master.assigned_rate');
                $this->db->join(db_prefix() . 'rate_master', '' . db_prefix() . 'rate_master.item_id = ' . db_prefix() . 'items.item_code AND '. db_prefix() . 'rate_master.PlantID = ' . db_prefix() . 'items.PlantID AND '. db_prefix() . 'rate_master.state_id ="'.$State.'" AND '. db_prefix() . 'rate_master.distributor_id ="'.$DistType.'"','LEFT');
                $this->db->join(db_prefix() . 'taxes', '' . db_prefix() . 'taxes.id = ' . db_prefix() . 'items.tax');
                $this->db->where(db_prefix() . 'items.PlantID', $selected_company);
                $this->db->where(db_prefix() . 'items.item_code', $ItemID);
                $this->db->from(db_prefix() . 'items');
                $result = $this->db->get()->row();
                return $result;
        }
    }
    
    public function increment_next_number()
    {
        // Update next Scheme number in settings
       $FY = $this->session->userdata('finacial_year'); 
      $selected_company = $this->session->userdata('root_company');
            if($selected_company == 1){
                $this->db->where('name', 'next_scheme_number_for_cspl');
                
            }elseif($selected_company == 2){
                $this->db->where('name', 'next_scheme_number_for_cff');
               
            }elseif($selected_company == 3){
                $this->db->where('name', 'next_scheme_number_for_cbu');
                
            }
        $this->db->set('value', 'value+1', false);
        $this->db->WHERE('FY', $FY);
        $this->db->update(db_prefix() . 'options');
    }
    
    public function GetSchemeDetailByID($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'schememaster.*');
        $this->db->where(db_prefix() . 'schememaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'schememaster.FY', $FY);
        $this->db->from(db_prefix() . 'schememaster');
        $this->db->where(db_prefix() . 'schememaster.SchemeID', $id);
        $result = $this->db->get()->row();
        if($result){
            $this->db->select(db_prefix() . 'schemedetails.*,'.db_prefix() . 'items.description');
            $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'schemedetails.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'schemedetails.PlantID ');
            $this->db->where(db_prefix() . 'schemedetails.PlantID', $selected_company);
            $this->db->where(db_prefix() . 'schemedetails.FY', $FY);
            $this->db->from(db_prefix() . 'schemedetails');
            
            $this->db->where(db_prefix() . 'schemedetails.SchemeID', $id);
            $this->db->order_by(db_prefix() . 'schemedetails.Ordinalno', 'ASC');
            $ItemDetails = $this->db->get()->result_array();
            if($ItemDetails){
                $result->Item = $ItemDetails;
            }
        }
        return $result;
    }
    
    public function GetSchemeByID($id)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'schememaster.*');
        $this->db->where(db_prefix() . 'schememaster.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'schememaster.FY', $FY);
        $this->db->from(db_prefix() . 'schememaster');
        $this->db->where(db_prefix() . 'schememaster.SchemeID', $id);
        $result = $this->db->get()->row();
        return $result;
    }
    
    public function ItemPartyWiseSale($allItem,$FromDate,$ToDate,$states,$client_type,$Item_UnitDisc)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'history.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'items.description,'.db_prefix() . 'history.ItemID,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty');
        $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID ');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID ');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $FY);
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() . 'history.TType', 'O');
        $this->db->where(db_prefix() . 'history.TType2', 'Order');
        $this->db->where(db_prefix() . 'history.TransDate2 >=',$FromDate);
        $this->db->where(db_prefix() . 'history.TransDate2 <=',$ToDate);
        $this->db->where(db_prefix() . 'clients.state', $states);
        $this->db->where(db_prefix() . 'clients.DistributorType', $client_type);
        $this->db->from(db_prefix() . 'history');
        $this->db->where_in(db_prefix() . 'history.ItemID', $allItem);
        $this->db->group_by(db_prefix() . 'history.AccountID,'.db_prefix() . 'history.ItemID');
        $result = $this->db->get()->result_array();
        $html = '';
        if($result){
            $html .= '<div class="table-Show_List tableFixHead2">';
            $html .= '<input type="text" id="ShowSearchInput" onkeyup="ShowSearch()" placeholder="Search.." title="Type in a name" style="float: left;width: 100%;">';
            $html .= '<table class="tree table table-striped table-bordered table-Show_List tableFixHead2" id="Show_List" width="100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th id="sl" style="text-align:left">AccountID</th>';
            $html .= '<th style="text-align:left;">AccountName</th>';
            $html .= '<th style="text-align:left;">ItemID</th>';
            //$html .= '<th style="text-align:left;">ItemName</th>';
            $html .= '<th style="text-align:left;">Amount</th>';
            $html .= '<th style="text-align:left;">BilledQty</th>';
            $html .= '<th style="text-align:left;">SchemeAmt</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $SaleAmtSum = 0; 
            $BilledQtySum = 0; 
            $SchemeAmtSum = 0;
            foreach($result as $value){
                $ShemeAmt = 0;
                foreach($Item_UnitDisc as $value1){
                    $SlabQty = $value1["SlabQtyCases"] * $value1["CaseQty"];
                    if($value1["ItemID"] == $value["ItemID"] && $SlabQty <= $value["BilledQty"]){
                        $ShemeAmt = $value1["DiscAmt"] * $value["BilledQty"];
                        $SchemeAmtSum += $ShemeAmt;
                    }
                }
                if($ShemeAmt > 0){
                    $html .= '<tr>';
                    $html .= '<td style="text-align:center;">'.$value["AccountID"].'</td>';
                    $html .= '<td style="text-align:left;">'.$value["company"].'</td>';
                    $html .= '<td style="text-align:center;">'.$value["ItemID"].'</td>';
                    //$html .= '<td style="text-align:left;">'.$value["description"].'</td>';
                    $html .= '<td style="text-align:right;">'.$value["TaxableAmt"].'</td>';
                    $html .= '<td style="text-align:right;">'.$value["BilledQty"].'</td>';
                    
                    $html .= '<td style="text-align:right;">'.number_format($ShemeAmt, 2, '.', '').'</td>';
                    $SaleAmtSum += $value["TaxableAmt"];
                    $BilledQtySum += $value["BilledQty"];
                    $html .= '</tr>';
                }
                
            }
            $html .= '<td style="text-align:center;" colspan="3"><b>Total</b></td>';
            $html .= '<td style="text-align:right;">'.number_format($SaleAmtSum, 2, '.', '').'</td>';
            $html .= '<td style="text-align:right;">'.number_format($BilledQtySum, 2, '.', '').'</td>';
            $html .= '<td style="text-align:right;">'.number_format($SchemeAmtSum, 2, '.', '').'</td>';
            $html .= '</tbody>';
            $html .= '</table>';
            
            $html .= '</div>';
        }
        return $html;
    }
    public function ItemPartyWiseSaleExport($allItem,$FromDate,$ToDate,$states,$client_type,$Item_UnitDisc)
    {
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        
        $this->db->select(db_prefix() . 'history.AccountID,'.db_prefix() . 'clients.company,'.db_prefix() . 'items.description,'.db_prefix() . 'history.ItemID,SUM('.db_prefix() . 'history.ChallanAmt) AS TaxableAmt,SUM('.db_prefix() . 'history.BilledQty) AS BilledQty');
        $this->db->join(db_prefix() . 'items', '' . db_prefix() . 'items.item_code = ' . db_prefix() . 'history.ItemID AND ' . db_prefix() . 'items.PlantID = ' . db_prefix() . 'history.PlantID ');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.AccountID = ' . db_prefix() . 'history.AccountID AND ' . db_prefix() . 'clients.PlantID = ' . db_prefix() . 'history.PlantID ');
        $this->db->where(db_prefix() . 'history.PlantID', $selected_company);
        $this->db->where(db_prefix() . 'history.FY', $FY);
        $this->db->where(db_prefix() . 'history.BillID IS NOT NULL', NULL, FALSE);
        $this->db->where(db_prefix() . 'history.TType', 'O');
        $this->db->where(db_prefix() . 'history.TType2', 'Order');
        $this->db->where(db_prefix() . 'history.TransDate2 >=',$FromDate);
        $this->db->where(db_prefix() . 'history.TransDate2 <=',$ToDate);
        $this->db->where(db_prefix() . 'clients.state', $states);
        $this->db->where(db_prefix() . 'clients.DistributorType', $client_type);
        $this->db->from(db_prefix() . 'history');
        $this->db->where_in(db_prefix() . 'history.ItemID', $allItem);
        $this->db->group_by(db_prefix() . 'history.AccountID,'.db_prefix() . 'history.ItemID');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function get_company_detail()
     {   
        $selected_company = $this->session->userdata('root_company');
        $sql ='SELECT '.db_prefix().'rootcompany.*
        FROM '.db_prefix().'rootcompany WHERE id = '.$selected_company;
        $result = $this->db->query($sql)->row();
        return $result;
        
     }
    // upload approve latter
    
    function save_upload($SchemeID,$image){
        $selected_company = $this->session->userdata('root_company');
        $FY = $this->session->userdata('finacial_year');
        $data = array(
            'file_name' => $image
        );  
        $this->db->where('PlantID', $selected_company);
        $this->db->where('FY', $FY);
        $this->db->where('SchemeID', $SchemeID);
        $this->db->update(db_prefix() . 'schememaster', $data);
        if($this->db->affected_rows()>0){
            echo true;
        }else{
            echo false;
        }
    }
    
    
}
?>