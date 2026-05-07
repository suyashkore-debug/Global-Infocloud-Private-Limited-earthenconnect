<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Autocheckout extends CI_Controller
{
    public function index()
    {
        $cur_date = date('Y-m-d');
        $this->db->where('type_check', 1);
        $this->db->where('date', $cur_date);
        $tracking = $this->db->get(db_prefix().'check_in_out_app2')->result_array();

        print_r($tracking);
    }
}
