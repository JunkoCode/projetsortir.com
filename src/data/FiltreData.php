<?php

namespace App\data;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use DateTime;

class FiltreData
{

    /**
     * @var string
     */
    public string $filtreSortieMotCle = '';

    /**
     * @var Integer
     */
    public int $filtreSortieIdUser;

    /**
     * @var Campus[]
     */
    public array $filtreSortieCampus = [];

    /**
     * @var DateTime|null
     */
    public string|null|DateTime $filtreSortieDateMin = '';
    public string|null|DateTime $filtreSortieDateMax = '';

    /**
     * @var boolean
     */
    public $filtreSortieOrganisateur = false;
    public bool $filtreSortieInscrit = false;
    public bool $filtreSortiePasInscrit = false;
    public bool $filtreSortiePassees = false;


}