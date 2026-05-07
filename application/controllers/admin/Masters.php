<?php
	
defined('BASEPATH') or exit('No direct script access allowed');

class Masters extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('masters_model');
	}
	public function ArticleMaster()
	{
		if (!has_permission_new('ArticleMaster', '', 'view')) {
			access_denied('Article Master');
		}
		$data['title'] = "Article Master";
		$data['AllPartyList'] = $this->masters_model->GetAllPartyList();
		$data['AllFGList'] = $this->masters_model->GetAllFGList();
		$this->load->view('admin/Masters/ArticleMaster', $data);
	}
	
	/* Get Party Wise Item Wise Article List / ajax */
    public function GetPartyWiseArticleList()
    {
        $AccountID = $this->input->post('AccountID');
        $PartyWiseArticleList = $this->masters_model->GetPartyWiseArticleList($AccountID);
        echo json_encode($PartyWiseArticleList);
    }
    
	/* Save New ItemID Item Wise Party Wise Article / ajax */
    public function SaveItemWiseArticle()
    {
        if (!has_permission_new('ArticleMaster', '', 'create')) {
            access_denied('Invoice Items');
        }
        $data = array(
            'AccountID'=>strtoupper($this->input->post('AccountID')),
            'ArticledataArraylength'=>$this->input->post('ArticledataArraylength'),
            'ArticledataSerializedArr'=>$this->input->post('ArticledataSerializedArr'),
        );
        
        $ArticleData  = $this->masters_model->SaveItemWiseArticle($data);
        echo json_encode($ArticleData);
    }   
    
    /* Save New ItemID Item Wise Party Wise Article / ajax */
    public function UpdateItemWiseArticle()
    {
        if (!has_permission_new('ArticleMaster', '', 'edit')) {
            access_denied('Invoice Items');
        }
        $data = array(
            'AccountID'=>strtoupper($this->input->post('AccountID')),
            'ItemDeleted'=>$this->input->post('ItemDeleted'),
            'ArticledataArraylength'=>$this->input->post('ArticledataArraylength'),
            'ArticledataSerializedArr'=>$this->input->post('ArticledataSerializedArr'),
        );
        $ArticleData  = $this->masters_model->UpdateItemWiseArticle($data);
        echo json_encode($ArticleData);
    }
}
?>