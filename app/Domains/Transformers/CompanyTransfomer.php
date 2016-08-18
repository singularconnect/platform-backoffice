<?php
namespace App\Domains\Transformers;

use App\Domains\Models\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransfomer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Company $resource
     * @return array
     */
    public function transform(Company $resource)
    {
        return [
            'id' => $resource->id,
            'domain' => $resource->domain,
            'database_ips' => $resource->database_ips,
            'database_name' => $resource->database_name,
            'database_server_tag' => $resource->database_server_tag,
            'application_ips' => $resource->application_ips,
            'application_directory' => $resource->application_directory,
            'development_ip' => $resource->development_ip,
            'development_directory' => $resource->development_directory,
            'backoffice_ip' => $resource->backoffice_ip,
            'backoffice_directory' => $resource->backoffice_directory,
            'status' => $resource->status,
        ];
    }

}