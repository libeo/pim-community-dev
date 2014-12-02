<?php

namespace Libeo\Bundle\PdfGeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LibeoPdfGeneratorBundle extends Bundle {

    public function getParent() {
        return 'PimPdfGeneratorBundle';
    }

}
