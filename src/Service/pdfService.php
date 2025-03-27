<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PdfService {
    private $params;

    public function __construct(ParameterBagInterface $parameterBagInterface) {

        $this->params = $parameterBagInterface;
    }

    public function generatePdf($html, $pdf) {
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // $pdfPath = $this->param->get('kernel.project_dir').'/public/pdf/';
        $dompdf->render();
        // $dompdf->stream($filename);
        $dompdf->output();

        $pdfPath = $this->params->get('kernel.project_dir') . '/public/pdf/' . $pdf . '.pdf';
        // enregister le fichier pdf
        file_put_contents($pdfPath, $dompdf->output());

        return $pdfPath;
    }
}
