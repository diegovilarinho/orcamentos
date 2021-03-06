<?php

namespace Orcamentos\Service;

use Exception;

/**
 * Project Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class Status extends Service
{
    /**
     * Function that gets the Quotes from the company
     * @param                 array $data
     * @return                array $data
     */
    public function get($data)
    {
        $data = json_decode($data);

        if (!isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $company = $this->em->getRepository("Orcamentos\Model\Company")->find($data->companyId);
        
        if (!$company) {
            throw new Exception("Empresa não existe", 1);
        }        

        $projects = $company->getProjectCollection();

        $awaiting = array();
        $aproved = array();
        $nonAproved = array();

        foreach ($projects as $project) {
            foreach ($project->getQuoteCollection() as $quote) {
                switch ($quote->getStatus()) {
                    case 1:
                    case '1':
                        $awaiting[] = $quote;
                        break;
                    
                    case 2:
                    case '2':
                        $aproved[] = $quote;
                        break;

                    case 3:
                    case '3':
                        $nonAproved[] = $quote;
                        break;
                }
            }
        }

        $data = array( 'awaiting' => $awaiting, 'aproved' => $aproved, 'nonAproved' => $nonAproved );

        return $data;
    }
}
