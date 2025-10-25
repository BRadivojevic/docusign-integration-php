<?php
namespace App\Services;

use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Recipients;

final class EnvelopeService {
    private EnvelopesApi $api;
    private string $accountId;

    public function __construct(EnvelopesApi $api, string $accountId) {
        $this->api = $api;
        $this->accountId = $accountId;
    }

    /** Create a simple one-signer envelope. $docBase64 must be base64 of a PDF. */
    public function createSimpleEnvelope(string $email, string $name, string $docBase64, string $subject='Please sign'): string {
        $document = new Document([
            'document_base64' => $docBase64,
            'name' => 'Document',
            'file_extension' => 'pdf',
            'document_id' => '1'
        ]);

        $signHere = new SignHere([
            'anchor_string' => '/signhere/',
            'anchor_units' => 'pixels',
            'anchor_x_offset' => '20',
            'anchor_y_offset' => '10'
        ]);

        $signer = new Signer([
            'email' => $email,
            'name' => $name,
            'recipient_id' => '1',
            'routing_order' => '1',
            'tabs' => new Tabs(['sign_here_tabs' => [$signHere]])
        ]);

        $envelopeDefinition = new EnvelopeDefinition([
            'email_subject' => $subject,
            'documents' => [$document],
            'recipients' => new Recipients(['signers' => [$signer]]),
            'status' => 'sent'
        ]);

        $result = $this->api->createEnvelope($this->accountId, $envelopeDefinition);
        return $result->getEnvelopeId();
    }
}
