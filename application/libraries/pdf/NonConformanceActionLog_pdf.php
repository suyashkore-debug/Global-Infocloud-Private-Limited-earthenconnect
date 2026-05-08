<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class NonConformanceActionLog_pdf extends App_pdf
{
    protected $log;

    public function __construct($invoice)
    {
        $GLOBALS['NonConformanceActionLog_pdf'] = $invoice;

        parent::__construct();

        // $invoice = ['invoice' => $data['log']]
        // $data['log'] is stdClass with ->details array
        $this->log = $invoice['invoice'];

        $this->load_language(0); // no client_id for internal doc, pass 0 or skip
        $this->SetTitle('Non-Conformance & Corrective Action Log');
    }

    public function prepare()
    {
        $this->set_view_vars([
            'log'     => $this->log,
            'details' => $this->log->details ?? [],
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'invoice';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_NonConformanceActionLog_pdf.php';
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/NonConformanceActionLogpdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}