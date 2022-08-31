<?php

namespace App\data;

use App\Entity\Campus;
use App\Entity\Utilisateur;

class FiltreData
{

    /**
     * @var string
     */
    public $filtreSortieMotCle = '';

    /**
     * @var Utilisateur
     */
    public $filtreSortieIdUser;

    /**
     * @var Campus[]
     */
    public $filtreSortieCampus = [];

    /**
     * @var \DateTime|null
     */
    public $filtreSortieDateMin = '';
    public $filtreSortieDateMax = '';

    /**
     * @var boolean
     */
    public $filtreSortieOrganisateur = false;
    public $filtreSortieInscrit = false;
    public $filtreSortiePasInscrit = false;
    public $filtreSortiePassees = false;


}