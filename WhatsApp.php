<?php
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "integrated_number": "919511116350",
    "content_type": "template",
    "payload": {
        "messaging_product": "whatsapp",
        "type": "template",
        "template": {
            "name": "customer_ledger_share",
            "language": {
                "code": "en",
                "policy": "deterministic"
            },
            "namespace": "9af5a9d2_f151_4144_8ae2_7e5190a3a07e",
            "to_and_components": [
                {
                    "to": [
                        "919595830789"
                    ],
                    "components": {
                        "body_1": {
                            "type": "text",
                            "value": "Ajinkya"
                        },
                        "body_2": {
                            "type": "text",
                            "value": "01/04/2025"
                        },
                        "body_3": {
                            "type": "text",
                            "value": "11/11/2025"
                        },
                        "body_4": {
                            "type": "text",
                            "value": "https://goodmorning.globalinfocloud.in/"
                        },
                        "button_1": {
                            "subtype": "url",
                            "type": "text",
                            "value": "admin"
                        }
                    }
                }
            ]
        }
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'authkey: 466363AedlbXQ869059f72P1',
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
