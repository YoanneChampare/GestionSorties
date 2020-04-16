<?php

namespace App\Data;

use Symfony\Component\Validator\Constraints\DateTime;

class SearchData
{
    /**
     * @var string
     */
    public $motCle='';

    /**
     * @var null|datetime
     */
    public $datemin;

    /**
     * @var null|datetime
     */
    public $datemax;

    /**
     * @var boolean
     */
    public $sInscrit=false;

    /**
     * @var boolean
     */
    public $sNonInscrit=false;

    /**
     * @var boolean
     */
    public $sOrganisateur=false;

    /**
     * @var boolean
     */
    public $sPasse=false;

    /**
     * @var NomSite[]
     */
    public $nSite=[];
}
